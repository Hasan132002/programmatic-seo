<div>
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Header with Search & Create --}}
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="relative flex-1 w-full sm:max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search data sources..."
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
            />
        </div>
        <div class="flex gap-3">
            <button wire:click="openCreate" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Create Source
            </button>
            <a href="{{ route('app.sites.data.import', $site) }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                Import CSV
            </a>
        </div>
    </div>

    {{-- Data Sources List --}}
    @if ($dataSources->isEmpty())
        <div class="bg-white shadow-sm rounded-xl p-12 text-center border border-gray-100">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-50 mb-4">
                <svg class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
            </div>
            <p class="text-gray-600 mb-2 text-lg font-semibold">No data sources yet</p>
            <p class="text-gray-400 mb-6 text-sm">Import a CSV file or connect an API to get started.</p>
            <a href="{{ route('app.sites.data.import', $site) }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Import Your First Data
            </a>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entries</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Synced</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($dataSources as $dataSource)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('app.sites.data.browse', [$site, $dataSource]) }}" class="font-medium text-indigo-600 hover:text-indigo-800">
                                    {{ $dataSource->name }}
                                </a>
                                @if ($dataSource->config && isset($dataSource->config['original_filename']))
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $dataSource->config['original_filename'] }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'csv' => 'bg-blue-100 text-blue-800',
                                        'api' => 'bg-purple-100 text-purple-800',
                                        'manual' => 'bg-gray-100 text-gray-800',
                                        'scrape' => 'bg-orange-100 text-orange-800',
                                    ];
                                    $colorClass = $typeColors[$dataSource->type->value] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                    {{ $dataSource->type->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ number_format($dataSource->entries_count) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if ($dataSource->last_synced_at)
                                    <span title="{{ $dataSource->last_synced_at->format('M d, Y H:i:s') }}">
                                        {{ $dataSource->last_synced_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Never</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm space-x-2">
                                <a href="{{ route('app.sites.data.browse', [$site, $dataSource]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    View
                                </a>
                                @if ($dataSource->type === \App\Enums\DataSourceType::Api)
                                    <button
                                        wire:click="resync({{ $dataSource->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="resync({{ $dataSource->id }})"
                                        class="text-purple-600 hover:text-purple-800 font-medium"
                                    >
                                        <span wire:loading.remove wire:target="resync({{ $dataSource->id }})">Re-sync</span>
                                        <span wire:loading wire:target="resync({{ $dataSource->id }})">
                                            <svg class="animate-spin inline h-4 w-4 mr-1" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                            Syncing...
                                        </span>
                                    </button>
                                @endif
                                <button
                                    wire:click="delete({{ $dataSource->id }})"
                                    wire:confirm="Are you sure you want to delete &quot;{{ $dataSource->name }}&quot; and all its entries? This action cannot be undone."
                                    class="text-red-600 hover:text-red-800 font-medium"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary --}}
        <div class="mt-4 text-sm text-gray-500">
            Showing {{ $dataSources->count() }} {{ Str::plural('data source', $dataSources->count()) }}
            with {{ number_format($dataSources->sum('entries_count')) }} total {{ Str::plural('entry', $dataSources->sum('entries_count')) }}
        </div>
    @endif

    {{-- Create Data Source Modal --}}
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="document.body.classList.add('overflow-hidden')" x-on:remove="document.body.classList.remove('overflow-hidden')">
            <div class="flex items-center justify-center min-h-screen px-4">
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" wire:click="$set('showCreateModal', false)"></div>

                {{-- Modal --}}
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Create Data Source</h3>
                        <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit="create" class="space-y-4">
                        <div>
                            <label for="newName" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" wire:model="newName" id="newName" placeholder="e.g. City API Data"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('newName') border-red-500 ring-1 ring-red-500 @enderror" />
                            @error('newName')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center p-3 rounded-lg border-2 cursor-pointer transition {{ $newType === 'manual' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" wire:model.live="newType" value="manual" class="sr-only" />
                                    <div>
                                        <span class="block text-sm font-medium {{ $newType === 'manual' ? 'text-indigo-900' : 'text-gray-900' }}">Manual</span>
                                        <span class="block text-xs {{ $newType === 'manual' ? 'text-indigo-600' : 'text-gray-500' }}">Add entries one by one</span>
                                    </div>
                                </label>
                                <label class="relative flex items-center p-3 rounded-lg border-2 cursor-pointer transition {{ $newType === 'api' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" wire:model.live="newType" value="api" class="sr-only" />
                                    <div>
                                        <span class="block text-sm font-medium {{ $newType === 'api' ? 'text-indigo-900' : 'text-gray-900' }}">API</span>
                                        <span class="block text-xs {{ $newType === 'api' ? 'text-indigo-600' : 'text-gray-500' }}">Fetch from REST API</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @if ($newType === 'api')
                            <div class="space-y-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div>
                                    <label for="apiUrl" class="block text-sm font-medium text-gray-700 mb-1">API URL</label>
                                    <input type="url" wire:model="apiUrl" id="apiUrl" placeholder="https://api.example.com/data"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('apiUrl') border-red-500 @enderror" />
                                    @error('apiUrl')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="apiMethod" class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                                        <select wire:model="apiMethod" id="apiMethod" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <option value="GET">GET</option>
                                            <option value="POST">POST</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="apiDataPath" class="block text-sm font-medium text-gray-700 mb-1">Data Path</label>
                                        <input type="text" wire:model="apiDataPath" id="apiDataPath" placeholder="data.items"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                        <p class="mt-1 text-xs text-gray-400">Dot notation to nested array</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" wire:click="$set('showCreateModal', false)"
                                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                <span wire:loading.remove wire:target="create">Create Data Source</span>
                                <span wire:loading wire:target="create" class="flex items-center">
                                    <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Creating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
