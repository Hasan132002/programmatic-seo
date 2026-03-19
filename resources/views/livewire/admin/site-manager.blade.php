<div>
    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex flex-col sm:flex-row gap-3 flex-1">
            {{-- Search --}}
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search sites by name, domain, or subdomain..."
                       class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- User Filter --}}
            <select wire:model.live="filterUser" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Owners</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>

            {{-- Niche Filter --}}
            <select wire:model.live="filterNiche" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Niches</option>
                @foreach($nicheTypes as $niche)
                    <option value="{{ $niche->value }}">{{ $niche->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="text-sm text-gray-500">
            {{ $sites->total() }} site{{ $sites->total() !== 1 ? 's' : '' }} total
        </div>
    </div>

    {{-- Sites Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('name')" class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Site
                                @if($sortField === 'name')
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Niche</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Pages</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('created_at')" class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Created
                                @if($sortField === 'created_at')
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sites as $site)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Site Name & Domain --}}
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $site->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        @if($site->domain)
                                            <span class="inline-flex items-center">
                                                <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $site->domain }}
                                            </span>
                                        @elseif($site->subdomain)
                                            {{ $site->subdomain }}.{{ config('pseo.platform_domain', 'localhost') }}
                                        @else
                                            No domain configured
                                        @endif
                                    </p>
                                </div>
                            </td>

                            {{-- Owner --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($site->tenant)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-gray-400 flex items-center justify-center text-xs font-semibold text-white">
                                            {{ strtoupper(substr($site->tenant->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-2">
                                            <p class="text-sm text-gray-900">{{ $site->tenant->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $site->tenant->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Unknown</span>
                                @endif
                            </td>

                            {{-- Niche --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $site->niche_type->label() }}
                                </span>
                            </td>

                            {{-- Pages Count --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-700">
                                {{ $site->pages_count }}
                            </td>

                            {{-- Published Status --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="togglePublish({{ $site->id }})"
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors
                                        {{ $site->is_published ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $site->is_published ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    {{ $site->is_published ? 'Published' : 'Draft' }}
                                </button>
                            </td>

                            {{-- Created --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $site->created_at->format('M d, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <button wire:click="confirmDelete({{ $site->id }})"
                                        class="text-red-600 hover:text-red-800 font-medium">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                <p class="mt-4 text-sm text-gray-500">No sites found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($sites->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $sites->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($confirmingDeleteId)
        @php $deletingSite = \App\Models\Site::withoutGlobalScopes()->withCount('pages')->find($confirmingDeleteId); @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.cancelDelete()"></div>

                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">Delete Site</h3>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        Are you sure you want to delete <strong>{{ $deletingSite?->name }}</strong>?
                        This will permanently remove the site and its <strong>{{ $deletingSite?->pages_count ?? 0 }}</strong> page(s).
                        This action cannot be undone.
                    </p>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="cancelDelete"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button wire:click="deleteSite"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Delete Site
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
