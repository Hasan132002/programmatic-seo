<?php

namespace App\Services\SEO;

use App\Enums\ContentStatus;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Builds and injects internal links between pages within a site.
 *
 * Analyses published page titles to find related content, stores link
 * records in the internal_links table, and injects contextual anchor
 * links into page content HTML.
 */
class InternalLinkBuilder
{
    /**
     * Minimum number of shared words required to consider pages related.
     */
    protected const int MIN_SHARED_WORDS = 2;

    /**
     * Words to ignore when matching page titles (stop words).
     */
    protected const array STOP_WORDS = [
        'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for',
        'of', 'with', 'by', 'from', 'is', 'are', 'was', 'were', 'be', 'been',
        'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would',
        'could', 'should', 'may', 'might', 'shall', 'can', 'it', 'its', 'this',
        'that', 'these', 'those', 'my', 'your', 'his', 'her', 'our', 'their',
        'vs', 'versus', 'best', 'top', 'how', 'what', 'why', 'when', 'where',
    ];

    /**
     * Analyse all published pages in a site and build internal link records.
     *
     * Clears existing internal links for the site and regenerates them
     * based on title similarity analysis between all published page pairs.
     *
     * @param Site $site  The site to build internal links for.
     */
    public function buildForSite(Site $site): void
    {
        // Clear existing internal links for this site
        DB::table('internal_links')->where('site_id', $site->id)->delete();

        $pages = $site->pages()
            ->where('status', ContentStatus::Published->value)
            ->select(['id', 'site_id', 'title', 'slug'])
            ->get();

        if ($pages->count() < 2) {
            return;
        }

        // Build a word index for each page
        $pageWords = [];
        foreach ($pages as $page) {
            $pageWords[$page->id] = $this->extractSignificantWords($page->title);
        }

        $linksToInsert = [];
        $now = now();

        foreach ($pages as $sourcePage) {
            $related = $this->findRelatedFromWordIndex(
                sourcePage: $sourcePage,
                pageWords: $pageWords,
                allPages: $pages,
                limit: 5,
            );

            foreach ($related as $targetPage) {
                $anchorText = $this->generateAnchorText($targetPage);

                $linksToInsert[] = [
                    'site_id'        => $site->id,
                    'source_page_id' => $sourcePage->id,
                    'target_page_id' => $targetPage->id,
                    'anchor_text'    => Str::limit($anchorText, 255, ''),
                    'link_type'      => 'related',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];
            }
        }

        // Batch insert for performance
        if (!empty($linksToInsert)) {
            foreach (array_chunk($linksToInsert, 500) as $chunk) {
                DB::table('internal_links')->insert($chunk);
            }
        }
    }

    /**
     * Find related pages for a given page based on shared title words.
     *
     * @param Page $page   The source page to find related content for.
     * @param int  $limit  Maximum number of related pages to return.
     *
     * @return Collection  Collection of related Page models.
     */
    public function findRelated(Page $page, int $limit = 5): Collection
    {
        $sourceWords = $this->extractSignificantWords($page->title);

        if (empty($sourceWords)) {
            return new Collection();
        }

        // Find pages with titles containing any of the significant words
        $query = Page::where('site_id', $page->site_id)
            ->where('id', '!=', $page->id)
            ->where('status', ContentStatus::Published->value);

        // Build a relevance score using CASE statements for each word
        $scoreParts = [];
        $bindings = [];

        foreach ($sourceWords as $word) {
            $scoreParts[] = "(CASE WHEN LOWER(title) LIKE ? THEN 1 ELSE 0 END)";
            $bindings[] = '%' . strtolower($word) . '%';
        }

        $scoreExpression = implode(' + ', $scoreParts);

        return $query
            ->selectRaw("*, ({$scoreExpression}) as relevance_score", $bindings)
            ->having('relevance_score', '>=', self::MIN_SHARED_WORDS)
            ->orderByDesc('relevance_score')
            ->limit($limit)
            ->get();
    }

