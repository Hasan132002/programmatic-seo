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

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">Manage where ads appear on your generated pages.</p>
        <button
            wire:click="$set('showCreateModal', true)"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition"
        >
            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Placement
        </button>
    </div>

    {{-- Create Modal --}}
    @if($showCreateModal)
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
                @click.outside="$wire.set('showCreateModal', false)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-lg shadow-xl w-full max-w-lg"
            >
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">Create Ad Placement</h3>
                </div>
                <form wire:submit="createPlacement" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Placement Name</label>
                        <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g., Top Banner, Sidebar Ad">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ad Type</label>
                            <select wire:model="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="adsense">Google AdSense</option>
                                <option value="custom">Custom HTML</option>
                                <option value="affiliate">Affiliate Banner</option>
                            </select>
                            @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                            <select wire:model="position" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="header">Header</option>
                                <option value="before-content">Before Content</option>
                                <option value="in-content">In Content</option>
                                <option value="after-paragraph-2">After Paragraph 2</option>
                                <option value="after-content">After Content</option>
                                <option value="sidebar">Sidebar</option>
                                <option value="footer">Footer</option>
                            </select>
                            @error('position') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ad Code</label>
                        <textarea
                            wire:model="code"
                            rows="6"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono bg-gray-900 text-green-400 p-3"
                            placeholder="Paste your ad code here..."
                            spellcheck="false"
                        ></textarea>
                        @error('code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Position Preview --}}
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Position Preview</p>
                        <div class="space-y-2">
                            <div class="h-6 rounded {{ $position === 'header' ? 'bg-indigo-200 border-2 border-indigo-400' : 'bg-gray-200' }} flex items-center justify-center">
                                <span class="text-[10px] text-gray-500 font-medium">HEADER</span>
                            </div>
                            <div class="h-4 rounded {{ $position === 'before-content' ? 'bg-indigo-200 border-2 border-indigo-400' : 'bg-gray-200' }} flex items-center justify-center">
                                <span class="text-[10px] text-gray-500 font-medium">BEFORE CONTENT</span>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex-1 space-y-1.5">
                                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                                    <div class="h-3 bg-gray-200 rounded w-11/12"></div>
                                    <div class="h-3 bg-gray-200 rounded w-10/12"></div>
                                    <div class="h-4 rounded {{ $position === 'after-paragraph-2' ? 'bg-indigo-200 border-2 border-indigo-400' : 'bg-gray-100' }} flex items-center justify-center">
                                        <span class="text-[9px] text-gray-400 font-medium">AFTER P2</span>
                                    </div>
                                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                                    <div class="h-4 rounded {{ $position === 'in-content' ? 'bg-indigo-200 border-2 border-indigo-400' : 'bg-gray-100' }} flex items-center justify-center">
                                        <span class="text-[9px] text-gray-400 font-medium">IN-CONTENT</span>
                                    </div>
                                    <div class="h-3 bg-gray-200 rounded w-9/12"></div>
                                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                                </div>
                                <div class="w-16 rounded {{ $position === 'sidebar' ? 'bg-indigo-200 border-2 border-indigo-400' : 'bg-gray-200' }} flex items-center justify-center">
                                    <span class="text-[9px] text-gray-400 font-medium writing-vertical" style="writing-mode: vertical-rl">SIDEBAR</span>
                                </div>
                            </div>
                            <div class="h-4 rounded {{ $position === 'after-content' ? 'bg-indigo-200 border-2 border-indigo-400' : 'bg-gray-200' }} flex items-center justify-center">
                                <span class="text-[10px] text-gray-500 font-medium">AFTER CONTENT</span>
                            </div>
                            <div class="h-6 rounded {{ $position === 'footer' ? 'bg-indigo-200 border-2 border-indigo-400' : 'bg-gray-200' }} flex items-center justify-center">
                                <span class="text-[10px] text-gray-500 font-medium">FOOTER</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="isActive" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 transition">Create Placement</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Modal --}}
    @if($showEditModal)
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
                @click.outside="$wire.set('showEditModal', false)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-lg shadow-xl w-full max-w-lg"
            >
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Ad Placement</h3>
                </div>
                <form wire:submit="updatePlacement" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Placement Name</label>
                        <input type="text" wire:model="editName" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('editName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ad Type</label>
                            <select wire:model="editType" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="adsense">Google AdSense</option>
                                <option value="custom">Custom HTML</option>
                                <option value="affiliate">Affiliate Banner</option>
                            </select>
                            @error('editType') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                            <select wire:model="editPosition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="header">Header</option>
                                <option value="before-content">Before Content</option>
                                <option value="in-content">In Content</option>
                                <option value="after-paragraph-2">After Paragraph 2</option>
                                <option value="after-content">After Content</option>
                                <option value="sidebar">Sidebar</option>
                                <option value="footer">Footer</option>
                            </select>
                            @error('editPosition') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ad Code</label>
                        <textarea wire:model="editCode" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono bg-gray-900 text-green-400 p-3" spellcheck="false"></textarea>
                        @error('editCode') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="editIsActive" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 transition">Update Placement</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Placements Grid --}}
    @if($placements->isEmpty())
        <div class="bg-white shadow-sm rounded-lg p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
            <p class="text-gray-500 mb-2">No ad placements configured yet.</p>
            <p class="text-sm text-gray-400">Create ad placements to monetize your generated pages.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($placements as $placement)
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    {{-- Card Header --}}
                    <div class="px-5 py-4 border-b border-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0">
                                @php
                                    $typeColors = [
                                        'adsense' => 'bg-blue-100 text-blue-700',
                                        'custom' => 'bg-purple-100 text-purple-700',
                                        'affiliate' => 'bg-orange-100 text-orange-700',
                                    ];
                                    $typeIcons = [
                                        'adsense' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'custom' => 'M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5',
                                        'affiliate' => 'M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.03a4.5 4.5 0 00-1.242-7.244l4.5-4.5a4.5 4.5 0 016.364 6.364l-1.757 1.757',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $typeColors[$placement->type] ?? 'bg-gray-100 text-gray-700' }} mr-2">
                                    {{ ucfirst($placement->type) }}
                                </span>
                                <h4 class="text-sm font-semibold text-gray-800 truncate">{{ $placement->name }}</h4>
                            </div>
                            {{-- Active Toggle --}}
                            <button
                                wire:click="toggleActive({{ $placement->id }})"
                                class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $placement->is_active ? 'bg-indigo-600' : 'bg-gray-200' }}"
                                role="switch"
                                aria-checked="{{ $placement->is_active ? 'true' : 'false' }}"
                            >
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $placement->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="px-5 py-3">
                        <div class="flex items-center text-xs text-gray-500 mb-3">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Position: <span class="font-medium ml-1 text-gray-700">{{ ucwords(str_replace('-', ' ', $placement->position)) }}</span>
                        </div>

                        {{-- Code Preview --}}
                        <div class="bg-gray-900 rounded-md p-3 max-h-20 overflow-hidden relative">
                            <code class="text-[11px] text-green-400 font-mono leading-tight break-all">{{ Str::limit($placement->code, 150) }}</code>
                            <div class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-gray-900 to-transparent"></div>
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="px-5 py-3 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-xs text-gray-400">{{ $placement->created_at->diffForHumans() }}</span>
                        <div class="flex items-center gap-3">
                            <button wire:click="editPlacement({{ $placement->id }})" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium transition">Edit</button>
                            <button wire:click="deletePlacement({{ $placement->id }})" wire:confirm="Are you sure you want to delete this ad placement?" class="text-red-600 hover:text-red-800 text-xs font-medium transition">Delete</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($placements->hasPages())
            <div class="mt-6">
                {{ $placements->links() }}
            </div>
        @endif
    @endif
</div>
