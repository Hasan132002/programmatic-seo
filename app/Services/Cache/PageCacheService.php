<?php

namespace App\Services\Cache;

use App\Enums\ContentStatus;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Facades\Storage;

/**
 * File-based page cache service for serving pre-rendered static HTML.
 *
 * Stores rendered page HTML in the local filesystem under
 * storage/app/cache/pages/{site_id}/{slug}.html for fast retrieval
 * by the web server or application middleware.
 */
class PageCacheService
{
    /**
     * Base directory for page cache files relative to the storage disk.
     */
    protected const string CACHE_DIR = 'cache/pages';

    /**
     * Cache a rendered page's HTML to the filesystem.
     *
     * Writes the page's content_html to a static HTML file that can
     * be served directly without hitting the database.
     *
     * @param Page $page  The published page to cache.
     */
    public function cache(Page $page): void
    {
        $path = $this->buildPath($page->site_id, $page->slug);
        $content = $page->content_html ?? '';

        Storage::disk('local')->put($path, $content);
    }

    /**
     * Retrieve a cached page's HTML content.
     *
     * @param int    $siteId  The site ID.
     * @param string $slug    The page slug.
     *
     * @return string|null  The cached HTML content, or null if not found.
     */
    public function get(int $siteId, string $slug): ?string
    {
        $path = $this->buildPath($siteId, $slug);

        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        return Storage::disk('local')->get($path);
    }

    /**
     * Invalidate (delete) a cached page.
     *
     * @param Page $page  The page whose cache should be removed.
     */
    public function invalidate(Page $page): void
    {
        $path = $this->buildPath($page->site_id, $page->slug);

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
    }

    /**
     * Invalidate all cached pages for an entire site.
     *
     * Deletes the site's cache directory and all files within it.
     *
     * @param int $siteId  The site ID whose cache should be cleared.
     */
    public function invalidateSite(int $siteId): void
    {
        $directory = self::CACHE_DIR . '/' . $siteId;

        if (Storage::disk('local')->exists($directory)) {
            Storage::disk('local')->deleteDirectory($directory);
        }
    }

    /**
     * Warm up the cache by pre-rendering all published pages for a site.
     *
     * Uses database cursor for memory efficiency when processing large
     * numbers of pages.
     *
     * @param Site $site  The site to warm cache for.
     */
    public function warmUp(Site $site): void
    {
        $pages = $site->pages()
            ->where('status', ContentStatus::Published->value)
            ->select(['id', 'site_id', 'slug', 'content_html'])
            ->cursor();

        foreach ($pages as $page) {
            if (!empty($page->content_html)) {
                $this->cache($page);
            }
        }
    }

    /**
     * Build the cache file path for a page.
     *
     * Sanitises the slug to create a safe filesystem path. Nested slugs
     * (containing slashes) are flattened using double underscores.
     *
     * @param int    $siteId  The site ID.
     * @param string $slug    The page slug.
     *
     * @return string  The relative path within the storage disk.
     */
    protected function buildPath(int $siteId, string $slug): string
    {
        // Sanitise slug for filesystem safety
        $safeSlug = str_replace(['/', '\\'], '__', $slug);
        $safeSlug = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $safeSlug);

        return self::CACHE_DIR . '/' . $siteId . '/' . $safeSlug . '.html';
    }
}