    /**
     * Inject contextual internal links within the HTML content of a page.
     *
     * Looks for related page link records and inserts anchor tags at
     * appropriate positions in the content (after paragraphs that
     * mention related keywords).
     *
     * @param string $html         The source HTML content.
     * @param Site   $site         The site model (for URL construction).
     * @param Page   $currentPage  The current page (excluded from linking).
     *
     * @return string  The HTML with internal links injected.
     */
    public function injectLinks(string $html, Site $site, Page $currentPage): string
    {
        // Fetch internal links for this page
        $links = DB::table('internal_links')
            ->where('source_page_id', $currentPage->id)
            ->join('pages', 'pages.id', '=', 'internal_links.target_page_id')
            ->select([
                'internal_links.anchor_text',
                'pages.slug as target_slug',
                'pages.title as target_title',
            ])
            ->limit(5)
            ->get();

        if ($links->isEmpty()) {
            return $html;
        }

        $injectedCount = 0;
        $maxLinks = 5;

        foreach ($links as $link) {
            if ($injectedCount >= $maxLinks) {
                break;
            }

            $targetUrl = $site->url . '/' . $link->target_slug;
            $anchor = htmlspecialchars($link->anchor_text, ENT_QUOTES, 'UTF-8');
            $title = htmlspecialchars($link->target_title, ENT_QUOTES, 'UTF-8');

            // Strategy 1: Replace first occurrence of the anchor text in paragraph content
            $pattern = '/(<p[^>]*>.*?)(' . preg_quote($anchor, '/') . ')(.*?<\/p>)/si';

            if (preg_match($pattern, $html)) {
                $replacement = '$1<a href="' . $targetUrl . '" title="' . $title . '">$2</a>$3';
                $html = preg_replace($pattern, $replacement, $html, 1);
                $injectedCount++;
                continue;
            }

            // Strategy 2: Inject a "Related:" link after the second <h2> or <h3>
            $headingPattern = '/(<\/h[23]>)/i';
            if (preg_match_all($headingPattern, $html, $headingMatches, PREG_OFFSET_SET) && count($headingMatches[0]) >= 2) {
                $insertPosition = $headingMatches[0][1][1] + strlen($headingMatches[0][1][0]);
                $linkHtml = "\n<p class=\"internal-link\"><a href=\"{$targetUrl}\" title=\"{$title}\">{$anchor}</a></p>\n";

                $html = substr($html, 0, $insertPosition) . $linkHtml . substr($html, $insertPosition);
                $injectedCount++;
            }
        }

        return $html;
    }

    /**
     * Extract significant (non-stop) words from a title.
     *
     * @param string $title  The page title.
     *
     * @return array<int, string>  List of significant lowercase words.
     */
    protected function extractSignificantWords(string $title): array
    {
        $words = preg_split('/[\s\-_,.:;!?]+/', strtolower($title));
        $words = array_filter($words, fn (string $word) => strlen($word) >= 3);

        return array_values(
            array_diff($words, self::STOP_WORDS),
        );
    }

    /**
     * Find related pages from the pre-built word index (used in bulk building).
     *
     * @param Page       $sourcePage  The source page.
     * @param array      $pageWords   Word index keyed by page ID.
     * @param Collection $allPages    All pages being analysed.
     * @param int        $limit       Max related pages to return.
     *
     * @return array  Array of related Page models.
     */
    protected function findRelatedFromWordIndex(Page $sourcePage, array $pageWords, Collection $allPages, int $limit): array
    {
        $sourceWords = $pageWords[$sourcePage->id] ?? [];

        if (empty($sourceWords)) {
            return [];
        }

        $scores = [];

        foreach ($pageWords as $pageId => $words) {
            if ($pageId === $sourcePage->id) {
                continue;
            }

            $shared = count(array_intersect($sourceWords, $words));

            if ($shared >= self::MIN_SHARED_WORDS) {
                $scores[$pageId] = $shared;
            }
        }

        arsort($scores);
        $topIds = array_slice(array_keys($scores), 0, $limit, true);

        return $allPages->filter(fn (Page $p) => in_array($p->id, $topIds, true))->values()->all();
    }

    /**
     * Generate appropriate anchor text for a target page.
     *
     * @param Page $page  The target page.
     *
     * @return string  The anchor text to use.
     */
    protected function generateAnchorText(Page $page): string
    {
        // Use the page title, but truncate if too long
        return Str::limit($page->title, 60, '...');
    }
}
