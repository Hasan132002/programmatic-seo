<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $dataSource->name }}</h2>
                <div class="flex items-center space-x-3 mt-1">
                    <p class="text-sm text-gray-500">{{ $site->name }}</p>
                    <span class="text-gray-300">|</span>
                    @php
                        $typeColors = [
                            'csv' => 'bg-blue-100 text-blue-800',
                            'api' => 'bg-purple-100 text-purple-800',
                            'manual' => 'bg-gray-100 text-gray-800',
                            'scrape' => 'bg-orange-100 text-orange-800',
                        ];
                        $colorClass = $typeColors[$dataSource->type->value] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                        {{ $dataSource->type->label() }}
                    </span>
                    @if ($dataSource->last_synced_at)
                        <span class="text-gray-300">|</span>
                        <span class="text-xs text-gray-500">Last synced {{ $dataSource->last_synced_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('app.sites.data.index', $site) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                <svg class="w-4 h-4 mr-1.5 -ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Data Sources
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:app.data.data-browser :dataSource="$dataSource" />
        </div>
    </div>
</x-app-layout>
