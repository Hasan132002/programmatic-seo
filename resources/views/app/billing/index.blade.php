<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Billing & Subscription</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your plan, subscription, and payment details</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-full
                    {{ $currentPlan?->slug === 'enterprise' ? 'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200' :
                       ($currentPlan?->slug === 'pro' ? 'bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 border border-indigo-200' :
                       'bg-gray-100 text-gray-600 border border-gray-200') }}">
                    {{ $currentPlan?->name ?? 'Free' }} Plan
                </span>
            </div>
        </div>
    </x-slot>

    {{-- Billing Page Styles --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 1; }
            50% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(0.95); opacity: 1; }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .gradient-animated {
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }
        .shimmer-bg {
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.1) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Subscription Manager (current plan + usage) --}}
            <div class="animate-fade-in-up">
                <livewire:app.billing.subscription-manager />
            </div>

            {{-- Plan Selector (pricing cards) --}}
            <div class="animate-fade-in-up delay-200">
                <livewire:app.billing.plan-selector />
            </div>

        </div>
    </div>
</x-app-layout>
