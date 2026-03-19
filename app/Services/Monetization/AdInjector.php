<?php

namespace App\Services\Monetization;

use App\Models\Site;

/**
 * Injects advertisement placements into page HTML content.
 *
 * Supports multiple ad positions: before_content, after_h2, after_content,
 * and sidebar. Uses Google AdSense when a publisher ID is configured on
 * the site, or custom ad HTML from site settings.
 */
class AdInjector
{
    /**
     * Inject ad placements into the HTML content based on site configuration.
     *
     * Ad positions are controlled by the site's settings['ad_placements'] array.
     * Each placement specifies a position and optional custom HTML override.
     *
     * @param string $html  The page HTML content.
     * @param Site   $site  The site model with ad configuration.
     *
     * @return string  The HTML with ad placements injected.
     */
    public function inject(string $html, Site $site): string
    {
        $settings = $site->settings ?? [];
        $placements = $settings['ad_placements'] ?? $this->getDefaultPlacements();
        $publisherId = $site->adsense_publisher_id;

        if (empty($publisherId) && !$this->hasCustomAds($placements)) {
            return $html;
        }

        foreach ($placements as $placement) {
            if (empty($placement['enabled'] ?? true)) {
                continue;
            }

            $position = $placement['position'] ?? '';
            $adHtml = $placement['custom_html'] ?? $this->buildAdSenseUnit($publisherId, $placement);

            if (empty($adHtml)) {
                continue;
            }

            $wrappedAd = $this->wrapAdUnit($adHtml, $position);

            $html = match ($position) {
                'before_content' => $this->injectBeforeContent($html, $wrappedAd),
                'after_h2'       => $this->injectAfterFirstH2($html, $wrappedAd),
                'after_content'  => $this->injectAfterContent($html, $wrappedAd),
                'sidebar'        => $this->injectSidebar($html, $wrappedAd),
                default          => $html,
            };
        }

        return $html;
    }

    /**
     * Inject an ad unit before the main content (at the very top).
     */
    protected function injectBeforeContent(string $html, string $adHtml): string
    {
        return $adHtml . "\n" . $html;
    }

    /**
     * Inject an ad unit after the first <h2> element.
     */
    protected function injectAfterFirstH2(string $html, string $adHtml): string
    {
        $pattern = '/(<\/h2>)/i';

        // Replace only the first occurrence
        $count = 0;

        return preg_replace_callback($pattern, function (array $match) use ($adHtml, &$count) {
            $count++;
            if ($count === 1) {
                return $match[0] . "\n" . $adHtml;
            }
            return $match[0];
        }, $html);
    }

    /**
     * Inject an ad unit after the main content (at the very end).
     */
    protected function injectAfterContent(string $html, string $adHtml): string
    {
        return $html . "\n" . $adHtml;
    }

    /**
     * Inject a sidebar ad unit by wrapping the content in a flex container.
     *
     * If the content already has a sidebar wrapper, inserts the ad into it.
     */
    protected function injectSidebar(string $html, string $adHtml): string
    {
        // Check if content already has a sidebar container
        if (str_contains($html, 'class="sidebar"') || str_contains($html, 'id="sidebar"')) {
            // Insert ad into existing sidebar
            $html = preg_replace(
                '/(<(?:div|aside)[^>]*(?:class="[^"]*sidebar[^"]*"|id="sidebar")[^>]*>)/i',
                '$1' . "\n" . $adHtml,
                $html,
                1,
            );

            return $html;
        }

        // Wrap content in a layout with sidebar
        return '<div class="content-with-sidebar" style="display:flex;gap:2rem;">'
            . '<div class="main-content" style="flex:1;">' . $html . '</div>'
            . '<aside class="sidebar" style="width:300px;">' . $adHtml . '</aside>'
            . '</div>';
    }

    /**
     * Build a Google AdSense ad unit HTML snippet.
     *
     * @param string|null $publisherId  The AdSense publisher ID.
     * @param array       $placement    The placement configuration.
     *
     * @return string  The AdSense script/ins tag HTML.
     */
    protected function buildAdSenseUnit(?string $publisherId, array $placement): string
    {
        if (empty($publisherId)) {
            return '';
        }

        $adSlot = $placement['ad_slot'] ?? '';
        $adFormat = $placement['ad_format'] ?? 'auto';
        $fullWidthResponsive = $placement['full_width_responsive'] ?? 'true';

        return '<ins class="adsbygoogle"'
            . ' style="display:block"'
            . ' data-ad-client="' . htmlspecialchars($publisherId, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-ad-slot="' . htmlspecialchars($adSlot, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-ad-format="' . htmlspecialchars($adFormat, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-full-width-responsive="' . htmlspecialchars($fullWidthResponsive, ENT_QUOTES, 'UTF-8') . '"></ins>'
            . '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
    }

    /**
     * Wrap an ad unit in a container div with position-based class.
     */
    protected function wrapAdUnit(string $adHtml, string $position): string
    {
        $sanitisedPosition = preg_replace('/[^a-z0-9_-]/', '', $position);

        return '<div class="ad-placement ad-' . $sanitisedPosition . '" data-ad-position="' . $sanitisedPosition . '">'
            . $adHtml
            . '</div>';
    }

    /**
     * Check if any placements have custom ad HTML configured.
     */
    protected function hasCustomAds(array $placements): bool
    {
        foreach ($placements as $placement) {
            if (!empty($placement['custom_html'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the default ad placement configuration.
     *
     * @return array<int, array>  Default placement definitions.
     */
    protected function getDefaultPlacements(): array
    {
        return [
            [
                'position' => 'before_content',
                'enabled'  => true,
            ],
            [
                'position' => 'after_h2',
                'enabled'  => true,
            ],
            [
                'position' => 'after_content',
                'enabled'  => true,
            ],
        ];
    }
}
