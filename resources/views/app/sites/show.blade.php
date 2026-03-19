<x-app-layout>
    @php
        $nicheGradient = match($site->niche_type?->value) {
            'city' => ['from-blue-500 to-cyan-500', 'from-blue-50 to-cyan-50', 'text-blue-700', 'bg-blue-50', 'border-blue-200', 'blue'],
            'comparison' => ['from-purple-500 to-fuchsia-500', 'from-purple-50 to-fuchsia-50', 'text-purple-700', 'bg-purple-50', 'border-purple-200', 'purple'],
            'directory' => ['from-emerald-500 to-teal-500', 'from-emerald-50 to-teal-50', 'text-emerald-700', 'bg-emerald-50', 'border-emerald-200', 'emerald'],
            default => ['from-indigo-500 to-violet-500', 'from-indigo-50 to-violet-50', 'text-indigo-700', 'bg-indigo-50', 'border-indigo-200', 'indigo'],
        };
    @endphp

    {{-- Hero Section --}}
    <div class="relative overflow-hidden bg-white border-b border-gray-100">
        {{-- Background Gradient Accent --}}
        <div class="absolute inset-0 bg-gradient-to-br {{ $nicheGradient[1] }} opacity-40"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br {{ $nicheGradient[0] }} opacity-5 rounded-full -mr-48 -mt-48 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-4">
                    {{-- Site Icon --}}
                    <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br {{ $nicheGradient[0] }} flex items-center justify-center shadow-lg shadow-{{ $nicheGradient[5] }}-200/50">
                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($site->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $site->name }}</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $nicheGradient[3] }} {{ $nicheGradient[2] }} {{ $nicheGradient[4] }} border">
                                {{ $site->niche_type?->label() ?? 'Custom' }}
                            </span>
                            @if($site->is_published)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Live
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Draft
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 mt-1.5">
                            @php
                                $displayUrl = $site->domain ?: ($site->subdomain . '.' . config('pseo.platform_domain', 'localhost'));
                            @endphp
                            <a href="{{ $site->url }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-{{ $nicheGradient[5] }}-600 transition-colors group">
                                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-{{ $nicheGradient[5] }}-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                                {{ $displayUrl }}
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:flex-shrink-0">
                    <a href="{{ route('app.sites.pages.create', $site) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r {{ $nicheGradient[0] }} text-white text-sm font-semibold rounded-xl shadow-lg shadow-{{ $nicheGradient[5] }}-200/50 hover:shadow-xl hover:shadow-{{ $nicheGradient[5] }}-200/60 hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        New Page
                    </a>
                    <a href="{{ route('app.sites.edit', $site) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 hover:border-gray-300 hover:shadow-sm transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Quick Stats Row --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" x-data>
                {{-- Total Pages --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all duration-300"
                     style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.05s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pages</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $site->pages_count }}</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Published --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all duration-300"
                     style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Published</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">{{ $publishedCount }}</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Drafts --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all duration-300"
                     style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.15s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Drafts</p>
                            <p class="text-3xl font-bold text-amber-600 mt-1">{{ $draftCount }}</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Views --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all duration-300"
                     style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Views (7d)</p>
                            <p class="text-3xl font-bold text-indigo-600 mt-1">{{ number_format($viewsCount) }}</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-100 to-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Cards Grid --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Manage Your Site</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Pages --}}
                    <a href="{{ route('app.sites.pages.index', $site) }}"
                       class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                       style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.1s">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-200/50">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Pages</h4>
                                    <p class="text-xs text-gray-500 mt-1">Manage all your pages</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>

                    {{-- Data Sources --}}
                    <a href="{{ route('app.sites.data.index', $site) }}"
                       class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                       style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.15s">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-200/50">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-violet-600 transition-colors">Data Sources</h4>
                                    <p class="text-xs text-gray-500 mt-1">Import and manage data</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-violet-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>

                    {{-- AI Content --}}
                    <a href="{{ route('app.sites.content.generate', $site) }}"
                       class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                       style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.2s">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-200/50">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">AI Content</h4>
                                    <p class="text-xs text-gray-500 mt-1">Generate content with AI</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-amber-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>

                    {{-- SEO Settings --}}
                    <a href="{{ route('app.sites.seo.settings', $site) }}"
                       class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                       style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.25s">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-200/50">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">SEO Settings</h4>
                                    <p class="text-xs text-gray-500 mt-1">Optimize for search engines</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>

                    {{-- Page Builder --}}
                    <a href="{{ route('app.sites.builder.create', $site) }}"
                       class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                       style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.3s">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg shadow-indigo-200/50">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Page Builder</h4>
                                    <p class="text-xs text-gray-500 mt-1">Design pages visually</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>

                    {{-- Monetization --}}
                    <a href="{{ route('app.sites.monetization.ads', $site) }}"
                       class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                       style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.35s">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shadow-lg shadow-rose-200/50">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-rose-600 transition-colors">Monetization</h4>
                                    <p class="text-xs text-gray-500 mt-1">Manage ads & affiliates</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-rose-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Bottom Grid: Recent Pages + Analytics Preview --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent Pages --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                     style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.3s">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Recent Pages</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Your latest pages at a glance</p>
                        </div>
                        <a href="{{ route('app.sites.pages.index', $site) }}"
                           class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                            View all
                        </a>
                    </div>
                    @if($recentPages->count() > 0)
                        <div class="divide-y divide-gray-50">
                            @foreach($recentPages as $page)
                                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/50 transition-colors group">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        @php
                                            $statusColor = match($page->status?->value ?? $page->status) {
                                                'published' => 'bg-green-500',
                                                'draft' => 'bg-gray-400',
                                                'generating' => 'bg-yellow-500',
                                                'failed' => 'bg-red-500',
                                                default => 'bg-gray-400',
                                            };
                                        @endphp
                                        <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $statusColor }}"></span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-700 truncate group-hover:text-indigo-600 transition-colors">
                                                {{ $page->title ?: 'Untitled' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">/{{ $page->slug }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 flex-shrink-0 ml-4">
                                        <span class="text-xs text-gray-400">{{ $page->updated_at?->diffForHumans() }}</span>
                                        @if(($page->status?->value ?? $page->status) === 'published')
                                            <a href="{{ $site->url }}/{{ $page->slug }}"
                                               target="_blank"
                                               class="opacity-0 group-hover:opacity-100 inline-flex items-center gap-1 text-xs font-medium text-emerald-600 hover:text-emerald-700 transition-all"
                                               title="View published page">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                                View
                                            </a>
                                        @endif
                                        <a href="{{ route('app.sites.pages.edit', [$site, $page]) }}"
                                           class="opacity-0 group-hover:opacity-100 inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-all">
                                            Edit
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <p class="text-sm text-gray-500">No pages yet</p>
                            <a href="{{ route('app.sites.pages.create', $site) }}"
                               class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 mt-2 transition-colors">
                                Create your first page
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Analytics Preview --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                     style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.35s">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Traffic (7 days)</h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ number_format($viewsCount) }} total views</p>
                        </div>
                        <a href="{{ route('app.sites.analytics', $site) }}"
                           class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                            Details
                        </a>
                    </div>
                    <div class="px-6 py-5">
                        @if(count($chartData) > 0)
                            @php $maxViews = max(array_column($chartData, 'views')); $maxViews = $maxViews > 0 ? $maxViews : 1; @endphp
                            <div class="flex items-end gap-1 h-28" x-data="{ hovered: null }">
                                @foreach($chartData as $i => $day)
                                    <div class="flex-1 flex flex-col items-center relative group"
                                         @mouseenter="hovered = {{ $i }}" @mouseleave="hovered = null">
                                        {{-- Tooltip --}}
                                        <div x-show="hovered === {{ $i }}" x-transition x-cloak
                                             class="absolute -top-10 bg-gray-900 text-white text-[10px] rounded-lg px-2 py-1 whitespace-nowrap z-10">
                                            {{ $day['date'] }}: {{ $day['views'] }}
                                            <div class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-gray-900 rotate-45"></div>
                                        </div>
                                        <div class="w-full bg-gradient-to-t from-indigo-500 to-indigo-400 rounded-t-sm transition-all duration-300 group-hover:from-indigo-600 group-hover:to-indigo-500"
                                             style="height: {{ ($day['views'] / $maxViews) * 100 }}%; min-height: {{ $day['views'] > 0 ? '4px' : '2px' }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-[10px] text-gray-400">{{ $chartData[0]['date'] ?? '' }}</span>
                                <span class="text-[10px] text-gray-400">{{ end($chartData)['date'] ?? '' }}</span>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-28 text-gray-400">
                                <svg class="w-8 h-8 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                                <p class="text-xs">No data yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-gradient-to-r {{ $nicheGradient[1] }} rounded-2xl border {{ $nicheGradient[4] }} p-6"
                 style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.4s">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Common tasks for your site</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('app.sites.pages.create', $site) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:border-gray-300 hover:shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Create Page
                        </a>
                        <a href="{{ route('app.sites.data.import', $site) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:border-gray-300 hover:shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Import CSV
                        </a>
                        <a href="{{ route('app.sites.content.generate', $site) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:border-gray-300 hover:shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                            </svg>
                            Generate Content
                        </a>
                        <a href="{{ route('app.sites.content.keywords', $site) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:border-gray-300 hover:shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Keyword Generator
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Animations CSS --}}
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>
