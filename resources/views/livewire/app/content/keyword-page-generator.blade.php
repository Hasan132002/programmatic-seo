<div x-data="{
    wordCount: @entangle('wordCount'),
    showAdvanced: false,
    keywordsInput: @entangle('keywordsInput'),
}" class="space-y-6">

    <x-toast-notifications />

    {{-- Progress Overlay --}}
    @if($generating)
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">
                {{-- Animated Icon --}}
                <div class="relative w-20 h-20 mx-auto mb-6">
                    <div class="absolute inset-0 rounded-full bg-indigo-100 animate-ping opacity-25"></div>
                    <div class="relative flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600">
                        <svg class="w-10 h-10 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-1">Generating Pages</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Processing: <span class="font-medium text-indigo-600">{{ $currentKeyword }}</span>
                </p>

                {{-- Progress Bar --}}
                <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500"
                         style="width: {{ $total > 0 ? ($progress / $total) * 100 : 0 }}%"></div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">{{ $progress }} / {{ $total }} keywords</span>
                    <div class="flex items-center gap-3">
                        <span class="text-green-600 font-medium">{{ $successCount }} done</span>
                        @if($failCount > 0)
                            <span class="text-red-600 font-medium">{{ $failCount }} failed</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content Area --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Keywords Input Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">Enter Your Keywords</h3>
                            <p class="text-xs text-gray-500">One keyword or phrase per line. Each keyword generates a unique page.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Keywords Textarea --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="keywordsInput" class="block text-sm font-semibold text-gray-700">Keywords</label>
                            <span class="text-xs text-gray-400" x-text="(keywordsInput.split(/\\n/).filter(l => l.trim()).length) + ' keywords'"></span>
                        </div>
                        <textarea
                            wire:model.live.debounce.500ms="keywordsInput"
                            id="keywordsInput"
                            rows="10"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"
                            placeholder="best coffee shops new york&#10;top restaurants chicago&#10;best hotels san francisco&#10;things to do in miami&#10;best gyms los angeles&#10;&#10;Enter one keyword per line...&#10;Each keyword will generate a unique SEO page."
                        ></textarea>
                        @error('keywordsInput') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Quick Actions --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs text-gray-500 mr-1">Quick:</span>
                        <button type="button" @click="keywordsInput = keywordsInput.split(/\n/).map(l => l.trim()).filter(l => l).join('\n'); $wire.set('keywordsInput', keywordsInput)"
                            class="px-2.5 py-1 text-xs bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition">
                            Clean & Trim
                        </button>
                        <button type="button" @click="keywordsInput = [...new Set(keywordsInput.split(/\n/).map(l => l.trim()).filter(l => l))].join('\n'); $wire.set('keywordsInput', keywordsInput)"
                            class="px-2.5 py-1 text-xs bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition">
                            Remove Duplicates
                        </button>
                        <button type="button" @click="keywordsInput = keywordsInput.split(/\n/).map(l => l.trim()).filter(l => l).sort().join('\n'); $wire.set('keywordsInput', keywordsInput)"
                            class="px-2.5 py-1 text-xs bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition">
                            Sort A-Z
                        </button>
                        <button type="button" @click="keywordsInput = ''; $wire.set('keywordsInput', '')"
                            class="px-2.5 py-1 text-xs bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition">
                            Clear All
                        </button>
                    </div>

                    {{-- Slug Prefix --}}
                    <div>
                        <label for="slugPrefix" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            URL Prefix
                            <span class="text-xs font-normal text-gray-400 ml-1">(optional)</span>
                        </label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-2 text-sm text-gray-500 bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg">
                                {{ $site->url ?? 'yoursite.com' }}/
                            </span>
                            <input
                                wire:model="slugPrefix"
                                type="text"
                                id="slugPrefix"
                                class="flex-1 border-gray-300 rounded-r-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="e.g., best, guide, top"
                            >
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Pages will be created at: {{ $site->url ?? 'yoursite.com' }}/{{ $slugPrefix ? Str::slug($slugPrefix) . '/' : '' }}<span class="text-indigo-500">keyword-slug</span></p>
                    </div>
                </div>
            </div>

            {{-- Generation Settings --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Content Settings
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Tone --}}
                    <div>
                        <label for="tone" class="block text-sm font-semibold text-gray-700 mb-1.5">Writing Tone</label>
                        <select wire:model="tone" id="tone"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="professional">Professional</option>
                            <option value="casual">Casual</option>
                            <option value="academic">Academic</option>
                            <option value="friendly">Friendly</option>
                            <option value="persuasive">Persuasive</option>
                        </select>
                    </div>

                    {{-- Word Count --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Word Count: <span class="text-indigo-600 font-bold" x-text="wordCount.toLocaleString()"></span>
                        </label>
                        <input type="range" x-model="wordCount"
                            @change="$wire.set('wordCount', parseInt(wordCount))"
                            min="300" max="5000" step="100"
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>300</span>
                            <span>1K</span>
                            <span>2.5K</span>
                            <span>5K</span>
                        </div>
                    </div>
                </div>

                {{-- Custom Instructions --}}
                <div>
                    <button type="button" @click="showAdvanced = !showAdvanced"
                        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4 transition-transform duration-200" :class="showAdvanced ? 'rotate-90' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        Custom Instructions
                    </button>

                    <div x-show="showAdvanced" x-collapse x-cloak class="mt-3">
                        <textarea wire:model="customInstructions" rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Additional instructions for all generated pages...&#10;e.g., Include a FAQ section, mention local transit options, add price comparisons"></textarea>
                        @error('customInstructions') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Generate Button --}}
            <div class="flex items-center gap-4">
                <button
                    wire:click="generateAll"
                    wire:loading.attr="disabled"
                    :disabled="$wire.generating || keywordsInput.trim().length === 0"
                    class="relative inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-sm rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-200"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    <span x-text="'Generate ' + (keywordsInput.split(/\\n/).filter(l => l.trim()).length) + ' Pages'">Generate Pages</span>
                </button>

                <button wire:click="resetForm" type="button"
                    class="inline-flex items-center gap-1.5 px-4 py-3 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                    </svg>
                    Reset
                </button>
            </div>

            {{-- Results Table --}}
            @if(!empty($results))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-800">Generation Results</h3>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="inline-flex items-center gap-1 text-green-600">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                {{ $successCount }} success
                            </span>
                            @if($failCount > 0)
                                <span class="inline-flex items-center gap-1 text-red-600">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    {{ $failCount }} failed
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keyword</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($results as $i => $result)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-xs text-gray-400">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-mono text-gray-700">{{ $result['keyword'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate">{{ $result['title'] }}</td>
                                        <td class="px-4 py-3">
                                            @if($result['status'] === 'success')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                    Done
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium text-red-700 bg-red-100 rounded-full" title="{{ $result['error'] ?? '' }}">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($result['status'] === 'success' && isset($result['page_id']))
                                                <a href="{{ route('app.sites.pages.edit', [$site, $result['page_id']]) }}"
                                                   class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors" wire:navigate>
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                    </svg>
                                                    Edit
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- How It Works Card --}}
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-5 text-white">
                <h3 class="text-sm font-bold text-white/90 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    How It Works
                </h3>
                <ol class="space-y-2.5 text-sm text-white/80">
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">1</span>
                        Enter your target keywords (one per line)
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">2</span>
                        Configure tone, word count & settings
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">3</span>
                        AI generates unique content for each keyword
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">4</span>
                        Pages are created as drafts for review
                    </li>
                </ol>
                <p class="text-xs text-white/60 mt-3 pt-3 border-t border-white/20">
                    Estimated cost: ~{{ max(1, intval($wordCount / 750)) }} credit(s) per page
                </p>
            </div>

            {{-- Keyword Preview Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Preview (first 5)
                </h3>
                @if(count($parsedKeywords) > 0)
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach(array_slice($parsedKeywords, 0, 5) as $keyword)
                            <div class="p-2.5 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-700 truncate">{{ ucwords($keyword) }}</p>
                                <p class="text-[10px] text-gray-400 font-mono truncate">/{{ $slugPrefix ? Str::slug($slugPrefix) . '/' : '' }}{{ Str::slug($keyword) }}</p>
                            </div>
                        @endforeach
                        @if(count($parsedKeywords) > 5)
                            <p class="text-xs text-gray-400 text-center pt-1">...and {{ count($parsedKeywords) - 5 }} more</p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                        </svg>
                        <p class="text-xs text-gray-400">Enter keywords to see preview</p>
                    </div>
                @endif
            </div>

            {{-- Site Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Site Details</h3>
                <dl class="space-y-2.5">
                    <div>
                        <dt class="text-xs text-gray-500">Name</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $site->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Niche</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $site->niche_type?->label() ?? 'Custom' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Total Pages</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $site->pages()->count() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Recent Keyword Generations --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Recent Keyword Jobs
                </h3>
                @if($recentJobs->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-xs text-gray-400">No recent keyword generations</p>
                    </div>
                @else
                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        @foreach($recentJobs as $job)
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                @if($job->status === 'completed')
                                    <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                                @elseif($job->status === 'failed')
                                    <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0 animate-pulse"></span>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-700 truncate">{{ $job->input_data['keyword'] ?? 'Unknown' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $job->created_at->diffForHumans() }}</p>
                                </div>
                                @if($job->status === 'completed' && $job->page_id)
                                    <a href="{{ route('app.sites.pages.edit', [$site, $job->page_id]) }}"
                                       class="text-indigo-500 hover:text-indigo-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
