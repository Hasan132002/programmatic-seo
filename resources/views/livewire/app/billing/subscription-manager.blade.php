<div>
    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div class="mb-6 flex items-center p-4 rounded-xl bg-green-50 border border-green-200 shadow-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Current Plan Card --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Plan Header with Gradient --}}
                @php
                    $gradientClass = match($plan?->slug) {
                        'enterprise' => 'from-amber-500 via-orange-500 to-red-500',
                        'pro' => 'from-indigo-500 via-purple-500 to-pink-500',
                        default => 'from-gray-400 via-gray-500 to-gray-600',
                    };
                @endphp
                <div class="relative overflow-hidden bg-gradient-to-r {{ $gradientClass }} p-6 text-white">
                    {{-- Background Pattern --}}
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="billing-grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(#billing-grid)"/></svg>
                    </div>

                    <div class="relative flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-xl font-bold">{{ $plan?->name ?? 'Free' }} Plan</h3>
                                @if($subscription?->onGracePeriod())
                                    <span class="px-2 py-0.5 text-xs font-bold bg-yellow-400 text-yellow-900 rounded-full">Cancelling</span>
                                @elseif($subscription?->active())
                                    <span class="px-2 py-0.5 text-xs font-bold bg-white/20 backdrop-blur-sm text-white rounded-full">Active</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-bold bg-white/20 backdrop-blur-sm text-white rounded-full">
                                        {{ $plan?->price_monthly > 0 ? 'Inactive' : 'Free Tier' }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-white/80 text-sm">
                                @if($plan?->price_monthly > 0)
                                    ${{ number_format($plan->price_monthly, 2) }}/month
                                    @if($plan->price_yearly > 0)
                                        <span class="text-white/60">or ${{ number_format($plan->price_yearly, 2) }}/year</span>
                                    @endif
                                @else
                                    Free forever - no credit card required
                                @endif
                            </p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                            @if($plan?->slug === 'enterprise')
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            @elseif($plan?->slug === 'pro')
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @else
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                    </div>

                    {{-- Subscription Info --}}
                    @if($subscription)
                        <div class="relative mt-4 pt-4 border-t border-white/20">
                            <div class="flex items-center gap-6 text-sm">
                                @if($subscription->onGracePeriod())
                                    <div>
                                        <span class="text-white/60 text-xs uppercase tracking-wider">Access until</span>
                                        <p class="font-medium text-yellow-200">{{ $subscription->ends_at?->format('M d, Y') }}</p>
                                    </div>
                                @else
                                    <div>
                                        <span class="text-white/60 text-xs uppercase tracking-wider">Next billing</span>
                                        <p class="font-medium">
                                            @if(method_exists($subscription, 'asStripeSubscription'))
                                                {{ now()->addMonth()->format('M d, Y') }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-white/60 text-xs uppercase tracking-wider">Started</span>
                                    <p class="font-medium">{{ $subscription->created_at?->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Usage Stats --}}
                <div class="p-6">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Plan Usage</h4>
                    <div class="space-y-5">
                        {{-- Sites Usage --}}
                        @php
                            $sitesPercent = $sitesLimit > 0 ? min(100, round(($sitesUsed / $sitesLimit) * 100)) : ($sitesLimit == -1 ? 10 : 0);
                            $sitesColor = $sitesPercent >= 90 ? 'red' : ($sitesPercent >= 70 ? 'yellow' : 'indigo');
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                                    <span class="text-sm font-medium text-gray-700">Sites</span>
                                </div>
                                <span class="text-sm text-gray-500">
                                    <span class="font-semibold text-gray-900">{{ $sitesUsed }}</span>
                                    / {{ $sitesLimit == -1 ? 'Unlimited' : $sitesLimit }}
                                </span>
                            </div>
                            <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 bg-{{ $sitesColor }}-500"
                                     style="width: {{ $sitesLimit == -1 ? '10' : $sitesPercent }}%"></div>
                            </div>
                        </div>

                        {{-- Pages Usage (max across sites) --}}
                        @php
                            $pagesPercent = $pagesLimit > 0 ? min(100, round(($maxPagesUsed / $pagesLimit) * 100)) : ($pagesLimit == -1 ? 10 : 0);
                            $pagesColor = $pagesPercent >= 90 ? 'red' : ($pagesPercent >= 70 ? 'yellow' : 'purple');
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="text-sm font-medium text-gray-700">Pages (best site)</span>
                                </div>
                                <span class="text-sm text-gray-500">
                                    <span class="font-semibold text-gray-900">{{ $maxPagesUsed }}</span>
                                    / {{ $pagesLimit == -1 ? 'Unlimited' : number_format($pagesLimit) }}
                                </span>
                            </div>
                            <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 bg-{{ $pagesColor }}-500"
                                     style="width: {{ $pagesLimit == -1 ? '10' : $pagesPercent }}%"></div>
                            </div>
                        </div>

                        {{-- AI Credits Usage --}}
                        @php
                            $aiPercent = $aiCreditsLimit > 0 ? min(100, round(($aiCreditsUsed / $aiCreditsLimit) * 100)) : ($aiCreditsLimit == -1 ? 10 : 0);
                            $aiColor = $aiPercent >= 90 ? 'red' : ($aiPercent >= 70 ? 'yellow' : 'emerald');
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span class="text-sm font-medium text-gray-700">AI Credits</span>
                                </div>
                                <span class="text-sm text-gray-500">
                                    <span class="font-semibold text-gray-900">{{ number_format($aiCreditsUsed) }}</span>
                                    / {{ $aiCreditsLimit == -1 ? 'Unlimited' : ($aiCreditsLimit == 0 ? '0' : number_format($aiCreditsLimit)) }}
                                </span>
                            </div>
                            <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                @if($aiCreditsLimit == 0)
                                    <div class="h-full rounded-full bg-gray-300" style="width: 100%"></div>
                                @else
                                    <div class="h-full rounded-full transition-all duration-500 bg-{{ $aiColor }}-500"
                                         style="width: {{ $aiCreditsLimit == -1 ? '10' : $aiPercent }}%"></div>
                                @endif
                            </div>
                            @if($aiCreditsLimit == 0)
                                <p class="text-xs text-gray-400 mt-1">Upgrade to unlock AI content generation</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar: Actions & Invoices --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-900">Quick Actions</h4>
                </div>
                <div class="p-4 space-y-2">
                    @if($subscription?->onGracePeriod())
                        <button wire:click="resumeSubscription"
                                wire:loading.attr="disabled"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span wire:loading.remove wire:target="resumeSubscription">Resume Subscription</span>
                            <span wire:loading wire:target="resumeSubscription">Resuming...</span>
                        </button>
                    @elseif($subscription?->active())
                        <button wire:click="$set('showCancelModal', true)"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 border border-gray-200 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancel Subscription
                        </button>
                    @endif

                    @if(auth()->user()->hasStripeId())
                        <button wire:click="redirectToPortal"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 border border-gray-200 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Manage Payment Method
                        </button>
                    @endif

                    <a href="#plans" onclick="document.querySelector('[wire\\:id]').scrollIntoView({behavior:'smooth', block:'start'}); return false;"
                       class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-indigo-600 hover:bg-indigo-50 border border-indigo-200 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                        {{ $plan?->slug !== 'enterprise' ? 'Upgrade Plan' : 'Change Plan' }}
                    </a>
                </div>
            </div>

            {{-- Recent Invoices --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-900">Recent Invoices</h4>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($invoices as $invoice)
                        <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-gray-900">${{ $invoice->total() }}</p>
                                <p class="text-xs text-gray-500">{{ $invoice->date()->toFormattedDateString() }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $invoice->paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $invoice->paid ? 'Paid' : 'Unpaid' }}
                                </span>
                                <a href="{{ $invoice->invoicePdf() }}" target="_blank"
                                   class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-xs text-gray-400">No invoices yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel Subscription Modal --}}
    @if($showCancelModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="cancel-modal" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('showCancelModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Cancel Subscription</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Are you sure you want to cancel your <strong>{{ $plan?->name }}</strong> subscription?
                                    You'll continue to have access to your plan features until the end of your current billing period.
                                </p>
                                <div class="mt-3 p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                                    <p class="text-xs text-yellow-700">
                                        <strong>What you'll lose:</strong> After your billing period ends, you'll be downgraded to the Free plan.
                                        Your sites and data will remain, but some features may become unavailable.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button wire:click="cancelSubscription"
                                wire:loading.attr="disabled"
                                class="px-4 py-2.5 rounded-lg text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-colors">
                            <span wire:loading.remove wire:target="cancelSubscription">Yes, Cancel Subscription</span>
                            <span wire:loading wire:target="cancelSubscription">Cancelling...</span>
                        </button>
                        <button wire:click="$set('showCancelModal', false)"
                                class="px-4 py-2.5 rounded-lg text-sm font-semibold bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 transition-colors">
                            Keep My Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
