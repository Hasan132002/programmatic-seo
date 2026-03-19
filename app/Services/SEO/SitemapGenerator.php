<?php

namespace App\Services\SEO;

use App\Enums\ContentStatus;
use App\Models\Site;
use Illuminate\Support\Carbon;

/**
 * Generates XML sitemaps for a site's published pages.
 *
 * Uses database cursors for memory-efficient iteration over large page sets.
 * Automatically creates a sitemap index when the page count exceeds 50,000.
 */
class SitemapGenerator
{
    /**
     * Maximum number of URLs allowed in a single sitemap file.
     */
    protected const int MAX_URLS_PER_SITEMAP = 50_000;

    /**
     * Generate a complete XML sitemap (or sitemap index) for the given site.
     *
     * If the site has more than 50,000 published pages, a sitemap index is
     * generated with references to numbered sub-sitemaps. Otherwise, a single
     * sitemap is returned.
     *
     * @param Site $site  The site to generate the sitemap for.
     *
     * @return string  The XML sitemap or sitemap index string.
     */
    public function generateForSite(Site $site): string
    {
        $totalPages = $site->pages()
            ->where('status', ContentStatus::Published->value)
            ->count();

        if ($totalPages > self::MAX_URLS_PER_SITEMAP) {
            return $this->generateSitemapIndex($site, $totalPages);
        }

        return $this->generateSingleSitemap($site);
    }

    /**
     * Generate a single XML sitemap containing all published pages.
     *
     * @param Site     $site    The site model.
     * @param int|null $offset  Optional offset for paginated sub-sitemaps.
     * @param int|null $limit   Optional limit for paginated sub-sitemaps.
     *
     * @return string  The XML sitemap string.
     */
    public function generateSingleSitemap(Site $site, ?int $offset = null, ?int $limit = null): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Add the homepage
        $xml .= $this->buildUrlEntry(
            loc: $site->url,
            lastmod: $site->updated_at,
            changefreq: 'daily',
            priority: '1.0',
        );

        $query = $site->pages()
            ->where('status', ContentStatus::Published->value)
            ->orderBy('published_at', 'desc')
            ->select(['id', 'site_id', 'slug', 'priority', 'updated_at', 'published_at']);

        if ($offset !== null) {
            $query->offset($offset);
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        // Use cursor() for memory efficiency with large page sets
        foreach ($query->cursor() as $page) {
            $xml .= $this->buildUrlEntry(
                loc: $site->url . '/' . $page->slug,
                lastmod: $page->updated_at ?? $page->published_at,
                changefreq: 'weekly',
                priority: number_format((float) ($page->priority ?? 0.5), 1),
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate a sitemap index for sites with more than 50,000 pages.
     *
     * @param Site $site        The site model.
     * @param int  $totalPages  Total number of published pages.
     *
     * @return string  The XML sitemap index string.
     */
    protected function generateSitemapIndex(Site $site, int $totalPages): string
    {
        $sitemapCount = (int) ceil($totalPages / self::MAX_URLS_PER_SITEMAP);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        for ($i = 1; $i <= $sitemapCount; $i++) {
            $xml .= '  <sitemap>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($site->url . "/sitemap-{$i}.xml") . '</loc>' . "\n";
            $xml .= '    <lastmod>' . Carbon::now()->toW3cString() . '</lastmod>' . "\n";
            $xml .= '  </sitemap>' . "\n";
        }

        $xml .= '</sitemapindex>';

        return $xml;
    }

    /**
     * Build a single <url> entry for the sitemap.
     *
     * @param string             $loc         The full URL.
     * @param Carbon|string|null $lastmod     The last modification date.
     * @param string             $changefreq  The change frequency hint.
     * @param string             $priority    The priority value (0.0 to 1.0).
     *
     * @return string  The XML <url> block.
     */
    protected function buildUrlEntry(
        string $loc,
        Carbon|string|null $lastmod = null,
        string $changefreq = 'weekly',
        string $priority = '0.5',
    ): string {
        $xml = '  <url>' . "\n";
        $xml .= '    <loc>' . htmlspecialchars($loc) . '</loc>' . "\n";

        if ($lastmod) {
            $date = $lastmod instanceof Carbon ? $lastmod : Carbon::parse($lastmod);
            $xml .= '    <lastmod>' . $date->toW3cString() . '</lastmod>' . "\n";
        }

        $xml .= '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
        $xml .= '    <priority>' . $priority . '</priority>' . "\n";
        $xml .= '  </url>' . "\n";

        return $xml;
    }
}
