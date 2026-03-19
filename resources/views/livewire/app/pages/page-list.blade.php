<div>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
        }
    </style>

    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    <div class="animate-fade-in-up bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Toolbar: Search + Filters --}}
        <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Search --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           placeholder="Search pages by title..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200">
                </div>

                {{-- Status Filter --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors w-full sm:w-auto justify-between sm:justify-center">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                        <span>
                            @if($statusFilter === '') All Status
                            @elseif($statusFilter === 'draft') Draft
                            @elseif($statusFilter === 'published') Published
                            @elseif($statusFilter === 'generating') Generating
                            @elseif($statusFilter === 'failed') Failed
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-20">
                        <button wire:click="$set('statusFilter', '')" @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors {{ $statusFilter === '' ? 'text-indigo-600 font-medium bg-indigo-50' : 'text-gray-700' }}">
                            All Status
                        </button>
                        <button wire:click="$set('statusFilter', 'published')" @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 {{ $statusFilter === 'published' ? 'text-indigo-600 font-medium bg-indigo-50' : 'text-gray-700' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Published
                        </button>
                        <button wire:click="$set('statusFilter', 'draft')" @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 {{ $statusFilter === 'draft' ? 'text-indigo-600 font-medium bg-indigo-50' : 'text-gray-700' }}">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span> Draft
                        </button>
                        <button wire:click="$set('statusFilter', 'generating')" @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 {{ $statusFilter === 'generating' ? 'text-indigo-600 font-medium bg-indigo-50' : 'text-gray-700' }}">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Generating
                        </button>
                        <button wire:click="$set('statusFilter', 'failed')" @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 {{ $statusFilter === 'failed' ? 'text-indigo-600 font-medium bg-indigo-50' : 'text-gray-700' }}">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Failed
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if($pages->isEmpty())
            {{-- Empty State --}}
            <div class="p-16 text-center">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">
                    @if($search || $statusFilter)
                        No pages match your filters
                    @else
                        No pages yet
                    @endif
                </h3>
                <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">
                    @if($search || $statusFilter)
                        Try adjusting your search or filter criteria to find what you're looking for.
                    @else
                        Create your first page to start building content for this site. You can write manually or use AI to generate content.
                    @endif
                </p>
                @if(!$search && !$statusFilter)
                    <a href="{{ route('app.sites.pages.create', $site) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:from-indigo-700 hover:to-purple-700 transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create First Page
                    </a>
                @else
                    <button wire:click="$set('search', '')" wire:click.self="$set('statusFilter', '')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        Clear Filters
                    </button>
                @endif
            </div>
        @else
            {{-- Pages Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Slug</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Method</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Updated</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pages as $page)
                            <tr class="hover:bg-gray-50/50 transition-colors duration-150 group"
                                x-data="{ showDeleteConfirm: false }">
                                {{-- Title --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('app.sites.pages.edit', [$site, $page]) }}"
                                       class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors text-sm block truncate max-w-xs">
                                        {{ Str::limit($page->title, 50) }}
                                    </a>
                                </td>

                                {{-- Slug --}}
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <code class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-md font-mono">/{{ Str::limit($page->slug, 30) }}</code>
                                </td>

                                {{-- Generation Method Badge --}}
                                <td class="px-6 py-4 hidden lg:table-cell">
                                    @php
                                        $methodColors = [
                                            'ai' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'template' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'hybrid' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'manual' => 'bg-gray-50 text-gray-600 border-gray-200',
                                        ];
                                        $methodColor = $methodColors[$page->generation_method->value] ?? $methodColors['manual'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-md border {{ $methodColor }}">
                                        @if($page->generation_method->value === 'ai')
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z" /></svg>
                                        @endif
                                        {{ $page->generation_method->label() }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyles = [
                                            'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'draft' => 'bg-gray-50 text-gray-600 border-gray-200',
                                            'generating' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                            'failed' => 'bg-red-50 text-red-700 border-red-200',
                                        ];
                                        $statusDots = [
                                            'published' => 'bg-emerald-500',
                                            'draft' => 'bg-gray-400',
                                            'generating' => 'bg-yellow-500 animate-pulse',
                                            'failed' => 'bg-red-500',
                                        ];
                                        $sStyle = $statusStyles[$page->status->value] ?? $statusStyles['draft'];
                                        $sDot = $statusDots[$page->status->value] ?? $statusDots['draft'];
                                    @endphp
                                    <button wire:click="togglePublish({{ $page->id }})"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full border cursor-pointer transition-colors hover:opacity-80 {{ $sStyle }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $sDot }}"></span>
                                        {{ $page->status->label() }}
                                    </button>
                                </td>

                                {{-- Updated --}}
                                <td class="px-6 py-4 hidden lg:table-cell">
                                    <span class="text-xs text-gray-400">{{ $page->updated_at->diffForHumans() }}</span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @if($page->status->value === 'published')
                                            <a href="{{ $site->url }}/{{ $page->slug }}"
                                               target="_blank"
                                               class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-150"
                                               title="View published page">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('app.sites.pages.edit', [$site, $page]) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-150"
                                           title="Edit page">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </a>
                                        <button @click="showDeleteConfirm = true"
                                                class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-150"
                                                title="Delete page">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>

                                    {{-- Inline Delete Confirmation --}}
                                    <div x-show="showDeleteConfirm" x-cloak
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                         @keydown.escape.window="showDeleteConfirm = false">
                                        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showDeleteConfirm = false"></div>
                                        <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 z-10">
                                            <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-xl mb-4 mx-auto">
                                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Delete Page</h3>
                                            <p class="text-sm text-gray-500 text-center mb-6">Are you sure you want to delete "<span class="font-semibold text-gray-700">{{ Str::limit($page->title, 40) }}</span>"? This action cannot be undone.</p>
                                            <div class="flex gap-3">
                                                <button @click="showDeleteConfirm = false"
                                                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                                                    Cancel
                                                </button>
                                                <button wire:click="delete({{ $page->id }})"
                                                        @click="showDeleteConfirm = false"
                                                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors shadow-sm">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pages->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $pages->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
