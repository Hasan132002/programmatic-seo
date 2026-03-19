<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($pages as $page)
    <url>
        <loc>{{ $site->url }}/{{ $page->slug }}</loc>
        <lastmod>{{ $page->updated_at->toW3cString() }}</lastmod>
        <priority>{{ $page->priority }}</priority>
    </url>
    @endforeach
</urlset>
