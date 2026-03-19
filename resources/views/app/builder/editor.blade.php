<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($page) ? 'Edit: ' . $page->title : 'New Page' }} - Visual Builder | {{ config('app.name', 'Programmatic SEO') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- App Styles + Builder Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/builder.js'])

    @livewireStyles

    <style>
        /* Full-screen builder layout - no scroll on body */
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
            background: #111827;
        }

        /* Override GrapesJS default styles for our dark theme */
        .gjs-one-bg {
            background-color: #1f2937;
        }
        .gjs-two-color {
            color: #d1d5db;
        }
        .gjs-three-bg {
            background-color: #374151;
        }
        .gjs-four-color,
        .gjs-four-color-h:hover {
            color: #818cf8;
        }

        /* Ensure the editor fills available space */
        #gjs-editor .gjs-editor {
            background: #e5e7eb;
        }

        /* Remove default GrapesJS panels since we use our own */
        .gjs-pn-panels {
            display: none !important;
        }

        /* Badge for selected components */
        .gjs-badge {
            background: #4f46e5;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 3px;
        }

        /* Highlight area */
        .gjs-highlighter {
            outline: 2px solid #818cf8;
        }

        /* Selection border */
        .gjs-selected {
            outline: 2px solid #4f46e5 !important;
        }

        /* Ensure trait manager inputs look right */
        .gjs-trt-trait .gjs-field {
            background: #374151;
            border: 1px solid #4b5563;
            color: #d1d5db;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="h-screen flex flex-col">
        @if(isset($page))
            <livewire:app.builder.page-builder-wrapper :site="$site" :page="$page" />
        @else
            <livewire:app.builder.page-builder-wrapper :site="$site" />
        @endif
    </div>

    @livewireScripts
</body>
</html>
