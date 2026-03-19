<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Programmatic SEO') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">

            {{-- Mobile sidebar overlay --}}
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"
                 @click="sidebarOpen = false" style="display: none;"></div>

            {{-- Sidebar --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-200 ease-in-out lg:translate-x-0 flex flex-col">

                {{-- Sidebar header / Logo --}}
                <div class="flex items-center justify-between h-16 px-5 border-b border-gray-100">
                    <a href="{{ route('app.dashboard') }}" wire:navigate class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:shadow-indigo-500/40 group-hover:scale-105 transition-all duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="font-bold text-gray-900 text-sm tracking-tight">PSEO Platform</span>
                            <span class="block text-[10px] text-gray-400 -mt-0.5">Programmatic SEO</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    {{-- Main Navigation --}}
                    <p class="px-3 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Main</p>

                    {{-- Dashboard --}}
                    <a href="{{ route('app.dashboard') }}" wire:navigate
                       class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.dashboard') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.dashboard') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    {{-- My Sites --}}
                    <a href="{{ route('app.sites.index') }}" wire:navigate
                       class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.index') || request()->routeIs('app.sites.create') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.index') || request()->routeIs('app.sites.create') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        My Sites
                    </a>

                    {{-- Site-specific navigation (shows when viewing a specific site) --}}
                    @if(request()->route('site'))
                        @php $currentSite = request()->route('site'); @endphp
                        <div class="mt-4 mb-1">
                            <p class="px-3 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">
                                Current Site
                            </p>
                            {{-- Site name badge --}}
                            <a href="{{ route('app.sites.show', $currentSite) }}" wire:navigate
                               class="flex items-center gap-2.5 mx-1 mb-3 px-3 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl text-white shadow-md shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                                <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($currentSite->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold truncate">{{ $currentSite->name }}</p>
                                    <p class="text-[10px] text-indigo-200 truncate">{{ $currentSite->subdomain ? $currentSite->subdomain . '.' . parse_url(config('app.url'), PHP_URL_HOST) : $currentSite->domain }}</p>
                                </div>
                            </a>
                        </div>

                        {{-- Site Overview --}}
                        <a href="{{ route('app.sites.show', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.show') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.show') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                            Overview
                        </a>

                        {{-- Pages --}}
                        <a href="{{ route('app.sites.pages.index', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.pages.*') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.pages.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Pages
                        </a>

                        {{-- Data Sources --}}
                        <a href="{{ route('app.sites.data.index', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.data.*') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.data.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                            Data Sources
                        </a>

                        {{-- AI Content --}}
                        <a href="{{ route('app.sites.content.generate', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.content.*') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.content.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            AI Content
                        </a>

                        {{-- Visual Builder --}}
                        <a href="{{ route('app.sites.builder.create', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.builder.*') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.builder.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                            </svg>
                            Visual Builder
                        </a>

                        {{-- SEO Settings --}}
                        <a href="{{ route('app.sites.seo.settings', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.seo.*') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.seo.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            SEO Settings
                        </a>

                        {{-- Monetization --}}
                        <a href="{{ route('app.sites.monetization.ads', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.monetization.*') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.monetization.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Monetization
                        </a>

                        {{-- Analytics --}}
                        <a href="{{ route('app.sites.analytics', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.analytics') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.analytics') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Analytics
                        </a>

                        {{-- Site Settings --}}
                        <a href="{{ route('app.sites.edit', $currentSite) }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.sites.edit') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.sites.edit') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Site Settings
                        </a>
                    @endif

                    {{-- Account Section --}}
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <p class="px-3 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Account</p>

                        {{-- Billing --}}
                        <a href="{{ route('app.billing') }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('app.billing*') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('app.billing*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Billing & Plans
                        </a>

                        {{-- Profile --}}
                        <a href="{{ route('profile') }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('profile') ? 'bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 border border-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('profile') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>

                        {{-- Admin Panel --}}
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" wire:navigate
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group text-amber-700 hover:bg-amber-50">
                            <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Admin Panel
                        </a>
                        @endif
                    </div>
                </nav>

                {{-- Plan badge & credits --}}
                @if(auth()->user()->plan)
                <div class="mx-3 mb-3 p-3 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-indigo-700">{{ auth()->user()->plan->name }} Plan</span>
                        <a href="{{ route('app.billing') }}" wire:navigate class="text-[10px] text-indigo-500 hover:text-indigo-700 font-medium">Upgrade</a>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span class="text-xs font-semibold text-indigo-600">
                            {{ number_format(auth()->user()->plan->ai_credits_monthly - auth()->user()->ai_credits_used) }} AI credits left
                        </span>
                    </div>
                </div>
                @endif

                {{-- User info at bottom --}}
                <div class="flex items-center gap-3 px-4 py-3.5 border-t border-gray-100">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <livewire:layout.navigation />
                </div>
            </aside>

            {{-- Main content area --}}
            <div class="lg:pl-64 flex flex-col min-h-screen">
                {{-- Top bar (mobile hamburger only on small screens) --}}
                <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-gray-200/60">
                    @if (isset($header))
                        <div class="flex items-center gap-3 px-4 sm:px-6 lg:px-8 py-4">
                            {{-- Mobile hamburger --}}
                            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 transition-colors flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <div class="flex-1 min-w-0">{{ $header }}</div>
                        </div>
                    @else
                        <div class="flex items-center h-14 px-4 sm:px-6 lg:px-8 lg:hidden">
                            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                </header>

                <!-- Global Toast Notification System -->
                <div x-data="toastManager()" x-on:toast.window="add($event.detail)"
                     class="fixed top-4 right-4 z-[9999] space-y-3 pointer-events-none" style="max-width: 420px;">
                    <template x-for="toast in toasts" :key="toast.id">
                        <div x-show="toast.visible"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-x-8 scale-95"
                             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-x-0"
                             x-transition:leave-end="opacity-0 translate-x-8 scale-95"
                             class="pointer-events-auto relative flex items-start gap-3 p-4 rounded-xl shadow-2xl border backdrop-blur-sm"
                             :class="{
                                 'bg-green-50/95 border-green-200 text-green-800': toast.type === 'success',
                                 'bg-red-50/95 border-red-200 text-red-800': toast.type === 'error',
                                 'bg-yellow-50/95 border-yellow-200 text-yellow-800': toast.type === 'warning',
                                 'bg-blue-50/95 border-blue-200 text-blue-800': toast.type === 'info',
                             }">
                            <!-- Icon -->
                            <div class="shrink-0 mt-0.5">
                                <template x-if="toast.type === 'success'">
                                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </template>
                                <template x-if="toast.type === 'error'">
                                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                </template>
                                <template x-if="toast.type === 'warning'">
                                    <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                                </template>
                                <template x-if="toast.type === 'info'">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                                </template>
                            </div>
                            <!-- Message -->
                            <p class="text-sm font-medium flex-1" x-text="toast.message"></p>
                            <!-- Close -->
                            <button @click="remove(toast.id)" class="shrink-0 opacity-60 hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <!-- Progress bar -->
                            <div class="absolute bottom-0 left-0 h-1 rounded-b-xl transition-all duration-100"
                                 :class="{
                                     'bg-green-400': toast.type === 'success',
                                     'bg-red-400': toast.type === 'error',
                                     'bg-yellow-400': toast.type === 'warning',
                                     'bg-blue-400': toast.type === 'info',
                                 }"
                                 :style="'width: ' + toast.progress + '%'">
                            </div>
                        </div>
                    </template>
                </div>

                <script>
                    function toastManager() {
                        return {
                            toasts: [],
                            add(detail) {
                                const id = Date.now() + Math.random();
                                const duration = detail.duration || (detail.type === 'error' ? 8000 : 5000);
                                const toast = { id, type: detail.type || 'info', message: detail.message, visible: true, progress: 100 };
                                this.toasts.push(toast);

                                // Animate progress bar
                                const interval = setInterval(() => {
                                    toast.progress -= (100 / (duration / 50));
                                    if (toast.progress <= 0) {
                                        clearInterval(interval);
                                        this.remove(id);
                                    }
                                }, 50);
                            },
                            remove(id) {
                                const toast = this.toasts.find(t => t.id === id);
                                if (toast) toast.visible = false;
                                setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 300);
                            }
                        }
                    }
                </script>

                <!-- Session Flash to Toast Bridge -->
                @if (session('success'))
                    <div x-data x-init="$dispatch('toast', { type: 'success', message: '{{ addslashes(session('success')) }}' })" class="hidden"></div>
                @endif
                @if (session('error'))
                    <div x-data x-init="$dispatch('toast', { type: 'error', message: '{{ addslashes(session('error')) }}' })" class="hidden"></div>
                @endif
                @if (session('warning'))
                    <div x-data x-init="$dispatch('toast', { type: 'warning', message: '{{ addslashes(session('warning')) }}' })" class="hidden"></div>
                @endif
                @if (session('info'))
                    <div x-data x-init="$dispatch('toast', { type: 'info', message: '{{ addslashes(session('info')) }}' })" class="hidden"></div>
                @endif

                {{-- Page Content --}}
                <main class="flex-1 animate-fade-in py-4">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
