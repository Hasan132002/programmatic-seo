<div>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
        }
    </style>

    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    @if(session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mb-6 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    <div class="animate-fade-in-up bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Edit Site Settings</h3>
                    <p class="text-sm text-gray-500">Update your site's name, niche type, and domain settings.</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="p-8 space-y-8">
            {{-- Site Name --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Site Name</label>
                <input wire:model="name"
                       type="text"
                       id="name"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                       placeholder="e.g., Best Restaurants Guide">
                @error('name') <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Niche Type Selector --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Niche Type</label>
                <p class="text-sm text-gray-400 mb-4">Choose the type that best matches your content strategy.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- City / Location --}}
                    <label class="relative cursor-pointer group">
                        <input wire:model="niche_type" type="radio" name="niche_type" value="city" class="sr-only peer">
                        <div class="p-5 border-2 rounded-2xl transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50/50 peer-checked:shadow-lg peer-checked:shadow-blue-500/10 border-gray-200 hover:border-gray-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/25">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">City / Location</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">Geo-targeted content for cities and locations.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    {{-- Comparison --}}
                    <label class="relative cursor-pointer group">
                        <input wire:model="niche_type" type="radio" name="niche_type" value="comparison" class="sr-only peer">
                        <div class="p-5 border-2 rounded-2xl transition-all duration-200 peer-checked:border-amber-500 peer-checked:bg-amber-50/50 peer-checked:shadow-lg peer-checked:shadow-amber-500/10 border-gray-200 hover:border-gray-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Comparison</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">"X vs Y" pages for products and tools.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    {{-- Directory --}}
                    <label class="relative cursor-pointer group">
                        <input wire:model="niche_type" type="radio" name="niche_type" value="directory" class="sr-only peer">
                        <div class="p-5 border-2 rounded-2xl transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 peer-checked:shadow-lg peer-checked:shadow-emerald-500/10 border-gray-200 hover:border-gray-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/25">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Directory</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">Listings and curated resource databases.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    {{-- Custom --}}
                    <label class="relative cursor-pointer group">
                        <input wire:model="niche_type" type="radio" name="niche_type" value="custom" class="sr-only peer">
                        <div class="p-5 border-2 rounded-2xl transition-all duration-200 peer-checked:border-violet-500 peer-checked:bg-violet-50/50 peer-checked:shadow-lg peer-checked:shadow-violet-500/10 border-gray-200 hover:border-gray-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-violet-500/25">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Custom</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">Full flexibility with custom templates.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                @error('niche_type') <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Custom Domain --}}
            <div>
                <label for="domain" class="block text-sm font-semibold text-gray-700 mb-2">Custom Domain</label>
                <p class="text-xs text-gray-400 mb-3">Optional. Point your domain's A/CNAME record to our server, then enter it here.</p>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                    </div>
                    <input wire:model="domain"
                           type="text"
                           id="domain"
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                           placeholder="e.g., example.com">
                </div>
                @error('domain') <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Image Generation Settings --}}
            <div class="pt-6 border-t border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-pink-500/25">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">AI Image Generation</h3>
                        <p class="text-xs text-gray-500">Auto-generate images for your pages during content creation.</p>
                    </div>
                </div>

                {{-- Provider Selection --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Image Provider</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- Pollinations (Free) --}}
                        <label class="relative cursor-pointer">
                            <input wire:model.live="image_provider" type="radio" name="image_provider" value="pollinations" class="sr-only peer">
                            <div class="p-4 border-2 rounded-xl transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 border-gray-200 hover:border-gray-300">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-sm text-gray-900">Pollinations AI</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full uppercase">Free</span>
                                </div>
                                <p class="text-xs text-gray-500">No API key needed. Good quality, unlimited generations.</p>
                            </div>
                        </label>

                        {{-- OpenAI DALL-E (Paid) --}}
                        <label class="relative cursor-pointer">
                            <input wire:model.live="image_provider" type="radio" name="image_provider" value="openai" class="sr-only peer">
                            <div class="p-4 border-2 rounded-xl transition-all duration-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50/50 border-gray-200 hover:border-gray-300">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-sm text-gray-900">DALL-E (OpenAI)</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full uppercase">Paid</span>
                                </div>
                                <p class="text-xs text-gray-500">Premium quality via DALL-E 3. Requires OpenAI API key.</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- OpenAI API Key (shown when openai selected) --}}
                @if($image_provider === 'openai')
                <div class="space-y-4 p-4 bg-indigo-50/50 border border-indigo-100 rounded-xl mb-5">
                    <div>
                        <label for="image_api_key" class="block text-sm font-semibold text-gray-700 mb-1.5">OpenAI API Key</label>
                        <input wire:model="image_api_key"
                               type="password"
                               id="image_api_key"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg shadow-sm text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                               placeholder="sk-...">
                        <p class="mt-1 text-xs text-gray-400">Your key is stored encrypted in site settings. Get one at <a href="https://platform.openai.com/api-keys" target="_blank" class="text-indigo-600 hover:underline">platform.openai.com</a></p>
                        @error('image_api_key') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="image_model" class="block text-xs font-semibold text-gray-600 mb-1.5">Model</label>
                            <select wire:model="image_model" id="image_model"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                                <option value="dall-e-3">DALL-E 3 (Best)</option>
                                <option value="dall-e-2">DALL-E 2 (Cheaper)</option>
                            </select>
                        </div>
                        <div>
                            <label for="image_style" class="block text-xs font-semibold text-gray-600 mb-1.5">Style</label>
                            <select wire:model="image_style" id="image_style"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                                <option value="natural">Natural (Realistic)</option>
                                <option value="vivid">Vivid (Creative)</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Subdomain Info --}}
            <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-xl border border-gray-100">
                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
                <div>
                    <span class="text-xs font-medium text-gray-500">Default Subdomain</span>
                    <p class="text-sm font-mono font-medium text-indigo-600">{{ $site->subdomain }}.{{ config('pseo.platform_domain', 'localhost') }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('app.sites.show', $site) }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    Back to Site
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:from-indigo-700 hover:to-purple-700 transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
