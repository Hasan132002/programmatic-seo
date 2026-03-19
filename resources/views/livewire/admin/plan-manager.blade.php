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
    @if(session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $plans->count() }} plan{{ $plans->count() !== 1 ? 's' : '' }} configured</p>
        <button wire:click="openCreateModal"
                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Plan
        </button>
    </div>

    {{-- Plan Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($plans as $plan)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden {{ !$plan->is_active ? 'opacity-60' : '' }}">
                {{-- Card Header --}}
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                        <div class="flex items-center space-x-2">
                            @if(!$plan->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    Inactive
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                {{ $plan->users_count }} user{{ $plan->users_count !== 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-3xl font-bold text-gray-900">${{ number_format($plan->price_monthly, 2) }}</span>
                        <span class="text-sm text-gray-500">/month</span>
                    </div>
                    @if($plan->price_yearly > 0)
                        <p class="text-xs text-gray-400 mt-1">${{ number_format($plan->price_yearly, 2) }}/year</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">Slug: {{ $plan->slug }}</p>
                </div>

                {{-- Limits --}}
                <div class="px-6 py-4 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Max Sites</span>
                        <span class="font-medium text-gray-900">
                            {{ $plan->max_sites === -1 ? 'Unlimited' : $plan->max_sites }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Pages per Site</span>
                        <span class="font-medium text-gray-900">
                            {{ $plan->max_pages_per_site === -1 ? 'Unlimited' : $plan->max_pages_per_site }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">AI Credits / Month</span>
                        <span class="font-medium text-gray-900">
                            {{ $plan->max_ai_credits_monthly === -1 ? 'Unlimited' : $plan->max_ai_credits_monthly }}
                        </span>
                    </div>

                    {{-- Features --}}
                    @if($plan->features && count($plan->features) > 0)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Features</p>
                            <ul class="space-y-1.5">
                                @foreach($plan->features as $feature)
                                    <li class="flex items-start text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Card Actions --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <button wire:click="toggleActive({{ $plan->id }})"
                            class="text-sm font-medium {{ $plan->is_active ? 'text-yellow-600 hover:text-yellow-700' : 'text-green-600 hover:text-green-700' }}">
                        {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    <div class="flex items-center space-x-3">
                        <button wire:click="openEditModal({{ $plan->id }})"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                            Edit
                        </button>
                        <button wire:click="confirmDelete({{ $plan->id }})"
                                class="text-sm font-medium text-red-600 hover:text-red-700">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="mt-4 text-sm text-gray-500">No plans configured yet.</p>
                <button wire:click="openCreateModal" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Create Your First Plan
                </button>
            </div>
        @endforelse
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-start justify-center min-h-screen px-4 pt-20 pb-8">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.closeModal()"></div>

                <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6 z-10">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">
                        {{ $editingPlanId ? 'Edit Plan' : 'Create New Plan' }}
                    </h3>

                    <form wire:submit="save" class="space-y-4">
                        {{-- Name & Slug --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                                <input wire:model.live="name" type="text" placeholder="e.g. Pro"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                                <input wire:model="slug" type="text" placeholder="e.g. pro"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Stripe Price ID --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stripe Price ID</label>
                            <input wire:model="stripe_price_id" type="text" placeholder="price_..."
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Prices --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Price ($)</label>
                                <input wire:model="price_monthly" type="number" step="0.01" min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('price_monthly') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Yearly Price ($)</label>
                                <input wire:model="price_yearly" type="number" step="0.01" min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('price_yearly') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Limits --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Sites</label>
                                <input wire:model="max_sites" type="number" min="-1"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="mt-1 text-xs text-gray-400">-1 = unlimited</p>
                                @error('max_sites') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pages/Site</label>
                                <input wire:model="max_pages_per_site" type="number" min="-1"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="mt-1 text-xs text-gray-400">-1 = unlimited</p>
                                @error('max_pages_per_site') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">AI Credits</label>
                                <input wire:model="max_ai_credits_monthly" type="number" min="-1"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="mt-1 text-xs text-gray-400">-1 = unlimited</p>
                                @error('max_ai_credits_monthly') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Features --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Features (one per line)</label>
                            <textarea wire:model="features" rows="4" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>

                        {{-- Sort Order & Active --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                    <input wire:model="sort_order" type="number" min="0"
                                           class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                            <label class="flex items-center cursor-pointer">
                                <input wire:model="is_active" type="checkbox" class="sr-only peer">
                                <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                {{ $editingPlanId ? 'Update Plan' : 'Create Plan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($confirmingDeleteId)
        @php $deletingPlan = \App\Models\Plan::withCount('users')->find($confirmingDeleteId); @endphp
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
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">Delete Plan</h3>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        Are you sure you want to delete <strong>{{ $deletingPlan?->name }}</strong>?
                        @if($deletingPlan && $deletingPlan->users_count > 0)
                            <span class="text-red-600 font-medium">This plan has {{ $deletingPlan->users_count }} active user(s) and cannot be deleted.</span>
                        @else
                            This action cannot be undone.
                        @endif
                    </p>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="cancelDelete"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button wire:click="deletePlan"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Delete Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
