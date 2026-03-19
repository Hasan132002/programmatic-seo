<x-app-layout>
    {{-- Dashboard Animations & Styles --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes countUp {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out forwards;
            opacity: 0;
        }
        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out forwards;
            opacity: 0;
        }
        .animate-count-up {
            animation: countUp 0.4s ease-out forwards;
            opacity: 0;
        }
        .animate-pulse-soft {
            animation: pulse-soft 2s ease-in-out infinite;
        }
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }
        .site-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .site-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.12);
        }
        .progress-bar-fill {
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .gradient-animated {
            background-size: 200% 200%;
            animation: gradient-shift 6s ease infinite;
        }
        .quick-action-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.1);
        }
        .quick-action-btn:active {
            transform: translateY(0);
        }
        .badge-niche {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));
            color: #6366f1;
        }
    </style>

    <div class="py-6 sm:py-8 lg:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div class="animate-fade-in-up mb-8">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700 gradient-animated p-6 sm:p-8 lg:p-10">
                    {{-- Decorative elements --}}
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-64 h-64 bg-purple-400/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-indigo-300/10 rounded-full blur-2xl"></div>

                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white">
                                Welcome back, {{ auth()->user()->name }}!
                            </h1>
                            <p class="mt-2 text-indigo-100 text-sm sm:text-base max-w-xl">
                                @if($totalPages === 0)
                                    Ready to start building? Create your first site and let AI generate SEO-optimized content for you.
                                @elseif($publishedPages > 0)
                                    Your content is live and working. You have {{ $publishedPages }} published {{ Str::plural('page', $publishedPages) }} driving organic traffic.
                                @else
                                    You're making great progress! Publish your pages to start driving organic traffic to your sites.
                                @endif
                            </p>
                            <div class="mt-3 flex items-center gap-2 text-indigo-200 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>Plan: <span class="font-semibold text-white">{{ $currentPlan['name'] }}</span></span>
                                @if($currentPlan['expires_at'])
                                    <span class="mx-1">&middot;</span>
                                    <span>Renews {{ $currentPlan['expires_at']->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 sm:flex-nowrap sm:gap-3">
                            <a href="{{ route('app.sites.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-700 font-semibold text-sm rounded-xl hover:bg-indigo-50 transition-all duration-200 shadow-lg shadow-indigo-900/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                New Site
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                {{-- Total Sites --}}
                <div class="animate-fade-in-up stagger-1 stat-card bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100"
                     x-data="{ count: 0, target: {{ $sites->count() }} }"
                     x-intersect.once="let interval = setInterval(() => { if(count < target) { count++; } else { clearInterval(interval); } }, Math.max(30, 1000/target))">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/25">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        @if($sites->count() > 0)
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Active</span>
                        @endif
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900" x-text="count">0</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Total Sites</div>
                </div>

                {{-- Total Pages --}}
                <div class="animate-fade-in-up stagger-2 stat-card bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100"
                     x-data="{ count: 0, target: {{ $totalPages }} }"
                     x-intersect.once="let interval = setInterval(() => { if(count < target) { count++; } else { clearInterval(interval); } }, Math.max(10, 1000/Math.max(target,1)))">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/25">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        @if($generatingPages > 0)
                            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse-soft"></span>
                                {{ $generatingPages }} generating
                            </span>
                        @endif
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900" x-text="count">0</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Total Pages</div>
                </div>

                {{-- Published Pages --}}
                <div class="animate-fade-in-up stagger-3 stat-card bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100"
                     x-data="{ count: 0, target: {{ $publishedPages }} }"
                     x-intersect.once="let interval = setInterval(() => { if(count < target) { count++; } else { clearInterval(interval); } }, Math.max(10, 1000/Math.max(target,1)))">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/25">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        @if($totalPages > 0)
                            <span class="text-xs font-medium text-gray-500">{{ $totalPages > 0 ? round(($publishedPages / $totalPages) * 100) : 0 }}%</span>
                        @endif
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900" x-text="count">0</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Published</div>
                </div>

                {{-- Page Views This Month --}}
                <div class="animate-fade-in-up stagger-4 stat-card bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100"
                     x-data="{ count: 0, target: {{ $viewsThisMonth }} }"
                     x-intersect.once="let steps = Math.min(target, 60); let increment = Math.ceil(target / Math.max(steps,1)); let interval = setInterval(() => { if(count + increment < target) { count += increment; } else { count = target; clearInterval(interval); } }, 20)">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/25">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        @if($viewsToday > 0)
                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse-soft"></span>
                                {{ $viewsToday }} today
                            </span>
                        @endif
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900" x-text="count.toLocaleString()">0</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Views This Month</div>
                </div>
            </div>

            {{-- Quick Actions & Plan Usage Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 sm:gap-6 mb-8">

                {{-- Quick Actions --}}
                <div class="lg:col-span-2 animate-fade-in-up stagger-3">
                    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100 h-full">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('app.sites.create') }}" class="quick-action-btn group flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/50">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md shadow-indigo-500/20 group-hover:shadow-lg group-hover:shadow-indigo-500/30 transition-shadow">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </div>
                                <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-indigo-700 transition-colors">Create Site</span>
                            </a>

                            @if($sites->count() > 0)
                                <a href="{{ route('app.sites.pages.create', $sites->first()) }}" class="quick-action-btn group flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100 hover:border-purple-200 hover:bg-purple-50/50">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-md shadow-purple-500/20 group-hover:shadow-lg group-hover:shadow-purple-500/30 transition-shadow">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-purple-700 transition-colors">Create Page</span>
                                </a>
                            @else
                                <div class="quick-action-btn flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100 opacity-40 cursor-not-allowed" title="Create a site first">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-400">Create Page</span>
                                </div>
                            @endif

                            <a href="{{ route('app.sites.index') }}" class="quick-action-btn group flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50/50">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-500/20 group-hover:shadow-lg group-hover:shadow-emerald-500/30 transition-shadow">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                </div>
                                <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-emerald-700 transition-colors">All Sites</span>
                            </a>

                            @if($sites->count() > 0)
                                <a href="{{ route('app.sites.show', $sites->first()) }}" class="quick-action-btn group flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100 hover:border-amber-200 hover:bg-amber-50/50">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-md shadow-amber-500/20 group-hover:shadow-lg group-hover:shadow-amber-500/30 transition-shadow">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-amber-700 transition-colors">Manage Site</span>
                                </a>
                            @else
                                <div class="quick-action-btn flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100 opacity-40 cursor-not-allowed" title="Create a site first">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-400">Manage Site</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Plan Usage --}}
                <div class="lg:col-span-3 animate-fade-in-up stagger-4">
                    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100 h-full">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Plan Usage</h3>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full
                                @if($currentPlan['slug'] === 'pro') bg-indigo-100 text-indigo-700
                                @elseif($currentPlan['slug'] === 'enterprise') bg-purple-100 text-purple-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"/></svg>
                                {{ $currentPlan['name'] }}
                            </span>
                        </div>

                        <div class="space-y-5"
                             x-data="{
                                sitesWidth: 0,
                                pagesWidth: 0,
                                creditsWidth: 0,
                                sitesTarget: {{ $sitesUnlimited ? 30 : ($sitesLimit > 0 ? min(round(($sites->count() / $sitesLimit) * 100), 100) : 0) }},
                                pagesTarget: {{ $pagesPerSiteUnlimited ? 20 : ($pagesPerSiteLimit > 0 ? min(round(($totalPages / ($pagesPerSiteLimit * max($sites->count(), 1))) * 100), 100) : 0) }},
                                creditsTarget: {{ $aiCreditsUnlimited ? 15 : ($aiCreditsLimit > 0 ? min(round(($aiCreditsUsed / $aiCreditsLimit) * 100), 100) : 0) }}
                             }"
                             x-intersect.once="setTimeout(() => { sitesWidth = sitesTarget; pagesWidth = pagesTarget; creditsWidth = creditsTarget; }, 300)">

                            {{-- Sites Usage --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                        <span class="text-sm font-medium text-gray-700">Sites</span>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        <span class="font-semibold text-gray-900">{{ $sites->count() }}</span>
                                        / {{ $sitesUnlimited ? 'Unlimited' : $sitesLimit }}
                                    </span>
                                </div>
                                <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-blue-400 progress-bar-fill" :style="'width: ' + sitesWidth + '%'"></div>
                                </div>
                            </div>

                            {{-- Pages Usage --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="text-sm font-medium text-gray-700">Pages</span>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        <span class="font-semibold text-gray-900">{{ $totalPages }}</span>
                                        / {{ $pagesPerSiteUnlimited ? 'Unlimited' : ($pagesPerSiteLimit * max($sites->count(), 1)) }}
                                        @if(!$pagesPerSiteUnlimited && $sites->count() > 0)
                                            <span class="text-xs text-gray-400">({{ $pagesPerSiteLimit }}/site)</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-purple-400 progress-bar-fill" :style="'width: ' + pagesWidth + '%'"></div>
                                </div>
                            </div>

                            {{-- AI Credits Usage --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        <span class="text-sm font-medium text-gray-700">AI Credits</span>
                                        <span class="text-xs text-gray-400">(monthly)</span>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        <span class="font-semibold text-gray-900">{{ number_format($aiCreditsUsed) }}</span>
                                        / {{ $aiCreditsUnlimited ? 'Unlimited' : number_format($aiCreditsLimit) }}
                                    </span>
                                </div>
                                <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-orange-400 progress-bar-fill"
                                         :style="'width: ' + creditsWidth + '%'"
                                         :class="creditsTarget > 90 ? 'from-red-500 to-red-400' : (creditsTarget > 70 ? 'from-amber-500 to-orange-400' : 'from-amber-500 to-orange-400')"></div>
                                </div>
                                @if(!$aiCreditsUnlimited && $aiCreditsLimit > 0 && ($aiCreditsUsed / $aiCreditsLimit) > 0.9)
                                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        Running low on AI credits
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Pages & Traffic Overview --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">

                {{-- Recent Pages --}}
                <div class="lg:col-span-2 animate-fade-in-up stagger-5">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 sm:px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Recent Pages</h3>
                            @if($totalPages > 5)
                                <span class="text-xs text-gray-400">Showing latest 5</span>
                            @endif
                        </div>

                        @if($recentPages->isEmpty())
                            <div class="p-8 sm:p-12 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">No pages yet</h4>
                                <p class="text-xs text-gray-500 mb-4">Create your first page to see it here.</p>
                                @if($sites->count() > 0)
                                    <a href="{{ route('app.sites.pages.create', $sites->first()) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Create a page
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="divide-y divide-gray-50">
                                @foreach($recentPages as $page)
                                    <div class="px-5 sm:px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/50 transition-colors group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            {{-- Status dot --}}
                                            @php
                                                $statusColors = [
                                                    'published' => 'bg-emerald-500',
                                                    'draft' => 'bg-gray-400',
                                                    'generating' => 'bg-amber-500 animate-pulse-soft',
                                                    'failed' => 'bg-red-500',
                                                ];
                                                $dotClass = $statusColors[$page->status->value] ?? 'bg-gray-400';
                                            @endphp
                                            <span class="w-2 h-2 rounded-full {{ $dotClass }} shrink-0"></span>

                                            <div class="min-w-0">
                                                <a href="{{ route('app.sites.pages.edit', [$page->site, $page]) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors truncate block">
                                                    {{ Str::limit($page->title ?? 'Untitled Page', 45) }}
                                                </a>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span class="text-xs text-gray-400">{{ $page->site->name }}</span>
                                                    <span class="text-xs text-gray-300">&middot;</span>
                                                    <span class="text-xs text-gray-400">{{ $page->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0 ml-3">
                                            @php
                                                $badgeClasses = [
                                                    'published' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                    'draft' => 'bg-gray-50 text-gray-600 border-gray-100',
                                                    'generating' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                    'failed' => 'bg-red-50 text-red-700 border-red-100',
                                                ];
                                                $badgeClass = $badgeClasses[$page->status->value] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                            @endphp
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full border {{ $badgeClass }}">
                                                {{ $page->status->label() }}
                                            </span>
                                            <a href="{{ route('app.sites.pages.edit', [$page->site, $page]) }}" class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-indigo-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Traffic Overview --}}
                <div class="animate-fade-in-up stagger-6">
                    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100 h-full">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-5">Traffic Overview</h3>

                        <div class="space-y-4">
                            {{-- Today --}}
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-blue-50 to-transparent">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="text-sm text-gray-600">Today</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($viewsToday) }}</span>
                            </div>

                            {{-- This Week --}}
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-purple-50 to-transparent">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-sm text-gray-600">This Week</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($viewsThisWeek) }}</span>
                            </div>

                            {{-- This Month --}}
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-transparent">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <span class="text-sm text-gray-600">This Month</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($viewsThisMonth) }}</span>
                            </div>

                            {{-- Page Status Breakdown --}}
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Page Status</h4>
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span class="text-xs text-gray-600">Published</span>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-900">{{ $publishedPages }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                            <span class="text-xs text-gray-600">Draft</span>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-900">{{ $draftPages }}</span>
                                    </div>
                                    @if($generatingPages > 0)
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse-soft"></span>
                                                <span class="text-xs text-gray-600">Generating</span>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-900">{{ $generatingPages }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sites Grid --}}
            <div class="animate-fade-in-up stagger-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Your Sites</h3>
                    <a href="{{ route('app.sites.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1">
                        View All
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                @if($sites->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mx-auto mb-5">
                            <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Create your first site</h4>
                        <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Start building your programmatic SEO empire. Create a site, choose a niche, and let AI generate hundreds of optimized pages.</p>
                        <a href="{{ route('app.sites.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-sm rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-500/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Create Your First Site
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        @foreach($sites as $index => $site)
                            <div class="site-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group" style="animation-delay: {{ ($index * 0.1) + 0.2 }}s;">
                                {{-- Card Header with gradient accent --}}
                                <div class="h-1.5 bg-gradient-to-r
                                    @switch($site->niche_type->value)
                                        @case('city') from-blue-500 to-cyan-400 @break
                                        @case('comparison') from-purple-500 to-pink-400 @break
                                        @case('directory') from-emerald-500 to-teal-400 @break
                                        @default from-indigo-500 to-violet-400
                                    @endswitch
                                "></div>

                                <div class="p-5">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="min-w-0">
                                            <h4 class="text-base font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                                {{ $site->name }}
                                            </h4>
                                            @if($site->domain || $site->subdomain)
                                                <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $site->domain ?? $site->subdomain }}</p>
                                            @endif
                                        </div>
                                        <span class="shrink-0 ml-2 inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full {{ $site->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-50 text-gray-500' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $site->is_published ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                            {{ $site->is_published ? 'Live' : 'Draft' }}
                                        </span>
                                    </div>

                                    {{-- Niche Badge --}}
                                    <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-lg badge-niche mb-4">
                                        @switch($site->niche_type->value)
                                            @case('city')
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                @break
                                            @case('comparison')
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                                @break
                                            @case('directory')
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                                @break
                                            @default
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                        @endswitch
                                        {{ $site->niche_type->label() }}
                                    </span>

                                    {{-- Stats Row --}}
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span class="font-medium text-gray-700">{{ $site->pages_count }}</span> pages
                                        </div>
                                        <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span class="font-medium text-gray-700">{{ $site->published_pages_count }}</span> live
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('app.sites.show', $site) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </a>
                                        <a href="{{ route('app.sites.edit', $site) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        <a href="{{ route('app.sites.pages.index', $site) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                            Pages
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Add Site Card --}}
                        @if(!$sitesUnlimited && $sites->count() < $sitesLimit || $sitesUnlimited)
                            <a href="{{ route('app.sites.create') }}" class="site-card bg-white rounded-2xl shadow-sm border-2 border-dashed border-gray-200 hover:border-indigo-300 overflow-hidden group flex flex-col items-center justify-center p-8 min-h-[200px] transition-all duration-200">
                                <div class="w-12 h-12 rounded-xl bg-gray-50 group-hover:bg-indigo-50 flex items-center justify-center mb-3 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-500 group-hover:text-indigo-600 transition-colors">Add New Site</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
