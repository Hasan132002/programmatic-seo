<?php

namespace App\Services\Monetization;

use App\Models\Site;

/**
 * Replaces keywords and URLs in page HTML with affiliate links.
 *
 * Scans the content for configured keyword-to-affiliate mappings and
 * original URLs that should be replaced with affiliate-tracked URLs.
 * Respects replacement limits to avoid over-linking.
 */
class AffiliateLinkReplacer
{
    /**
     * Maximum number of affiliate link replacements per keyword per page.
     */
    protected const int MAX_REPLACEMENTS_PER_KEYWORD = 3;

    /**
     * Process the HTML content, replacing keywords and URLs with affiliate links.
     *
     * Affiliate configuration is read from site->settings['affiliate_links'],
     * which should be an array of entries with:
     *   - keyword: The text to match (for keyword replacement)
     *   - url: The original URL to replace (for URL replacement)
     *   - affiliate_url: The affiliate link to use
     *   - rel: Optional rel attribute (defaults to "nofollow sponsored")
     *   - max_replacements: Optional per-keyword limit
     *
     * @param string $html  The page HTML content.
     * @param Site   $site  The site model with affiliate link configuration.
     *
     * @return string  The HTML with affiliate links applied.
     */
    public function process(string $html, Site $site): string
    {
        $settings = $site->settings ?? [];
        $affiliateLinks = $settings['affiliate_links'] ?? [];

        if (empty($affiliateLinks)) {
            return $html;
        }

        foreach ($affiliateLinks as $config) {
            if (!empty($config['keyword'])) {
                $html = $this->replaceKeyword($html, $config);
            }

            if (!empty($config['url'])) {
                $html = $this->replaceUrl($html, $config);
            }
        }

        return $html;
    }

    /**
     * Replace occurrences of a keyword in text content with an affiliate anchor tag.
     *
     * Only replaces keywords that appear in text nodes (outside of existing
     * anchor tags and HTML attributes) to avoid breaking the markup.
     *
     * @param string $html    The HTML content.
     * @param array  $config  The affiliate link configuration entry.
     *
     * @return string  The HTML with keyword replacements applied.
     */
    protected function replaceKeyword(string $html, array $config): string
    {
        $keyword = $config['keyword'];
        $affiliateUrl = $config['affiliate_url'] ?? '';
        $rel = $config['rel'] ?? 'nofollow sponsored';
        $maxReplacements = (int) ($config['max_replacements'] ?? self::MAX_REPLACEMENTS_PER_KEYWORD);

        if (empty($affiliateUrl)) {
            return $html;
        }

        $escapedKeyword = preg_quote($keyword, '/');
        $safeUrl = htmlspecialchars($affiliateUrl, ENT_QUOTES, 'UTF-8');
        $safeRel = htmlspecialchars($rel, ENT_QUOTES, 'UTF-8');

        // Match keyword that is NOT inside an HTML tag or existing <a> tag.
        // Strategy: Split content by tags, only replace in text segments.
        $replacementCount = 0;

        $result = preg_replace_callback(
            '/(<a\b[^>]*>.*?<\/a>|<[^>]+>)|(' . $escapedKeyword . ')/si',
            function (array $matches) use ($safeUrl, $safeRel, $keyword, $maxReplacements, &$replacementCount) {
                // If it's an HTML tag or anchor, leave it untouched
                if (!empty($matches[1])) {
                    return $matches[0];
                }

                // It's a keyword match in text content
                if ($replacementCount >= $maxReplacements) {
                    return $matches[0];
                }

                $replacementCount++;

                return '<a href="' . $safeUrl . '" rel="' . $safeRel . '" target="_blank">'
                    . htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8')
                    . '</a>';
            },
            $html,
        );

        return $result ?? $html;
    }

    /**
     * Replace original URLs in existing anchor tags with affiliate URLs.
     *
     * Finds <a href="original_url"> tags and swaps the href to the
     * affiliate URL, adding appropriate rel attributes.
     *
     * @param string $html    The HTML content.
     * @param array  $config  The affiliate link configuration entry.
     *
     * @return string  The HTML with URL replacements applied.
     */
    protected function replaceUrl(string $html, array $config): string
    {
        $originalUrl = $config['url'];
        $affiliateUrl = $config['affiliate_url'] ?? '';
        $rel = $config['rel'] ?? 'nofollow sponsored';

        if (empty($affiliateUrl)) {
            return $html;
        }

        $escapedUrl = preg_quote($originalUrl, '/');
        $safeAffiliateUrl = htmlspecialchars($affiliateUrl, ENT_QUOTES, 'UTF-8');
        $safeRel = htmlspecialchars($rel, ENT_QUOTES, 'UTF-8');

        // Replace href in anchor tags matching the original URL
        $result = preg_replace_callback(
            '/(<a\s[^>]*?)href=["\']' . $escapedUrl . '["\']([^>]*>)/si',
            function (array $matches) use ($safeAffiliateUrl, $safeRel) {
                $beforeHref = $matches[1];
                $afterHref = $matches[2];

                // Build the replacement tag
                $tag = $beforeHref . 'href="' . $safeAffiliateUrl . '"';

                // Add or update rel attribute
                if (preg_match('/rel=["\'][^"\']*["\']/i', $tag)) {
                    $tag = preg_replace(
                        '/rel=["\']([^"\']*)["\']/',
                        'rel="' . $safeRel . '"',
                        $tag,
                    );
                } else {
                    $tag .= ' rel="' . $safeRel . '"';
                }

                // Add target="_blank" if not present
                if (!str_contains($tag, 'target=')) {
                    $tag .= ' target="_blank"';
                }

                return $tag . $afterHref;
            },
            $html,
        );

        return $result ?? $html;
    }
}
