<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
                <nav class="flex items-center gap-1.5 text-xs text-gray-400 mb-1">
                    <a href="{{ route('app.sites.show', $site) }}" class="hover:text-indigo-600 transition-colors truncate">{{ $site->name }}</a>
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <span class="text-gray-500 font-medium">Generate Content</span>
                </nav>
                <h1 class="text-lg font-bold text-gray-900 leading-tight">AI Content Generator</h1>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('app.sites.content.keywords', $site) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm" wire:navigate>
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                    Keyword Generator
                </a>
                <a href="{{ route('app.sites.content.bulk', $site) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm" wire:navigate>
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
                    Bulk Generate
                </a>
                <a href="{{ route('app.sites.content.prompts', $site) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm" wire:navigate>
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>
                    Prompt Templates
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:app.content.content-generator :site="$site" />
        </div>
    </div>
</x-app-layout>
