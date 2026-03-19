<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->meta_title ?? $page->title }}</title>
    <meta name="description" content="{{ $page->meta_description ?? '' }}">
    <link rel="canonical" href="{{ $page->canonical_url ?? url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $page->meta_title ?? $page->title }}">
    <meta property="og:description" content="{{ $page->meta_description ?? '' }}">
    @if($page->og_image)
    <meta property="og:image" content="{{ $page->og_image }}">
    @endif
    <meta property="og:type" content="{{ $site->seo_defaults['og_type'] ?? 'article' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $site->name }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page->meta_title ?? $page->title }}">
    <meta name="twitter:description" content="{{ $page->meta_description ?? '' }}">

    @if($page->schema_markup)
    <script type="application/ld+json">{!! json_encode($page->schema_markup, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    @php
        $settings = $site->settings ?? [];
        $primaryColor = $settings['primary_color'] ?? '#4f46e5';
        $fontFamily = $settings['font_family'] ?? 'Inter, system-ui, -apple-system, sans-serif';
        $showBreadcrumbs = $settings['show_breadcrumbs'] ?? true;

        // Derive color shades from primary
        $primaryRgb = sscanf($primaryColor, "#%02x%02x%02x");
        $primaryLight = sprintf("rgba(%d,%d,%d,0.08)", ...$primaryRgb);
        $primaryMedium = sprintf("rgba(%d,%d,%d,0.15)", ...$primaryRgb);
    @endphp

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-light: {{ $primaryLight }};
            --primary-medium: {{ $primaryMedium }};
            --font-family: {{ $fontFamily }};
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --bg-light: #f8fafc;
            --bg-card: #ffffff;
            --radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-family);
            line-height: 1.7;
            color: var(--text-secondary);
            background: var(--bg-light);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Header */
        .site-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.95);
        }
        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .site-logo {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        .site-logo span { color: var(--primary); }
        .header-nav { display: flex; gap: 24px; align-items: center; }
        .header-nav a {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s;
        }
        .header-nav a:hover { color: var(--primary); }

        /* Breadcrumbs */
        .breadcrumbs {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 24px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .breadcrumbs a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }
        .breadcrumbs a:hover { color: var(--primary); }
        .breadcrumbs .sep { margin: 0 8px; opacity: 0.5; }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px 60px;
        }

        /* Article Card */
        .article-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
        }

        /* Article Header */
        .article-header {
            padding: 48px 48px 32px;
            border-bottom: 1px solid var(--border);
        }
        .article-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
        }
        .article-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .article-meta .dot { width: 4px; height: 4px; border-radius: 50%; background: var(--text-muted); opacity: 0.5; }

        /* Article Body - Template Content Styles */
        .article-body {
            padding: 40px 48px 48px;
        }
        .article-body h1 { font-size: 2rem; font-weight: 800; color: var(--text-primary); margin: 40px 0 16px; line-height: 1.25; letter-spacing: -0.02em; }
        .article-body h2 { font-size: 1.6rem; font-weight: 700; color: var(--text-primary); margin: 36px 0 14px; line-height: 1.3; letter-spacing: -0.01em; }
        .article-body h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin: 28px 0 12px; line-height: 1.4; }
        .article-body h4 { font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin: 24px 0 10px; }
        .article-body p { margin-bottom: 16px; line-height: 1.8; color: var(--text-secondary); font-size: 1.05rem; }
        .article-body ul, .article-body ol { margin-bottom: 20px; padding-left: 28px; }
        .article-body li { margin-bottom: 8px; line-height: 1.7; color: var(--text-secondary); }
        .article-body a { color: var(--primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid transparent; transition: border-color 0.2s; }
        .article-body a:hover { border-bottom-color: var(--primary); }
        .article-body img { max-width: 100%; height: auto; border-radius: var(--radius); margin: 24px 0; }
        .article-body blockquote {
            border-left: 4px solid var(--primary);
            padding: 16px 24px;
            margin: 24px 0;
            background: var(--primary-light);
            border-radius: 0 var(--radius) var(--radius) 0;
            color: var(--text-primary);
            font-style: italic;
        }
        .article-body table { width: 100%; border-collapse: separate; border-spacing: 0; margin: 24px 0; border-radius: var(--radius); overflow: hidden; border: 1px solid var(--border); }
        .article-body thead th { background: var(--primary); color: white; padding: 14px 18px; text-align: left; font-weight: 600; font-size: 0.9rem; }
        .article-body tbody td { padding: 12px 18px; border-bottom: 1px solid var(--border); font-size: 0.95rem; }
        .article-body tbody tr:last-child td { border-bottom: none; }
        .article-body tbody tr:hover { background: var(--primary-light); }
        .article-body hr { border: none; border-top: 1px solid var(--border); margin: 32px 0; }
        .article-body strong { color: var(--text-primary); font-weight: 600; }
        .article-body code { background: var(--bg-light); padding: 2px 8px; border-radius: 6px; font-size: 0.9em; color: var(--primary); font-family: 'SF Mono', Monaco, monospace; }
        .article-body pre { background: #1e293b; color: #e2e8f0; padding: 20px 24px; border-radius: var(--radius); overflow-x: auto; margin: 24px 0; font-size: 0.9rem; line-height: 1.6; }
        .article-body pre code { background: none; padding: 0; color: inherit; }
        .article-body details { border: 1px solid var(--border); border-radius: var(--radius); margin-bottom: 12px; }
        .article-body details summary { padding: 16px 20px; cursor: pointer; font-weight: 600; color: var(--text-primary); }
        .article-body details[open] summary { border-bottom: 1px solid var(--border); }
        .article-body details p { padding: 16px 20px; margin: 0; }

        /* Related Pages */
        .related-section {
            margin-top: 32px;
        }
        .related-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
            letter-spacing: -0.01em;
        }
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }
        .related-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            text-decoration: none;
            transition: all 0.2s;
            display: block;
        }
        .related-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .related-card h3 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
            line-height: 1.4;
        }
        .related-card .arrow {
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 8px;
            display: inline-block;
        }

        /* Footer */
        .site-footer {
            background: var(--text-primary);
            color: var(--text-muted);
            padding: 40px 24px;
            margin-top: 60px;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-brand { font-weight: 700; color: white; font-size: 1.1rem; }
        .footer-copy { font-size: 0.85rem; }

        /* AdSense placeholder */
        .ad-banner {
            margin: 24px 0;
            padding: 16px;
            background: var(--bg-light);
            border: 1px dashed var(--border);
            border-radius: var(--radius);
            text-align: center;
            min-height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .article-header { padding: 32px 24px 24px; }
            .article-header h1 { font-size: 1.75rem; }
            .article-body { padding: 24px; }
            .article-body h2 { font-size: 1.35rem; }
            .article-body p { font-size: 1rem; }
            .related-grid { grid-template-columns: 1fr; }
            .footer-inner { flex-direction: column; gap: 12px; text-align: center; }
        }
    </style>

    @if($page->template && $page->template->layout_css)
    <style>{!! $page->template->layout_css !!}</style>
    @endif

    @if($site->adsense_publisher_id)
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $site->adsense_publisher_id }}" crossorigin="anonymous"></script>
    @endif
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="header-inner">
            <a href="/" class="site-logo">{{ $site->name }}</a>
            <nav class="header-nav">
                <a href="/">Home</a>
            </nav>
        </div>
    </header>

    @if($showBreadcrumbs)
    <nav class="breadcrumbs">
        <a href="/">Home</a>
        <span class="sep">/</span>
        <span>{{ $page->title }}</span>
    </nav>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        <article class="article-card">
            <div class="article-header">
                <h1>{{ $page->title }}</h1>
                <div class="article-meta">
                    <span>{{ $site->name }}</span>
                    <span class="dot"></span>
                    <span>{{ $page->published_at ? $page->published_at->format('M d, Y') : $page->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <div class="article-body">
                {!! $page->content_html !!}
            </div>
        </article>

        @if($internalLinks->isNotEmpty())
        <section class="related-section">
            <h2 class="related-title">Related Pages</h2>
            <div class="related-grid">
                @foreach($internalLinks as $link)
                <a href="/{{ $link->slug }}" class="related-card">
                    <h3>{{ $link->title }}</h3>
                    <span class="arrow">Read more &rarr;</span>
                </a>
                @endforeach
            </div>
        </section>
        @endif
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-inner">
            <span class="footer-brand">{{ $site->name }}</span>
            <span class="footer-copy">&copy; {{ date('Y') }} {{ $site->name }}. All rights reserved.</span>
        </div>
    </footer>
</body>
</html>
