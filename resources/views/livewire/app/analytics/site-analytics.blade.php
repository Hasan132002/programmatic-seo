<div class="space-y-8">
    {{-- Period Selector --}}
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Traffic Overview</h3>
            <p class="text-sm text-gray-500 mt-0.5">Monitor your site's performance and visitor trends</p>
        </div>
        <div class="inline-flex items-center bg-gray-100 rounded-xl p-1 gap-0.5">
            @foreach(['24h' => '24h', '7d' => '7 days', '30d' => '30 days', '90d' => '90 days'] as $value => $label)
                <button wire:click="$set('period', '{{ $value }}')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                        {{ $period === $value
                            ? 'bg-white text-gray-900 shadow-sm ring-1 ring-gray-200'
                            : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Total Views --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-gray-100 shadow-sm p-6 group hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-full opacity-60 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Total Page Views</span>
                </div>
                <div class="flex items-end gap-2" x-data="{ count: 0, target: {{ $totalViews }} }" x-init="
                    let start = 0;
                    const duration = 800;
                    const startTime = performance.now();
                    function animate(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        count = Math.floor(eased * target);
                        if (progress < 1) requestAnimationFrame(animate);
                    }
                    requestAnimationFrame(animate);
                ">
                    <span class="text-4xl font-bold text-gray-900" x-text="count.toLocaleString()">0</span>
                </div>
            </div>
        </div>

        {{-- Unique Visitors --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-gray-100 shadow-sm p-6 group hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-full opacity-60 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-200">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Unique Visitors</span>
                </div>
                <div class="flex items-end gap-2" x-data="{ count: 0, target: {{ $uniqueVisitors }} }" x-init="
                    let start = 0;
                    const duration = 800;
                    const startTime = performance.now();
                    function animate(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        count = Math.floor(eased * target);
                        if (progress < 1) requestAnimationFrame(animate);
                    }
                    requestAnimationFrame(animate);
                ">
                    <span class="text-4xl font-bold text-gray-900" x-text="count.toLocaleString()">0</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Traffic Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-sm font-semibold text-gray-900">Daily Traffic</h4>
                <p class="text-xs text-gray-500 mt-0.5">Page views and unique visitors over time</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    <span class="text-gray-500">Views</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-gray-500">Visitors</span>
                </div>
            </div>
        </div>

        @if(count($chartData) > 0)
            @php
                $maxViews = max(array_column($chartData, 'views'));
                $maxViews = $maxViews > 0 ? $maxViews : 1;
            @endphp
            <div class="flex items-end gap-1.5 h-52" x-data="{ hoveredIndex: null }">
                @foreach($chartData as $i => $day)
                    <div class="flex-1 flex flex-col items-center group relative cursor-pointer"
                         @mouseenter="hoveredIndex = {{ $i }}" @mouseleave="hoveredIndex = null">
                        {{-- Tooltip --}}
                        <div x-show="hoveredIndex === {{ $i }}" x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak
                             class="absolute -top-20 bg-gray-900 text-white text-xs rounded-xl px-3 py-2.5 whitespace-nowrap z-10 shadow-xl">
                            <div class="font-semibold mb-1">{{ $day['date'] }}</div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                {{ number_format($day['views']) }} views
                            </div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                {{ number_format($day['visitors']) }} visitors
                            </div>
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                        </div>
                        {{-- Bars Container --}}
                        <div class="w-full flex justify-center gap-0.5 items-end h-44">
                            {{-- Views Bar --}}
                            <div class="w-1/2 bg-indigo-500 rounded-t-md transition-all duration-500 group-hover:bg-indigo-600 opacity-80 group-hover:opacity-100"
                                 style="height: {{ ($day['views'] / $maxViews) * 100 }}%; min-height: {{ $day['views'] > 0 ? '4px' : '0' }}">
                            </div>
                            {{-- Visitors Bar --}}
                            <div class="w-1/2 bg-emerald-500 rounded-t-md transition-all duration-500 group-hover:bg-emerald-600 opacity-80 group-hover:opacity-100"
                                 style="height: {{ ($day['visitors'] / $maxViews) * 100 }}%; min-height: {{ $day['visitors'] > 0 ? '4px' : '0' }}">
                            </div>
                        </div>
                        {{-- Date Label --}}
                        <span class="text-[10px] text-gray-400 mt-2 hidden md:block truncate max-w-full">{{ $day['date'] }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-52 text-gray-400">
                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <p class="text-sm font-medium">No traffic data yet</p>
                <p class="text-xs mt-1">Views will appear here once your site gets traffic</p>
            </div>
        @endif
    </div>

    {{-- Bottom Grid: Top Pages + Top Referrers --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top Pages --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <h4 class="text-sm font-semibold text-gray-900">Top Pages</h4>
                <p class="text-xs text-gray-500 mt-0.5">Most visited pages in the last 30 days</p>
            </div>
            @if($topPages->count() > 0)
                @php $maxPageViews = $topPages->max('views'); @endphp
                <div class="divide-y divide-gray-50">
                    @foreach($topPages as $index => $page)
                        <div class="px-6 py-3.5 hover:bg-gray-50/50 transition-colors group">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center text-xs font-bold rounded-lg
                                        {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-50 text-orange-600' : 'bg-gray-50 text-gray-400')) }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-700 truncate group-hover:text-indigo-600 transition-colors">
                                        {{ $page->title ?: $page->slug }}
                                    </span>
                                </div>
                                <span class="flex-shrink-0 text-sm font-semibold text-gray-900 ml-3">
                                    {{ number_format($page->views) }}
                                </span>
                            </div>
                            <div class="ml-9">
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-700"
                                         style="width: {{ ($page->views / $maxPageViews) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-10 text-center text-gray-400">
                    <p class="text-sm">No page data available yet</p>
                </div>
            @endif
        </div>

        {{-- Top Referrers --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <h4 class="text-sm font-semibold text-gray-900">Top Referrers</h4>
                <p class="text-xs text-gray-500 mt-0.5">Where your visitors are coming from</p>
            </div>
            @if($topReferrers->count() > 0)
                @php $maxRefCount = $topReferrers->max('count'); @endphp
                <div class="divide-y divide-gray-50">
                    @foreach($topReferrers as $index => $referrer)
                        @php
                            $parsedUrl = parse_url($referrer->referer);
                            $domain = $parsedUrl['host'] ?? $referrer->referer;
                        @endphp
                        <div class="px-6 py-3.5 hover:bg-gray-50/50 transition-colors group">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <img src="https://www.google.com/s2/favicons?domain={{ $domain }}&sz=32"
                                         alt="" class="w-5 h-5 rounded flex-shrink-0"
                                         onerror="this.style.display='none'">
                                    <span class="text-sm text-gray-700 truncate group-hover:text-indigo-600 transition-colors" title="{{ $referrer->referer }}">
                                        {{ $domain }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($referrer->count) }}</span>
                                    <span class="text-xs text-gray-400">
                                        ({{ $totalViews > 0 ? round(($referrer->count / $totalViews) * 100, 1) : 0 }}%)
                                    </span>
                                </div>
                            </div>
                            <div class="ml-8">
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-700"
                                         style="width: {{ ($referrer->count / $maxRefCount) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-10 text-center text-gray-400">
                    <p class="text-sm">No referrer data available yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
