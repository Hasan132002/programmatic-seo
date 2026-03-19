<div x-data="{
    step: @entangle('currentStep'),
}" class="space-y-6">

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="flex items-center p-4 rounded-lg bg-green-50 border border-green-200">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="flex items-center p-4 rounded-lg bg-red-50 border border-red-200">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="flex items-center p-4 rounded-lg bg-yellow-50 border border-yellow-200">
            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
            </svg>
            <p class="ml-3 text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
        </div>
    @endif

    {{-- Step Wizard Navigation --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <nav aria-label="Progress">
            <ol class="flex items-center">
                @foreach([
                    ['num' => 1, 'label' => 'Select Data Source'],
                    ['num' => 2, 'label' => 'Select Template'],
                    ['num' => 3, 'label' => 'Map Variables'],
                    ['num' => 4, 'label' => 'Preview & Generate'],
                ] as $s)
                    <li class="relative {{ $loop->last ? '' : 'flex-1 pr-8 sm:pr-20' }}">
                        <div class="flex items-center">
                            <button
                                type="button"
                                wire:click="goToStep({{ $s['num'] }})"
                                @class([
                                    'relative z-10 flex h-10 w-10 items-center justify-center rounded-full text-sm font-semibold transition-all duration-300',
                                    'bg-indigo-600 text-white shadow-lg shadow-indigo-200' => $currentStep === $s['num'],
                                    'bg-indigo-600 text-white' => $currentStep > $s['num'],
                                    'bg-gray-100 text-gray-500 border-2 border-gray-200' => $currentStep < $s['num'],
                                ])
                                @if($currentStep < $s['num']) disabled @endif
                            >
                                @if($currentStep > $s['num'])
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                @else
                                    {{ $s['num'] }}
                                @endif
                            </button>

                            @if(!$loop->last)
                                <div class="absolute top-5 left-10 right-0 h-0.5 {{ $currentStep > $s['num'] ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                        <p class="mt-2 text-xs font-medium {{ $currentStep >= $s['num'] ? 'text-indigo-600' : 'text-gray-400' }}">
                            {{ $s['label'] }}
                        </p>
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>

    {{-- Step 1: Select Data Source --}}
    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    Select Data Source
                </h3>
                <p class="text-sm text-gray-500 mt-1">Choose which data source to use for generating pages</p>
            </div>

            <div class="p-6">
                @if($dataSources->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                        </svg>
                        <p class="text-sm font-medium text-gray-500">No data sources found</p>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Import a CSV or connect a data source first</p>
                        <a href="{{ route('app.sites.show', $site) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                            </svg>
                            Go to Site Dashboard
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($dataSources as $ds)
                            <label
                                class="relative flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $dataSourceId == $ds->id ? 'border-indigo-500 bg-indigo-50/50 ring-2 ring-indigo-200' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}"
                            >
                                <input
                                    type="radio"
                                    wire:model.live="dataSourceId"
                                    value="{{ $ds->id }}"
                                    class="mt-1 h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                >
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800">{{ $ds->name }}</p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 0v.375" />
                                            </svg>
                                            {{ $ds->entries()->count() }} entries
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                            </svg>
                                            {{ $ds->type->value }}
                                        </span>
                                    </div>
                                    @if($ds->last_synced_at)
                                        <p class="text-[10px] text-gray-400 mt-1">Last synced: {{ $ds->last_synced_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('dataSourceId')
                        <p class="mt-3 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            @if($dataSources->isNotEmpty())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button
                        wire:click="nextStep"
                        type="button"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-sm"
                    >
                        Next: Select Template
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Step 2: Select Template --}}
    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Select Template
                </h3>
                <p class="text-sm text-gray-500 mt-1">Choose a page template to structure the generated content</p>
            </div>

            <div class="p-6">
                @if($templates->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <p class="text-sm font-medium text-gray-500">No templates found</p>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Create a prompt template first</p>
                        <a href="{{ route('app.sites.content.prompts', $site) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors" wire:navigate>
                            Create Template
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($templates as $template)
                            <label
                                class="relative flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $templateId == $template->id ? 'border-indigo-500 bg-indigo-50/50 ring-2 ring-indigo-200' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}"
                            >
                                <input
                                    type="radio"
                                    wire:model.live="templateId"
                                    value="{{ $template->id }}"
                                    class="mt-1 h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                >
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-gray-800">{{ $template->name }}</p>
                                        @if($template->is_system)
                                            <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded bg-blue-100 text-blue-700">System</span>
                                        @endif
                                    </div>
                                    @if($template->niche_type)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $template->niche_type->label() }}</p>
                                    @endif
                                    @if($template->variable_schema)
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @foreach(array_keys($template->variable_schema) as $var)
                                                <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium rounded bg-gray-100 text-gray-600">{!! '&#123;&#123;' . e($var) . '&#125;&#125;' !!}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('templateId')
                        <p class="mt-3 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <button
                    wire:click="previousStep"
                    type="button"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back
                </button>
                <button
                    wire:click="nextStep"
                    type="button"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-sm"
                >
                    Next: Map Variables
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Step 3: Map Variables --}}
    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    Map Variables
                </h3>
                <p class="text-sm text-gray-500 mt-1">Connect data source columns to template variables</p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Page Title Mapping --}}
                <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                    <h4 class="text-sm font-semibold text-indigo-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                        Required: Page Identity
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-indigo-700 mb-1">Title Column *</label>
                            <select
                                wire:model="titleColumn"
                                class="w-full border-indigo-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white"
                            >
                                <option value="">-- Select column for page title --</option>
                                @foreach($dataSourceColumns as $col)
                                    <option value="{{ $col }}">{{ $col }}</option>
                                @endforeach
                            </select>
                            @error('titleColumn') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-indigo-700 mb-1">Slug Column (optional)</label>
                            <select
                                wire:model="slugColumn"
                                class="w-full border-indigo-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white"
                            >
                                <option value="">-- Auto-generate from title --</option>
                                @foreach($dataSourceColumns as $col)
                                    <option value="{{ $col }}">{{ $col }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Template Variable Mappings --}}
                @if(!empty($templateVariables))
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Template Variable Mappings</h4>
                        <div class="space-y-3">
                            @foreach($templateVariables as $variable)
                                <div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50 border border-gray-100">
                                    <div class="flex-1">
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-medium rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200">
                                            {!! '&#123;&#123;' . e($variable) . '&#125;&#125;' !!}
                                        </span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                    <div class="flex-1">
                                        <select
                                            wire:model="columnMappings.{{ $variable }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        >
                                            <option value="">-- Skip / Leave empty --</option>
                                            @foreach($dataSourceColumns as $col)
                                                <option value="{{ $col }}">{{ $col }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Generation Settings --}}
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Generation Settings</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Generation Method</label>
                            <select wire:model="generationMethod" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="template">Template Only (no AI)</option>
                                <option value="hybrid">Hybrid (Template + AI)</option>
                                <option value="ai">Full AI</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tone</label>
                            <select wire:model="tone" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="professional">Professional</option>
                                <option value="casual">Casual</option>
                                <option value="academic">Academic</option>
                                <option value="friendly">Friendly</option>
                                <option value="persuasive">Persuasive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Word Count</label>
                            <input wire:model="wordCount" type="number" min="300" max="5000" step="100" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Additional Prompt (optional)</label>
                        <textarea
                            wire:model="prompt"
                            rows="2"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Additional instructions for AI generation (applies to all pages)..."
                        ></textarea>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <button
                    wire:click="previousStep"
                    type="button"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back
                </button>
                <button
                    wire:click="nextStep"
                    type="button"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-sm"
                >
                    Next: Preview & Generate
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Step 4: Preview & Generate --}}
    <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
        <div class="space-y-6">
            {{-- Summary Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                    </svg>
                    Generation Summary
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-3 bg-gray-50 rounded-lg text-center">
                        <p class="text-2xl font-bold text-indigo-600">{{ $total }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Pages</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ ucfirst($generationMethod) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Method</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ ucfirst($tone) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Tone</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($wordCount) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Words/Page</p>
                    </div>
                </div>

                @if($generationMethod !== 'template')
                    <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-100">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                            </svg>
                            <p class="text-xs text-amber-800">
                                AI generation for {{ $total }} pages will use approximately <strong>{{ $total * max(1, intval($wordCount / 750)) }}</strong> AI credits.
                                This process may take several minutes.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Preview Cards --}}
            @if(!empty($previewPages))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Preview (First {{ count($previewPages) }} Pages)
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($previewPages as $index => $preview)
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-800">{{ $preview['title'] }}</h4>
                                        <p class="text-xs text-gray-400 font-mono mt-0.5">/{{ $preview['slug'] }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded bg-gray-100 text-gray-600">
                                        Page {{ $index + 1 }}
                                    </span>
                                </div>

                                @if($preview['content_preview'])
                                    <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <p class="text-xs text-gray-600 leading-relaxed">{{ $preview['content_preview'] }}</p>
                                    </div>
                                @endif

                                @if(!empty($preview['variables']))
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        @foreach(array_slice($preview['variables'], 0, 6) as $key => $val)
                                            <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] rounded bg-indigo-50 text-indigo-600 border border-indigo-100">
                                                {{ $key }}: {{ Str::limit(is_array($val) ? json_encode($val) : (string)$val, 20) }}
                                            </span>
                                        @endforeach
                                        @if(count($preview['variables']) > 6)
                                            <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] rounded bg-gray-100 text-gray-500">
                                                +{{ count($preview['variables']) - 6 }} more
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Progress Bar (during generation) --}}
            @if($generating)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generating Pages...
                        </h3>
                        <span class="text-sm font-bold text-indigo-600">{{ $progress }} / {{ $total }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div
                            class="bg-gradient-to-r from-indigo-500 to-purple-500 h-3 rounded-full transition-all duration-500 ease-out"
                            style="width: {{ $total > 0 ? ($progress / $total) * 100 : 0 }}%"
                        ></div>
                    </div>
                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            {{ $successCount }} succeeded
                        </span>
                        @if($failCount > 0)
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                {{ $failCount }} failed
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between">
                <button
                    wire:click="previousStep"
                    type="button"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200"
                    @if($generating) disabled @endif
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back
                </button>

                <button
                    wire:click="generateAll"
                    wire:loading.attr="disabled"
                    :disabled="$wire.generating"
                    type="button"
                    class="relative inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-200"
                >
                    <div wire:loading wire:target="generateAll" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Generating {{ $total }} Pages...</span>
                    </div>

                    <div wire:loading.remove wire:target="generateAll" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                        <span>Generate All {{ $total }} Pages</span>
                    </div>

                    {{-- Pulse animation --}}
                    <span
                        wire:loading
                        wire:target="generateAll"
                        class="absolute inset-0 rounded-xl animate-ping bg-indigo-400 opacity-20"
                    ></span>
                </button>
            </div>
        </div>
    </div>
</div>
