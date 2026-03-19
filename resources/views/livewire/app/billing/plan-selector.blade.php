<div>
    {{-- Section Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Choose Your Plan</h3>
            <p class="text-sm text-gray-500 mt-1">Select the plan that best fits your needs</p>
        </div>

        {{-- Billing Toggle --}}
        <div class="flex items-center gap-3 bg-gray-100 rounded-xl p-1">
            <button wire:click="toggleBilling"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ !$yearly ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                Monthly
            </button>
            <button wire:click="toggleBilling"
                    class="relative px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $yearly ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                Yearly
                <span class="absolute -top-2 -right-3 px-1.5 py-0.5 text-[10px] font-bold bg-green-500 text-white rounded-full">-28%</span>
            </button>
        </div>
    </div>

    {{-- Pricing Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            @php
                $isCurrent = $currentPlanId === $plan->id;
                $isPro = $plan->slug === 'pro';
                $isEnterprise = $plan->slug === 'enterprise';
                $isFree = $plan->slug === 'free';
                $price = $yearly ? $plan->price_yearly : $plan->price_monthly;
                $period = $yearly ? 'year' : 'month';

                // Determine if this is an upgrade or downgrade
                $currentPlanSort = \App\Models\Plan::find($currentPlanId)?->sort_order ?? 0;
                $isUpgrade = $plan->sort_order > $currentPlanSort;
                $isDowngrade = $plan->sort_order < $currentPlanSort;
            @endphp

            <div class="relative group">
                {{-- Popular Badge --}}
                @if($isPro)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-10">
                        <span class="inline-flex items-center gap-1 px-4 py-1.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs font-bold rounded-full shadow-lg shadow-indigo-200">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            Most Popular
                        </span>
                    </div>
                @endif

                <div class="h-full rounded-2xl border-2 transition-all duration-300 overflow-hidden
                    {{ $isCurrent ? ($isPro ? 'border-indigo-500 shadow-lg shadow-indigo-100' : ($isEnterprise ? 'border-amber-500 shadow-lg shadow-amber-100' : 'border-gray-300 shadow-md')) : ($isPro ? 'border-indigo-200 hover:border-indigo-400 hover:shadow-lg hover:shadow-indigo-50' : ($isEnterprise ? 'border-amber-200 hover:border-amber-400 hover:shadow-lg hover:shadow-amber-50' : 'border-gray-200 hover:border-gray-300 hover:shadow-md')) }}
                    {{ $isPro ? 'bg-white' : 'bg-white' }}
                    group-hover:scale-[1.02]"
                >
                    {{-- Plan Header --}}
                    <div class="p-6 {{ $isEnterprise ? 'bg-gradient-to-br from-amber-50 to-orange-50' : ($isPro ? 'bg-gradient-to-br from-indigo-50 to-purple-50' : 'bg-gray-50') }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center
                                    {{ $isEnterprise ? 'bg-gradient-to-br from-amber-400 to-orange-500' : ($isPro ? 'bg-gradient-to-br from-indigo-400 to-purple-500' : 'bg-gray-200') }}">
                                    @if($isEnterprise)
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    @elseif($isPro)
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold {{ $isEnterprise ? 'text-amber-900' : ($isPro ? 'text-indigo-900' : 'text-gray-900') }}">{{ $plan->name }}</h3>
                            </div>
                            @if($isCurrent)
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $isEnterprise ? 'bg-amber-100 text-amber-800' : ($isPro ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-200 text-gray-700') }}">
                                    Current
                                </span>
                            @endif
                        </div>

                        {{-- Price --}}
                        <div class="flex items-baseline gap-1">
                            @if($isFree)
                                <span class="text-4xl font-extrabold text-gray-900">$0</span>
                                <span class="text-gray-500 text-sm">/forever</span>
                            @else
                                <span class="text-4xl font-extrabold {{ $isEnterprise ? 'text-amber-900' : 'text-gray-900' }}">${{ number_format($price, 0) }}</span>
                                <span class="text-gray-500 text-sm">/{{ $period }}</span>
                            @endif
                        </div>

                        @if(!$isFree && $yearly)
                            <p class="text-xs mt-1 {{ $isEnterprise ? 'text-amber-600' : 'text-indigo-600' }} font-medium">
                                Save ${{ number_format(($plan->price_monthly * 12) - $plan->price_yearly, 0) }} per year
                            </p>
                        @endif

                        @if($isFree)
                            <p class="text-sm text-gray-500 mt-2">Get started with the basics</p>
                        @elseif($isPro)
                            <p class="text-sm text-gray-500 mt-2">For growing SEO businesses</p>
                        @else
                            <p class="text-sm text-gray-500 mt-2">For teams and agencies</p>
                        @endif
                    </div>

                    {{-- Features --}}
                    <div class="p-6">
                        <ul class="space-y-3">
                            {{-- Resource limits --}}
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full {{ $isEnterprise ? 'bg-amber-100' : ($isPro ? 'bg-indigo-100' : 'bg-gray-100') }} flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 {{ $isEnterprise ? 'text-amber-600' : ($isPro ? 'text-indigo-600' : 'text-gray-500') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">{{ $plan->max_sites == -1 ? 'Unlimited' : $plan->max_sites }} {{ Str::plural('site', $plan->max_sites == -1 ? 2 : $plan->max_sites) }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full {{ $isEnterprise ? 'bg-amber-100' : ($isPro ? 'bg-indigo-100' : 'bg-gray-100') }} flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 {{ $isEnterprise ? 'text-amber-600' : ($isPro ? 'text-indigo-600' : 'text-gray-500') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">{{ $plan->max_pages_per_site == -1 ? 'Unlimited' : number_format($plan->max_pages_per_site) }} pages/site</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full {{ ($plan->max_ai_credits_monthly > 0 || $plan->max_ai_credits_monthly == -1) ? ($isEnterprise ? 'bg-amber-100' : 'bg-indigo-100') : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                                    @if($plan->max_ai_credits_monthly > 0 || $plan->max_ai_credits_monthly == -1)
                                        <svg class="w-3 h-3 {{ $isEnterprise ? 'text-amber-600' : 'text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @endif
                                </div>
                                <span class="text-sm {{ ($plan->max_ai_credits_monthly > 0 || $plan->max_ai_credits_monthly == -1) ? 'text-gray-700 font-medium' : 'text-gray-400 line-through' }}">
                                    {{ $plan->max_ai_credits_monthly == -1 ? 'Unlimited' : ($plan->max_ai_credits_monthly == 0 ? 'No' : number_format($plan->max_ai_credits_monthly)) }} AI credits/mo
                                </span>
                            </li>

                            {{-- Feature flags --}}
                            @if($plan->features)
                                <li class="pt-2 border-t border-gray-100"></li>
                                @foreach($plan->features as $feature => $enabled)
                                    <li class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full {{ $enabled ? ($isEnterprise ? 'bg-amber-100' : ($isPro ? 'bg-indigo-100' : 'bg-green-100')) : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                                            @if($enabled)
                                                <svg class="w-3 h-3 {{ $isEnterprise ? 'text-amber-600' : ($isPro ? 'text-indigo-600' : 'text-green-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            @endif
                                        </div>
                                        <span class="text-sm {{ $enabled ? 'text-gray-700' : 'text-gray-400' }}">
                                            {{ str_replace('_', ' ', ucfirst($feature)) }}
                                        </span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    {{-- Action Button --}}
                    <div class="p-6 pt-0">
                        @if($isCurrent)
                            <button disabled
                                class="w-full px-4 py-3 rounded-xl text-sm font-semibold border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                                Current Plan
                            </button>
                        @elseif($isUpgrade)
                            <button wire:click="selectPlan({{ $plan->id }})"
                                    wire:loading.attr="disabled"
                                    class="w-full px-4 py-3 rounded-xl text-sm font-bold text-white transition-all duration-200 hover:scale-[1.02] hover:shadow-lg active:scale-[0.98]
                                        {{ $isEnterprise ? 'bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 shadow-amber-200' : 'bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 shadow-indigo-200' }}">
                                <span wire:loading.remove wire:target="selectPlan({{ $plan->id }})">
                                    Upgrade to {{ $plan->name }}
                                </span>
                                <span wire:loading wire:target="selectPlan({{ $plan->id }})" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Processing...
                                </span>
                            </button>
                        @else
                            <button wire:click="selectPlan({{ $plan->id }})"
                                    wire:loading.attr="disabled"
                                    wire:confirm="Are you sure you want to downgrade? You may lose access to some features."
                                    class="w-full px-4 py-3 rounded-xl text-sm font-semibold border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                                <span wire:loading.remove wire:target="selectPlan({{ $plan->id }})">
                                    Downgrade to {{ $plan->name }}
                                </span>
                                <span wire:loading wire:target="selectPlan({{ $plan->id }})" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Processing...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- FAQ Section --}}
    <div class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-8" x-data="{ openFaq: null }">
        <h3 class="text-lg font-bold text-gray-900 mb-6 text-center">Frequently Asked Questions</h3>
        <div class="max-w-3xl mx-auto divide-y divide-gray-100">
            @php
                $faqs = [
                    ['q' => 'Can I change plans at any time?', 'a' => 'Yes! You can upgrade or downgrade your plan at any time. When upgrading, you\'ll be charged the prorated difference. When downgrading, the new rate applies at your next billing cycle.'],
                    ['q' => 'What happens to my sites if I downgrade?', 'a' => 'Your existing sites and pages remain intact. However, you won\'t be able to create new sites or pages beyond your new plan\'s limits until you upgrade again.'],
                    ['q' => 'Is there a money-back guarantee?', 'a' => 'Absolutely! We offer a 30-day money-back guarantee on all paid plans. If you\'re not satisfied, contact us for a full refund.'],
                    ['q' => 'How do AI credits work?', 'a' => 'AI credits are used for AI-powered content generation. Each credit generates approximately one page of content. Credits reset monthly on your billing date.'],
                    ['q' => 'Can I cancel my subscription?', 'a' => 'Yes, you can cancel anytime from your billing settings. You\'ll continue to have access to your plan features until the end of your current billing period.'],
                ];
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="py-4">
                    <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                            class="flex items-center justify-between w-full text-left">
                        <span class="text-sm font-medium text-gray-900">{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                             :class="openFaq === {{ $index }} ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === {{ $index }}"
                         x-collapse
                         x-cloak>
                        <p class="text-sm text-gray-500 mt-3 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
