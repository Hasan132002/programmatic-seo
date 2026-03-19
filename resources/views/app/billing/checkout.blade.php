<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('app.billing') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Checkout</h2>
                <p class="text-sm text-gray-500 mt-1">Complete your subscription to {{ $plan->name }}</p>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .gradient-animated {
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- Order Summary --}}
                <div class="lg:col-span-2 animate-fade-in-up">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        {{-- Plan Header --}}
                        <div class="p-6 {{ $plan->slug === 'enterprise' ? 'bg-gradient-to-br from-amber-500 to-orange-600' : 'bg-gradient-to-br from-indigo-500 to-purple-600' }} gradient-animated text-white">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    @if($plan->slug === 'enterprise')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    @else
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">{{ $plan->name }} Plan</h3>
                                    <p class="text-sm text-white/80">Programmatic SEO</p>
                                </div>
                            </div>

                            @php
                                $isYearly = request('billing') === 'yearly';
                                $price = $isYearly ? $plan->price_yearly : $plan->price_monthly;
                                $period = $isYearly ? 'year' : 'month';
                            @endphp

                            <div class="mt-4">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-extrabold">${{ number_format($price, 2) }}</span>
                                    <span class="text-white/70 text-sm">/{{ $period }}</span>
                                </div>
                                @if($isYearly)
                                    <p class="text-sm text-white/70 mt-1">
                                        Save ${{ number_format(($plan->price_monthly * 12) - $plan->price_yearly, 2) }}/year
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Features --}}
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">What's included</h4>
                            <ul class="space-y-3">
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-sm text-gray-700">{{ $plan->max_sites == -1 ? 'Unlimited' : $plan->max_sites }} sites</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-sm text-gray-700">{{ $plan->max_pages_per_site == -1 ? 'Unlimited' : number_format($plan->max_pages_per_site) }} pages per site</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-sm text-gray-700">{{ $plan->max_ai_credits_monthly == -1 ? 'Unlimited' : number_format($plan->max_ai_credits_monthly) }} AI credits/mo</span>
                                </li>
                                @if($plan->features)
                                    @foreach($plan->features as $feature => $enabled)
                                        @if($enabled)
                                            <li class="flex items-center gap-3">
                                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <span class="text-sm text-gray-700">{{ str_replace('_', ' ', ucfirst($feature)) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        {{-- Order Total --}}
                        <div class="border-t border-gray-100 p-6 bg-gray-50">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">{{ $plan->name }} Plan ({{ $isYearly ? 'Yearly' : 'Monthly' }})</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($price, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                <span class="text-base font-semibold text-gray-900">Total due today</span>
                                <span class="text-lg font-bold text-gray-900">${{ number_format($price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Section --}}
                <div class="lg:col-span-3 animate-fade-in-up delay-100">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Payment Details</h3>
                            <p class="text-sm text-gray-500 mt-1">You'll be redirected to Stripe's secure checkout</p>
                        </div>

                        <div class="p-6">
                            {{-- Secure Payment Info --}}
                            <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100 mb-6">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Secured by Stripe</p>
                                    <p class="text-xs text-blue-700">Your payment information is encrypted and secure. We never store your card details.</p>
                                </div>
                            </div>

                            {{-- Account Info --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account</label>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Billing Period Toggle --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Billing Period</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="{{ route('app.billing.checkout', $plan) }}"
                                       class="flex items-center justify-center gap-2 p-3 rounded-lg border-2 transition-all duration-200 {{ !$isYearly ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}">
                                        <span class="text-sm font-medium">Monthly</span>
                                        <span class="text-sm font-bold">${{ number_format($plan->price_monthly, 2) }}</span>
                                    </a>
                                    <a href="{{ route('app.billing.checkout', ['plan' => $plan, 'billing' => 'yearly']) }}"
                                       class="relative flex items-center justify-center gap-2 p-3 rounded-lg border-2 transition-all duration-200 {{ $isYearly ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}">
                                        <span class="text-sm font-medium">Yearly</span>
                                        <span class="text-sm font-bold">${{ number_format($plan->price_yearly, 2) }}</span>
                                        @if($plan->price_yearly > 0 && $plan->price_monthly > 0)
                                            <span class="absolute -top-2 -right-2 px-2 py-0.5 text-[10px] font-bold bg-green-500 text-white rounded-full">
                                                SAVE {{ round(100 - ($plan->price_yearly / ($plan->price_monthly * 12)) * 100) }}%
                                            </span>
                                        @endif
                                    </a>
                                </div>
                            </div>

                            {{-- Checkout Button --}}
                            <form action="{{ route('app.billing.checkout', $plan) }}" method="GET">
                                @if($isYearly)
                                    <input type="hidden" name="billing" value="yearly">
                                @endif

                                {{-- In a real Stripe implementation, this would create a Stripe Checkout session --}}
                                <button type="submit"
                                    onclick="event.preventDefault(); alert('Stripe Checkout integration: In production, this creates a Stripe Checkout Session. Configure your STRIPE_KEY and STRIPE_SECRET in .env, and set stripe_price_id on each plan.');"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-4 rounded-xl text-white font-bold text-base transition-all duration-200 hover:scale-[1.02] hover:shadow-lg active:scale-[0.98] {{ $plan->slug === 'enterprise' ? 'bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700' : 'bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Subscribe to {{ $plan->name }} - ${{ number_format($price, 2) }}/{{ $period }}
                                </button>
                            </form>

                            {{-- Terms --}}
                            <p class="text-xs text-gray-400 text-center mt-4 leading-relaxed">
                                By subscribing, you agree to our Terms of Service and Privacy Policy.
                                You can cancel anytime from your billing settings. {{ $isYearly ? 'Yearly' : 'Monthly' }} billing
                                will renew automatically.
                            </p>
                        </div>

                        {{-- Payment Methods Accepted --}}
                        <div class="border-t border-gray-100 p-4 bg-gray-50 flex items-center justify-center gap-4">
                            <span class="text-xs text-gray-400">Accepted:</span>
                            <div class="flex items-center gap-2">
                                <div class="px-2 py-1 bg-white rounded border border-gray-200 text-xs font-bold text-gray-600">VISA</div>
                                <div class="px-2 py-1 bg-white rounded border border-gray-200 text-xs font-bold text-gray-600">MC</div>
                                <div class="px-2 py-1 bg-white rounded border border-gray-200 text-xs font-bold text-gray-600">AMEX</div>
                                <div class="px-2 py-1 bg-white rounded border border-gray-200 text-xs font-bold text-gray-600">DISCOVER</div>
                            </div>
                        </div>
                    </div>

                    {{-- Money Back Guarantee --}}
                    <div class="mt-4 flex items-center justify-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>30-day money-back guarantee. No questions asked.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
