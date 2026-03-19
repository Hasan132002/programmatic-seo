<div>
    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="ml-3 text-sm font-medium text-green-800">{{ session('message') }}</p>
                <div class="ml-auto">
                    <a href="{{ route('app.sites.data.index', $site) }}" class="text-sm font-medium text-green-700 underline hover:text-green-900">
                        View Data Sources
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Error Messages --}}
    @error('csvFile')
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <p class="ml-3 text-sm font-medium text-red-800">{{ $message }}</p>
            </div>
        </div>
    @enderror

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            {{-- Upload Area --}}
            @if (!$showPreview)
                <div
                    x-data="{
                        isDragging: false,
                        handleDrop(e) {
                            this.isDragging = false;
                            if (e.dataTransfer.files.length) {
                                @this.upload('csvFile', e.dataTransfer.files[0])
                            }
                        }
                    }"
                    x-on:dragover.prevent="isDragging = true"
                    x-on:dragleave.prevent="isDragging = false"
                    x-on:drop.prevent="handleDrop($event)"
                    class="relative border-2 border-dashed rounded-lg p-12 text-center transition-colors duration-200"
                    :class="isDragging ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 hover:border-gray-400'"
                >
                    <div class="space-y-4">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-50">
                            <svg class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-medium text-gray-700">Drop CSV file here or click to browse</p>
                            <p class="mt-1 text-sm text-gray-500">Supports .csv and .txt files up to 10MB</p>
                        </div>
                        <label class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 cursor-pointer transition">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Choose File
                            <input type="file" wire:model="csvFile" accept=".csv,.txt" class="hidden" />
                        </label>
                    </div>

                    {{-- Loading indicator while file is uploading --}}
                    <div wire:loading wire:target="csvFile" class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center rounded-lg">
                        <div class="text-center">
                            <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-sm font-medium text-gray-600">Uploading file...</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Preview Section --}}
            @if ($showPreview)
                <div class="space-y-6">
                    {{-- File Info --}}
                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $csvFile ? $csvFile->getClientOriginalName() : 'CSV File' }}
                                </p>
                                <div class="flex items-center space-x-3 mt-1">
                                    @if ($csvFile)
                                        <span class="text-xs text-gray-500">
                                            {{ number_format($csvFile->getSize() / 1024, 1) }} KB
                                        </span>
                                        <span class="text-gray-300">|</span>
                                    @endif
                                    <span class="text-xs text-gray-500">
                                        {{ $totalRows }} {{ Str::plural('row', $totalRows) }}
                                    </span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-xs text-gray-500">
                                        {{ count($headers) }} {{ Str::plural('column', count($headers)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button wire:click="removeFile" class="text-gray-400 hover:text-red-500 transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Data Source Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Data Source Name</label>
                        <input
                            type="text"
                            id="name"
                            wire:model="name"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter a name for this data source"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Column Headers --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Detected Columns</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($headers as $header)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $header }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Preview Table --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">
                            Preview
                            <span class="text-gray-400 font-normal">(first {{ count($preview) }} of {{ $totalRows }} rows)</span>
                        </h3>
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        @foreach ($headers as $header)
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                {{ $header }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($preview as $index => $row)
                                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $index + 1 }}</td>
                                            @foreach ($headers as $header)
                                                <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate">
                                                    {{ $row[$header] ?? '' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Import Button --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Ready to import <span class="font-semibold text-gray-700">{{ $totalRows }}</span> {{ Str::plural('entry', $totalRows) }}
                        </p>
                        <div class="flex items-center space-x-3">
                            <button
                                wire:click="removeFile"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition"
                            >
                                Cancel
                            </button>
                            <button
                                wire:click="import"
                                wire:loading.attr="disabled"
                                wire:target="import"
                                class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                            >
                                <span wire:loading.remove wire:target="import">
                                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Import Data
                                </span>
                                <span wire:loading wire:target="import" class="flex items-center">
                                    <svg class="animate-spin h-4 w-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Importing...
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- Import Progress (shown after import completes) --}}
                    @if ($importedCount > 0 && !$importing)
                        <div class="rounded-lg bg-green-50 border border-green-200 p-4 mt-4">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="ml-3 text-sm font-medium text-green-800">
                                    Successfully imported {{ $importedCount }} {{ Str::plural('entry', $importedCount) }}!
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
