<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Programmatic SEO') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .auth-floating-1 {
                width: 300px;
                height: 300px;
                top: 10%;
                left: -5%;
                background: rgba(255, 255, 255, 0.08);
                animation: float 8s ease-in-out infinite;
            }
            .auth-floating-2 {
                width: 200px;
                height: 200px;
                bottom: 15%;
                right: -3%;
                background: rgba(255, 255, 255, 0.06);
                animation: floatSlow 10s ease-in-out infinite;
                animation-delay: 1s;
            }
            .auth-floating-3 {
                width: 150px;
                height: 150px;
                top: 55%;
                left: 15%;
                background: rgba(255, 255, 255, 0.05);
                animation: float 7s ease-in-out infinite;
                animation-delay: 2s;
            }
            .auth-floating-4 {
                width: 80px;
                height: 80px;
                top: 20%;
                right: 10%;
                background: rgba(255, 255, 255, 0.07);
                border-radius: 20%;
                animation: floatSlow 9s ease-in-out infinite;
                animation-delay: 0.5s;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            {{-- Left Side - Gradient Branding Panel --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 relative overflow-hidden">
                {{-- Floating Animated Shapes --}}
                <div class="floating-shape auth-floating-1"></div>
                <div class="floating-shape auth-floating-2"></div>
                <div class="floating-shape auth-floating-3"></div>
                <div class="floating-shape auth-floating-4" style="border-radius: 20%;"></div>

                {{-- Content --}}
                <div class="relative z-10 flex flex-col justify-center px-12 xl:px-20 w-full">
                    {{-- Logo & App Name --}}
                    <div class="animate-fade-in">
                        <a href="/" class="flex items-center space-x-3 mb-12">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-white">Programmatic SEO</span>
                        </a>
                    </div>

                    {{-- Headline --}}
                    <div class="animate-fade-in-up" style="animation-delay: 0.2s; opacity: 0;">
                        <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-6">
                            Generate thousands of SEO pages in minutes.
                        </h1>
                        <p class="text-lg text-indigo-100/80 mb-12 max-w-md">
                            The all-in-one platform for programmatic SEO. Scale your content, rank faster, grow organically.
                        </p>
                    </div>

                    {{-- Feature Highlights --}}
                    <div class="space-y-5">
                        <div class="flex items-center space-x-4 animate-fade-in-up" style="animation-delay: 0.4s; opacity: 0;">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/15 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">AI-Powered Content</p>
                                <p class="text-indigo-200/70 text-sm">Generate unique, high-quality content at scale</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 animate-fade-in-up" style="animation-delay: 0.6s; opacity: 0;">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/15 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Drag & Drop Builder</p>
                                <p class="text-indigo-200/70 text-sm">Build page templates without coding</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 animate-fade-in-up" style="animation-delay: 0.8s; opacity: 0;">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/15 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Built-in SEO Analytics</p>
                                <p class="text-indigo-200/70 text-sm">Track rankings and optimize performance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side - Form Area --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white px-6 sm:px-12">
                {{-- Mobile Logo (visible on small screens) --}}
                <div class="lg:hidden mb-8 animate-fade-in">
                    <a href="/" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold gradient-text">Programmatic SEO</span>
                    </a>
                </div>

                {{-- Form Slot --}}
                <div class="w-full max-w-md animate-scale-in">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
