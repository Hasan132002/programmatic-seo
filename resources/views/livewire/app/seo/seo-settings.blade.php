<div>
    {{-- Success Notification --}}
    @if($saved)
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => { show = false; $wire.set('saved', false) }, 3000)"
            x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center shadow-sm"
        >
            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">SEO settings saved successfully.</span>
        </div>
    @endif

    <form wire:submit="save" class="space-y-8">
        {{-- Section: General SEO --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">General SEO</h3>
                </div>
                <p class="text-sm text-gray-500 mt-1 ml-7">Configure default title and description templates for your pages.</p>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label for="defaultMetaTitle" class="block text-sm font-medium text-gray-700 mb-1">Default Meta Title Template</label>
                    <input
                        type="text"
                        id="defaultMetaTitle"
                        wire:model="defaultMetaTitle"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="{page_title} | {site_name}"
                    >
                    <p class="mt-1.5 text-xs text-gray-400">Available variables: <code class="bg-gray-100 px-1 py-0.5 rounded text-indigo-600">{page_title}</code>, <code class="bg-gray-100 px-1 py-0.5 rounded text-indigo-600">{site_name}</code>, <code class="bg-gray-100 px-1 py-0.5 rounded text-indigo-600">{niche}</code></p>
                    @error('defaultMetaTitle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="defaultMetaDescription" class="block text-sm font-medium text-gray-700 mb-1">Default Meta Description Template</label>
                    <textarea
                        id="defaultMetaDescription"
                        wire:model="defaultMetaDescription"
                        rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="Learn about {page_title}. Comprehensive guide and resources on {site_name}."
                    ></textarea>
                    <p class="mt-1.5 text-xs text-gray-400">Max 160 characters recommended for search engine display.</p>
                    @error('defaultMetaDescription') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="canonicalBase" class="block text-sm font-medium text-gray-700 mb-1">Canonical Base URL</label>
                    <input
                        type="url"
                        id="canonicalBase"
                        wire:model="canonicalBase"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="https://example.com"
                    >
                    <p class="mt-1.5 text-xs text-gray-400">The base URL used for generating canonical URLs for all pages.</p>
                    @error('canonicalBase') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section: Social Media --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Social Media</h3>
                </div>
                <p class="text-sm text-gray-500 mt-1 ml-7">Configure Open Graph and Twitter Card defaults.</p>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Default OG Image</label>
                    <div class="flex items-start space-x-4">
                        @if($existingOgImage)
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $existingOgImage) }}" alt="Current OG Image" class="w-32 h-20 object-cover rounded-md border border-gray-200">
                            </div>
                        @endif
                        <div class="flex-1">
                            <input
                                type="file"
                                wire:model="ogImage"
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer"
                            >
                            <p class="mt-1.5 text-xs text-gray-400">Recommended: 1200x630px. Max 2MB. JPG, PNG, or WebP.</p>
                            @error('ogImage') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            <div wire:loading wire:target="ogImage" class="mt-2">
                                <div class="flex items-center text-xs text-indigo-600">
                                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Uploading...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="twitterHandle" class="block text-sm font-medium text-gray-700 mb-1">Twitter Handle</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">@</span>
                        <input
                            type="text"
                            id="twitterHandle"
                            wire:model="twitterHandle"
                            class="w-full pl-8 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="yourbrand"
                        >
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400">Used for Twitter Card attribution.</p>
                    @error('twitterHandle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section: Analytics --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Analytics & Verification</h3>
                </div>
                <p class="text-sm text-gray-500 mt-1 ml-7">Connect your site with Google Analytics and Search Console.</p>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label for="googleAnalyticsId" class="block text-sm font-medium text-gray-700 mb-1">Google Analytics Measurement ID</label>
                    <input
                        type="text"
                        id="googleAnalyticsId"
                        wire:model="googleAnalyticsId"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"
                        placeholder="G-XXXXXXXXXX"
                    >
                    <p class="mt-1.5 text-xs text-gray-400">Your GA4 Measurement ID (starts with G-).</p>
                    @error('googleAnalyticsId') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="googleSearchConsoleId" class="block text-sm font-medium text-gray-700 mb-1">Google Search Console Verification</label>
                    <input
                        type="text"
                        id="googleSearchConsoleId"
                        wire:model="googleSearchConsoleId"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"
                        placeholder="google-site-verification=xxxxxxxx"
                    >
                    <p class="mt-1.5 text-xs text-gray-400">The full meta tag content value from Google Search Console.</p>
                    @error('googleSearchConsoleId') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section: Robots.txt --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Robots.txt</h3>
                    </div>
                    <button
                        type="button"
                        wire:click="resetRobotsTxt"
                        class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition"
                    >
                        Reset to Default
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-1 ml-7">Control how search engines crawl your site.</p>
            </div>
            <div class="p-6">
                <div>
                    <textarea
                        id="robotsTxt"
                        wire:model="robotsTxt"
                        rows="8"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono bg-gray-900 text-green-400 p-4"
                        spellcheck="false"
                    ></textarea>
                    @error('robotsTxt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4 bg-gray-50 rounded-md p-4 border border-gray-200">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Preview</p>
                    <pre class="text-xs text-gray-700 font-mono whitespace-pre-wrap">{{ $this->robotsTxtPreview }}</pre>
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex items-center justify-end space-x-3">
            <div wire:loading wire:target="save" class="flex items-center text-sm text-indigo-600">
                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Saving...
            </div>
            <button
                type="submit"
                class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-wide hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Save SEO Settings
            </button>
        </div>
    </form>
</div>
