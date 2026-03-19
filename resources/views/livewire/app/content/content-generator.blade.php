<div x-data="{
    method: @entangle('generationMethod'),
    bulkMode: @entangle('bulkMode'),
    wordCount: @entangle('wordCount'),
    showAdvanced: false,
}" class="space-y-6">

    {{-- Flash Messages --}}
    @if (session()->has('error'))
        <div class="flex items-center p-4 rounded-lg bg-red-50 border border-red-200">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="flex items-center p-4 rounded-lg bg-blue-50 border border-blue-200">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <p class="ml-3 text-sm font-medium text-blue-800">{{ session('info') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content Area --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Generation Method Tabs --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="flex" aria-label="Generation method">
                        {{-- AI Tab --}}
                        <button
                            type="button"
                            @click="method = 'ai'; $wire.set('generationMethod', 'ai')"
                            :class="method === 'ai'
                                ? 'border-indigo-500 text-indigo-600 bg-indigo-50/50'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group relative flex-1 inline-flex items-center justify-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                            AI Generated
                        </button>

                        {{-- Template Tab --}}
                        <button
                            type="button"
                            @click="method = 'template'; $wire.set('generationMethod', 'template')"
                            :class="method === 'template'
                                ? 'border-indigo-500 text-indigo-600 bg-indigo-50/50'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group relative flex-1 inline-flex items-center justify-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Template
                        </button>

                        {{-- Hybrid Tab --}}
                        <button
                            type="button"
                            @click="method = 'hybrid'; $wire.set('generationMethod', 'hybrid')"
                            :class="method === 'hybrid'
                                ? 'border-indigo-500 text-indigo-600 bg-indigo-50/50'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group relative flex-1 inline-flex items-center justify-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                            Hybrid
                        </button>

                        {{-- Manual Tab --}}
                        <button
                            type="button"
                            @click="method = 'manual'; $wire.set('generationMethod', 'manual')"
                            :class="method === 'manual'
                                ? 'border-indigo-500 text-indigo-600 bg-indigo-50/50'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group relative flex-1 inline-flex items-center justify-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Manual
                        </button>
                    </nav>
                </div>

                {{-- Method Description --}}
                <div class="px-6 py-3 bg-gray-50/50 border-b border-gray-100">
                    <p class="text-xs text-gray-500" x-show="method === 'ai'">
                        AI will generate complete, unique content based on your prompt and settings.
                    </p>
                    <p class="text-xs text-gray-500" x-show="method === 'template'" x-cloak>
                        Content is generated by filling template variables with your data. No AI credits used.
                    </p>
                    <p class="text-xs text-gray-500" x-show="method === 'hybrid'" x-cloak>
                        Template structure with AI-enhanced sections for the best of both worlds.
                    </p>
                    <p class="text-xs text-gray-500" x-show="method === 'manual'" x-cloak>
                        Write your own content manually. Redirects to the page editor after creation.
                    </p>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Page Title --}}
                    <div x-show="!bulkMode">
                        <label for="pageTitle" class="block text-sm font-semibold text-gray-700 mb-1.5">Page Title</label>
                        <input
                            wire:model.live.debounce.400ms="pageTitle"
                            type="text"
                            id="pageTitle"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="e.g., Best Coffee Shops in Portland, Oregon"
                        >
                        @error('pageTitle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- URL Slug --}}
                    <div x-show="!bulkMode">
                        <label for="pageSlug" class="block text-sm font-semibold text-gray-700 mb-1.5">URL Slug</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-2 text-sm text-gray-500 bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg">
                                {{ $site->url }}/
                            </span>
                            <input
                                wire:model="pageSlug"
                                type="text"
                                id="pageSlug"
                                class="flex-1 border-gray-300 rounded-r-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="best-coffee-shops-portland-oregon"
                            >
                        </div>
                        @error('pageSlug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Template Selector (for template and hybrid methods) --}}
                    <div x-show="method === 'template' || method === 'hybrid'" x-cloak>
                        <label for="templateId" class="block text-sm font-semibold text-gray-700 mb-1.5">Page Template</label>
                        <select
                            wire:model="templateId"
                            id="templateId"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                            <option value="">-- Select a template --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">
                                    {{ $template->name }}
                                    @if($template->is_system) (System) @endif
                                    @if($template->niche_type) - {{ $template->niche_type->label() }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('templateId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Content Prompt --}}
                    <div x-show="method !== 'manual'" x-cloak>
                        <label for="prompt" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Content Prompt
                            <span class="font-normal text-gray-400 ml-1">*</span>
                        </label>
                        <textarea
                            wire:model="prompt"
                            id="prompt"
                            rows="5"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Describe what content you want generated...&#10;&#10;Example: Write a comprehensive guide about the best coffee shops in Portland, covering ambiance, specialty drinks, price ranges, and insider tips for each location."
                        ></textarea>
                        <div class="flex items-center justify-between mt-1">
                            @error('prompt') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-400 ml-auto">{{ strlen($prompt) }} characters</p>
                        </div>
                    </div>

                    {{-- Keywords --}}
                    <div x-show="method !== 'manual'" x-cloak>
                        <label for="keywords" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Target Keywords
                            <span class="text-xs font-normal text-gray-400 ml-1">(comma separated)</span>
                        </label>
                        <input
                            wire:model="keywords"
                            type="text"
                            id="keywords"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="e.g., best coffee shops portland, portland cafes, specialty coffee portland"
                        >
                        @error('keywords') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Advanced Settings Toggle --}}
                    <div x-show="method !== 'manual'" x-cloak>
                        <button
                            type="button"
                            @click="showAdvanced = !showAdvanced"
                            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition-colors"
                        >
                            <svg
                                class="w-4 h-4 transition-transform duration-200"
                                :class="showAdvanced ? 'rotate-90' : ''"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                            Advanced Settings
                        </button>

                        <div x-show="showAdvanced" x-collapse x-cloak class="mt-4 space-y-5 pl-2 border-l-2 border-indigo-100">
                            {{-- Tone Selection --}}
                            <div>
                                <label for="tone" class="block text-sm font-semibold text-gray-700 mb-1.5">Writing Tone</label>
                                <select
                                    wire:model="tone"
                                    id="tone"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
                                    <option value="professional">Professional</option>
                                    <option value="casual">Casual</option>
                                    <option value="academic">Academic</option>
                                    <option value="friendly">Friendly</option>
                                    <option value="persuasive">Persuasive</option>
                                </select>
                            </div>

                            {{-- Word Count Slider --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Target Word Count: <span class="text-indigo-600 font-bold" x-text="wordCount.toLocaleString()"></span>
                                </label>
                                <input
                                    type="range"
                                    x-model="wordCount"
                                    @change="$wire.set('wordCount', parseInt(wordCount))"
                                    min="300"
                                    max="5000"
                                    step="100"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                                >
                                <div class="flex justify-between text-xs text-gray-400 mt-1">
                                    <span>300</span>
                                    <span>1,000</span>
                                    <span>2,500</span>
                                    <span>5,000</span>
                                </div>
                            </div>

                            {{-- Custom Instructions --}}
                            <div>
                                <label for="customInstructions" class="block text-sm font-semibold text-gray-700 mb-1.5">Custom Instructions</label>
                                <textarea
                                    wire:model="customInstructions"
                                    id="customInstructions"
                                    rows="3"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    placeholder="Any additional instructions for the AI...&#10;e.g., Include a comparison table, focus on budget options, mention public transit access"
                                ></textarea>
                                @error('customInstructions') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bulk Mode Toggle --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Bulk Generation Mode</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Generate multiple pages at once from a data source</p>
                    </div>
                    <button
                        type="button"
                        @click="bulkMode = !bulkMode; $wire.set('bulkMode', bulkMode)"
                        :class="bulkMode ? 'bg-indigo-600' : 'bg-gray-200'"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        role="switch"
                        :aria-checked="bulkMode"
                    >
                        <span
                            :class="bulkMode ? 'translate-x-5' : 'translate-x-0'"
                            class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                        ></span>
                    </button>
                </div>

                <div x-show="bulkMode" x-collapse x-cloak class="mt-4 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <div>
                            <p class="text-sm text-indigo-800 font-medium">Bulk generation requires a data source</p>
                            <p class="text-xs text-indigo-600 mt-1">
                                Use the dedicated
                                <a href="{{ route('app.sites.content.bulk', $site) }}" class="underline font-medium hover:text-indigo-800" wire:navigate>Bulk Page Generator</a>
                                to map data source columns to template variables and generate pages in batch.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Generate Button --}}
            <div class="flex items-center gap-4" x-show="!bulkMode">
                <button
                    wire:click="generate"
                    wire:loading.attr="disabled"
                    :disabled="$wire.generating"
                    class="relative inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-200"
                >
                    {{-- Loading State --}}
                    <div wire:loading wire:target="generate" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Generating Content...</span>
                    </div>

                    {{-- Default State --}}
                    <div wire:loading.remove wire:target="generate" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        <span x-text="method === 'manual' ? 'Create Page' : 'Generate Content'"></span>
                    </div>

                    {{-- Pulse animation when generating --}}
                    <span
                        wire:loading
                        wire:target="generate"
                        class="absolute inset-0 rounded-xl animate-ping bg-indigo-400 opacity-20"
                    ></span>
                </button>

                <button
                    wire:click="resetForm"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-4 py-3 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                    </svg>
                    Reset
                </button>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- AI Credits Indicator --}}
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-5 text-white">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white/90">AI Credits</p>
                        <p class="text-2xl font-bold">
                            {{ number_format(auth()->user()->ai_credits ?? 100) }}
                        </p>
                    </div>
                </div>
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2" style="width: {{ min(100, ((auth()->user()->ai_credits ?? 100) / max(1, (auth()->user()->ai_credits_limit ?? 100))) * 100) }}%"></div>
                </div>
                <p class="text-xs text-white/70 mt-2">
                    Estimated cost: ~{{ max(1, intval($wordCount / 750)) }} credit(s) per page
                </p>
            </div>

            {{-- Site Info Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Site Details</h3>
                <dl class="space-y-2.5">
                    <div>
                        <dt class="text-xs text-gray-500">Name</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $site->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Niche</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $site->niche_type->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Total Pages</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $site->pages()->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">URL</dt>
                        <dd class="text-sm font-medium text-indigo-600 truncate">{{ $site->url }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Quick Tips Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                    </svg>
                    Tips for Better Content
                </h3>
                <ul class="space-y-2 text-xs text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="w-1 h-1 rounded-full bg-indigo-400 flex-shrink-0 mt-1.5"></span>
                        Be specific in your prompt - include target audience and unique angles
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1 h-1 rounded-full bg-indigo-400 flex-shrink-0 mt-1.5"></span>
                        Use 2-5 target keywords per page for optimal SEO
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1 h-1 rounded-full bg-indigo-400 flex-shrink-0 mt-1.5"></span>
                        Longer content (1,500+ words) tends to rank better for competitive keywords
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1 h-1 rounded-full bg-indigo-400 flex-shrink-0 mt-1.5"></span>
                        Use custom instructions to add unique data points or local knowledge
                    </li>
                </ul>
            </div>

            {{-- Recent Generation Jobs --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Recent Generations
                </h3>

                @if($recentJobs->isEmpty())
                    <div class="text-center py-6">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <p class="text-xs text-gray-400">No recent generations</p>
                    </div>
                @else
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @foreach($recentJobs as $job)
                            <div class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                                {{-- Status Indicator --}}
                                @if($job->status === 'completed')
                                    <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                                @elseif($job->status === 'pending')
                                    <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0 animate-pulse"></span>
                                @elseif($job->status === 'failed')
                                    <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-gray-400 flex-shrink-0"></span>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-700 truncate">
                                        {{ $job->input_data['title'] ?? 'Untitled' }}
                                    </p>
                                    <p class="text-[10px] text-gray-400">
                                        {{ $job->created_at->diffForHumans() }}
                                        @if($job->tokens_used)
                                            &middot; {{ number_format($job->tokens_used) }} tokens
                                        @endif
                                    </p>
                                </div>

                                @if($job->status === 'completed' && $job->page_id)
                                    <a
                                        href="{{ route('app.sites.pages.edit', [$site, $job->page_id]) }}"
                                        class="text-indigo-500 hover:text-indigo-700 transition-colors"
                                        title="View page"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    </a>
                                @elseif($job->status === 'failed')
                                    <span class="text-xs text-red-500" title="{{ $job->error_message }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
