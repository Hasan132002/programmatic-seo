<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Programmatic SEO') }} - Generate 1000s of SEO Pages</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Landing page specific animations */
            @keyframes heroFloat {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-15px); }
            }
            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .hero-gradient-text {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 25%, #a855f7 50%, #7c3aed 75%, #4f46e5 100%);
                background-size: 200% 200%;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                animation: gradientShift 6s ease infinite;
            }
            .hero-blob-1 {
                position: absolute;
                width: 600px;
                height: 600px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
                top: -200px;
                right: -100px;
                animation: heroFloat 8s ease-in-out infinite;
            }
            .hero-blob-2 {
                position: absolute;
                width: 400px;
                height: 400px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(168, 85, 247, 0.1) 0%, transparent 70%);
                bottom: -100px;
                left: -100px;
                animation: heroFloat 10s ease-in-out infinite;
                animation-delay: 2s;
            }
            .pricing-popular {
                position: relative;
            }
            .pricing-popular::before {
                content: '';
                position: absolute;
                inset: -2px;
                border-radius: 1rem;
                background: linear-gradient(135deg, #6366f1, #a855f7);
                z-index: -1;
            }
        </style>
    </head>
    <body class="antialiased font-sans bg-white text-gray-900">

        {{-- ============================================= --}}
        {{-- NAVIGATION --}}
        {{-- ============================================= --}}
        <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    {{-- Logo --}}
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">Programmatic SEO</span>
                    </a>

                    {{-- Auth Links --}}
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition duration-200">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition duration-200">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg btn-gradient">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        {{-- ============================================= --}}
        {{-- HERO SECTION --}}
        {{-- ============================================= --}}
        <section class="relative overflow-hidden pt-32 pb-20 sm:pt-40 sm:pb-28">
            <div class="hero-blob-1"></div>
            <div class="hero-blob-2"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                {{-- Badge --}}
                <div class="animate-fade-in inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 mb-8">
                    <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                    <span class="text-sm font-semibold text-indigo-700">Powered by AI</span>
                </div>

                {{-- Main Headline --}}
                <h1 class="animate-fade-in-up text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight leading-tight" style="opacity: 0; animation-delay: 0.1s;">
                    Generate <span class="hero-gradient-text">1000s of SEO Pages</span><br class="hidden sm:block"> in Minutes
                </h1>

                {{-- Subtitle --}}
                <p class="animate-fade-in-up mt-6 text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto" style="opacity: 0; animation-delay: 0.3s;">
                    The all-in-one platform for programmatic SEO. Upload your data, choose a template, and watch your pages come to life with AI-powered content generation.
                </p>

                {{-- CTA Buttons --}}
                <div class="animate-fade-in-up mt-10 flex flex-col sm:flex-row items-center justify-center gap-4" style="opacity: 0; animation-delay: 0.5s;">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-white rounded-xl btn-gradient shadow-lg shadow-indigo-500/25">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-gray-700 bg-white rounded-xl border-2 border-gray-200 hover:border-indigo-300 hover:text-indigo-600 transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Login
                    </a>
                </div>

                {{-- Social Proof --}}
                <div class="animate-fade-in-up mt-14 flex flex-col sm:flex-row items-center justify-center gap-6 text-sm text-gray-400" style="opacity: 0; animation-delay: 0.7s;">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-400 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-400 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-400 border-2 border-white"></div>
                        </div>
                        <span>Trusted by 2,000+ marketers</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="ml-1">4.9/5 rating</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================= --}}
        {{-- FEATURES SECTION --}}
        {{-- ============================================= --}}
        <section class="py-20 sm:py-28 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Section Header --}}
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3">Features</h2>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Everything you need to scale SEO</p>
                    <p class="mt-4 text-lg text-gray-500">Built for marketers, SEO professionals, and content teams who need to generate pages at scale.</p>
                </div>

                {{-- Feature Cards --}}
                <div class="grid md:grid-cols-3 gap-8">
                    {{-- Feature 1: AI Content Generation --}}
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">AI Content Generation</h3>
                        <p class="text-gray-500 leading-relaxed">Generate unique, SEO-optimized content for every page using advanced AI. Each page gets tailored copy that reads naturally and ranks well.</p>
                        <ul class="mt-5 space-y-2">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                GPT-4 powered writing
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Unique content per page
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Multi-language support
                            </li>
                        </ul>
                    </div>

                    {{-- Feature 2: Drag & Drop Builder --}}
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Drag & Drop Builder</h3>
                        <p class="text-gray-500 leading-relaxed">Design beautiful page templates with our intuitive visual builder. No coding required -- just drag, drop, and publish.</p>
                        <ul class="mt-5 space-y-2">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Visual template editor
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Dynamic data binding
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Responsive by default
                            </li>
                        </ul>
                    </div>

                    {{-- Feature 3: Built-in SEO --}}
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Built-in SEO</h3>
                        <p class="text-gray-500 leading-relaxed">Every page is automatically optimized for search engines with proper meta tags, structured data, sitemaps, and internal linking.</p>
                        <ul class="mt-5 space-y-2">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Auto meta tags & schema
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                XML sitemap generation
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Smart internal linking
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================= --}}
        {{-- HOW IT WORKS SECTION --}}
        {{-- ============================================= --}}
        <section class="py-20 sm:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Section Header --}}
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3">How it works</h2>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Three simple steps to scale</p>
                    <p class="mt-4 text-lg text-gray-500">Go from data to published pages in under 10 minutes.</p>
                </div>

                {{-- Steps --}}
                <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                    {{-- Step 1 --}}
                    <div class="text-center">
                        <div class="relative inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl mb-6 shadow-lg shadow-indigo-500/25">
                            <span class="text-2xl font-bold text-white">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Upload Your Data</h3>
                        <p class="text-gray-500 leading-relaxed">Import your data via CSV, Google Sheets, or API. We support any structured dataset -- cities, products, keywords, you name it.</p>
                    </div>

                    {{-- Connector Arrow (hidden on mobile) --}}
                    {{-- Step 2 --}}
                    <div class="text-center">
                        <div class="relative inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl mb-6 shadow-lg shadow-purple-500/25">
                            <span class="text-2xl font-bold text-white">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Choose a Template</h3>
                        <p class="text-gray-500 leading-relaxed">Pick from our library of proven templates or build your own with the drag-and-drop editor. Map your data fields to template slots.</p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="text-center">
                        <div class="relative inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-600 to-rose-600 rounded-2xl mb-6 shadow-lg shadow-pink-500/25">
                            <span class="text-2xl font-bold text-white">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Generate Pages</h3>
                        <p class="text-gray-500 leading-relaxed">Hit generate and watch hundreds or thousands of unique, SEO-optimized pages come to life. Publish instantly or review first.</p>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="text-center mt-14">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-white rounded-xl btn-gradient shadow-lg shadow-indigo-500/25">
                        Start Building Now
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        {{-- ============================================= --}}
        {{-- PRICING SECTION --}}
        {{-- ============================================= --}}
        <section class="py-20 sm:py-28 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Section Header --}}
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3">Pricing</h2>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Simple, transparent pricing</p>
                    <p class="mt-4 text-lg text-gray-500">Start free. Upgrade when you need more power.</p>
                </div>

                {{-- Pricing Cards --}}
                <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    {{-- Free Plan --}}
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Free</h3>
                        <p class="text-gray-500 text-sm mt-1">Perfect to get started</p>
                        <div class="mt-6 mb-8">
                            <span class="text-4xl font-extrabold text-gray-900">$0</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Up to 50 pages
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                1 template
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Basic AI content
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Community support
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center py-2.5 px-4 rounded-lg font-semibold text-indigo-600 border-2 border-indigo-200 hover:bg-indigo-50 transition duration-300">
                            Get Started
                        </a>
                    </div>

                    {{-- Pro Plan (Popular) --}}
                    <div class="relative card-hover bg-white rounded-2xl p-8 shadow-xl border-2 border-indigo-500 scale-105">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                            <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
                                Most Popular
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Pro</h3>
                        <p class="text-gray-500 text-sm mt-1">For growing businesses</p>
                        <div class="mt-6 mb-8">
                            <span class="text-4xl font-extrabold text-gray-900">$49</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Up to 5,000 pages
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Unlimited templates
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Advanced AI (GPT-4)
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Priority support
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                SEO analytics dashboard
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center py-2.5 px-4 rounded-lg font-semibold text-white btn-gradient shadow-lg shadow-indigo-500/25">
                            Start Free Trial
                        </a>
                    </div>

                    {{-- Enterprise Plan --}}
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Enterprise</h3>
                        <p class="text-gray-500 text-sm mt-1">For large-scale operations</p>
                        <div class="mt-6 mb-8">
                            <span class="text-4xl font-extrabold text-gray-900">$199</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Unlimited pages
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Custom AI models
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                API access
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Dedicated account manager
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                White-label option
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center py-2.5 px-4 rounded-lg font-semibold text-indigo-600 border-2 border-indigo-200 hover:bg-indigo-50 transition duration-300">
                            Contact Sales
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================= --}}
        {{-- FINAL CTA SECTION --}}
        {{-- ============================================= --}}
        <section class="py-20 sm:py-28">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-4">Ready to scale your SEO?</h2>
                <p class="text-lg text-gray-500 mb-10 max-w-2xl mx-auto">Join thousands of marketers who are already generating hundreds of pages and driving organic traffic at scale.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-white rounded-xl btn-gradient shadow-lg shadow-indigo-500/25">
                        Get Started Free
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
                <p class="mt-4 text-sm text-gray-400">No credit card required. Free plan available.</p>
            </div>
        </section>

        {{-- ============================================= --}}
        {{-- FOOTER --}}
        {{-- ============================================= --}}
        <footer class="bg-gray-900 text-gray-400">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid md:grid-cols-4 gap-8">
                    {{-- Brand --}}
                    <div class="md:col-span-1">
                        <a href="/" class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-white font-bold">Programmatic SEO</span>
                        </a>
                        <p class="text-sm leading-relaxed">The all-in-one platform for generating SEO-optimized pages at scale.</p>
                    </div>

                    {{-- Product --}}
                    <div>
                        <h4 class="text-white font-semibold mb-4">Product</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition duration-200">Features</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Pricing</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Templates</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Integrations</a></li>
                        </ul>
                    </div>

                    {{-- Resources --}}
                    <div>
                        <h4 class="text-white font-semibold mb-4">Resources</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition duration-200">Documentation</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Blog</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Tutorials</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">API Reference</a></li>
                        </ul>
                    </div>

                    {{-- Company --}}
                    <div>
                        <h4 class="text-white font-semibold mb-4">Company</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition duration-200">About</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Contact</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-white transition duration-200">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between text-sm">
                    <p>&copy; {{ date('Y') }} Programmatic SEO. All rights reserved.</p>
                    <p class="mt-4 sm:mt-0">Built with Laravel v{{ Illuminate\Foundation\Application::VERSION }}</p>
                </div>
            </div>
        </footer>

    </body>
</html>
