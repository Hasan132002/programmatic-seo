<div>
    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center shadow-sm"
        >
            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center shadow-sm">
            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search by URL or keyword..."
                class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
        </div>
        <div class="flex items-center gap-2">
            <button
                wire:click="$toggle('showImportModal')"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                Bulk Import
            </button>
            <button
                wire:click="$toggle('showCreateForm')"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Link
            </button>
        </div>
    </div>

    {{-- Import Modal --}}
    @if($showImportModal)
        <div
            x-data="{ open: true }"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        >
            <div
                @click.outside="$wire.set('showImportModal', false)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-lg shadow-xl w-full max-w-md p-6"
            >
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Import Affiliate Links from CSV</h3>
                <form wire:submit="importCsv">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-3">Upload a CSV file with columns: <code class="bg-gray-100 px-1 py-0.5 rounded text-xs">original_url, affiliate_url, keyword</code></p>
                        <input
                            type="file"
                            wire:model="csvFile"
                            accept=".csv,.txt"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100"
                        >
                        @error('csvFile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="csvFile" class="mt-2 text-xs text-indigo-600">Uploading file...</div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showImportModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 transition">Import</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Create Form --}}
    @if($showCreateForm)
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6 border border-indigo-100">
            <h4 class="text-sm font-semibold text-gray-800 mb-4">Create Affiliate Link</h4>
            <form wire:submit="createLink" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Original URL</label>
                    <input
                        type="url"
                        wire:model="originalUrl"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="https://example.com/product"
                    >
                    @error('originalUrl') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Affiliate URL</label>
                    <input
                        type="url"
                        wire:model="affiliateUrl"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="https://example.com/product?ref=yourcode"
                    >
                    @error('affiliateUrl') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Auto-Replace Keyword</label>
                    <input
                        type="text"
                        wire:model="keyword"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="product name"
                    >
                    <p class="mt-1 text-xs text-gray-400">Keyword in content that will auto-link to this affiliate URL.</p>
                    @error('keyword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-3 flex items-center justify-end gap-2">
                    <button type="button" wire:click="$set('showCreateForm', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">Create Link</button>
                </div>
            </form>
        </div>
    @endif

    {{-- Links Table --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        @if($links->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.03a4.5 4.5 0 00-1.242-7.244l4.5-4.5a4.5 4.5 0 016.364 6.364l-1.757 1.757" />
                </svg>
                <p class="text-gray-500 mb-2">No affiliate links yet.</p>
                <p class="text-sm text-gray-400">Add affiliate links to automatically monetize your content.</p>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original URL</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12"></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affiliate URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keyword</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($links as $link)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ $link->original_url }}" target="_blank" rel="noopener" class="text-sm text-gray-700 hover:text-indigo-600 transition truncate block max-w-[220px]" title="{{ $link->original_url }}">
                                    {{ $link->original_url }}
                                </a>
                            </td>
                            <td class="px-2 py-4 text-center">
                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ $link->affiliate_url }}" target="_blank" rel="noopener" class="text-sm text-green-700 hover:text-green-900 transition truncate block max-w-[220px]" title="{{ $link->affiliate_url }}">
                                    {{ $link->affiliate_url }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($link->keyword)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        {{ $link->keyword }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $clicks = $link->clicks ?? 0;
                                    $clickColor = $clicks > 100 ? 'bg-green-100 text-green-800' : ($clicks > 10 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700');
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $clickColor }}">
                                    {{ number_format($clicks) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="resetClicks({{ $link->id }})" wire:confirm="Reset click count to 0?" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition" title="Reset clicks">Reset</button>
                                <button wire:click="deleteLink({{ $link->id }})" wire:confirm="Are you sure you want to delete this affiliate link?" class="text-red-600 hover:text-red-800 text-sm font-medium transition">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($links->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $links->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
