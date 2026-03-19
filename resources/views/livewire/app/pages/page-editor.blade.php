<div x-data="{
        mode: 'visual',
        contentHtml: @entangle('content_html'),
        metaTitleLen: 0,
        metaDescLen: 0,
        initEditor() {
            this.$nextTick(() => {
                const editor = this.$refs.visualEditor;
                if (editor) {
                    editor.innerHTML = this.contentHtml || '';
                    this.metaTitleLen = (this.$refs.metaTitleInput?.value || '').length;
                    this.metaDescLen = (this.$refs.metaDescInput?.value || '').length;
                }
            });
        },
        syncFromVisual() {
            const editor = this.$refs.visualEditor;
            if (editor) {
                this.contentHtml = editor.innerHTML;
                this.$wire.set('content_html', editor.innerHTML);
            }
        },
        syncToVisual() {
            const editor = this.$refs.visualEditor;
            if (editor) {
                editor.innerHTML = this.contentHtml || '';
            }
        },
        execCmd(command, value = null) {
            document.execCommand(command, false, value);
            this.$refs.visualEditor.focus();
            this.syncFromVisual();
        },
        insertLink() {
            const url = prompt('Enter URL:');
            if (url) {
                document.execCommand('createLink', false, url);
                this.$refs.visualEditor.focus();
                this.syncFromVisual();
            }
        },
        switchMode(newMode) {
            if (newMode === 'html' && this.mode === 'visual') {
                this.syncFromVisual();
            } else if (newMode === 'visual' && this.mode === 'html') {
                this.syncToVisual();
            }
            this.mode = newMode;
        }
     }"
     x-init="initEditor()"
     @content-updated.window="contentHtml = $event.detail.html; syncToVisual(); metaTitleLen = ($refs.metaTitleInput?.value || '').length; metaDescLen = ($refs.metaDescInput?.value || '').length;"
>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
        }
        [x-ref="visualEditor"] {
            min-height: 400px;
            outline: none;
        }
        [x-ref="visualEditor"] h1 { font-size: 1.875rem; font-weight: 700; margin-bottom: 0.75rem; line-height: 1.3; }
        [x-ref="visualEditor"] h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.35; }
        [x-ref="visualEditor"] h3 { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.4; }
        [x-ref="visualEditor"] p { margin-bottom: 0.75rem; line-height: 1.7; }
        [x-ref="visualEditor"] ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 0.75rem; }
        [x-ref="visualEditor"] ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 0.75rem; }
        [x-ref="visualEditor"] li { margin-bottom: 0.25rem; line-height: 1.6; }
        [x-ref="visualEditor"] a { color: #4f46e5; text-decoration: underline; }
        [x-ref="visualEditor"] code { background: #f3f4f6; padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.875rem; font-family: monospace; }
        [x-ref="visualEditor"] pre { background: #1f2937; color: #e5e7eb; padding: 1rem; border-radius: 0.5rem; font-family: monospace; font-size: 0.875rem; overflow-x: auto; margin-bottom: 0.75rem; }
        [x-ref="visualEditor"] blockquote { border-left: 3px solid #e5e7eb; padding-left: 1rem; color: #6b7280; margin-bottom: 0.75rem; }
        .char-count-bar { height: 3px; border-radius: 2px; transition: all 0.3s; }
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

    <form wire:submit="save">
        <div class="animate-fade-in-up grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== MAIN EDITOR COLUMN ===== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Title Input --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Page Title</label>
                    <input wire:model.live.debounce.500ms="title"
                           type="text"
                           id="title"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm text-lg font-semibold text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                           placeholder="Enter your page title...">
                    @error('title') <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>{{ $message }}</p> @enderror
                </div>

                {{-- Slug Input --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">URL Slug</label>
                    <div class="flex items-center gap-0 border border-gray-200 rounded-xl overflow-hidden focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition-all duration-200">
                        <span class="inline-flex items-center px-4 py-3 bg-gray-50 text-sm text-gray-500 border-r border-gray-200 whitespace-nowrap">
                            {{ $site->domain ?? ($site->subdomain . '.' . config('pseo.platform_domain', 'localhost')) }}/
                        </span>
                        <input wire:model="slug"
                               type="text"
                               id="slug"
                               class="flex-1 px-4 py-3 border-0 text-sm text-gray-900 placeholder-gray-400 focus:ring-0"
                               placeholder="your-page-slug">
                    </div>
                    @error('slug') <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>{{ $message }}</p> @enderror
                </div>

                {{-- Content Editor --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Editor Mode Tabs + Toolbar --}}
                    <div class="border-b border-gray-100">
                        {{-- Mode Toggle --}}
                        <div class="flex items-center justify-between px-4 pt-3 pb-0">
                            <div class="flex gap-1 bg-gray-100 rounded-lg p-0.5">
                                <button type="button" @click="switchMode('visual')"
                                        :class="mode === 'visual' ? 'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                                        class="px-3.5 py-1.5 text-xs rounded-md transition-all duration-200">
                                    Visual
                                </button>
                                <button type="button" @click="switchMode('html')"
                                        :class="mode === 'html' ? 'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                                        class="px-3.5 py-1.5 text-xs rounded-md transition-all duration-200">
                                    HTML
                                </button>
                            </div>
                            <span class="text-xs text-gray-400">
                                <span x-show="mode === 'visual'">Visual Editor</span>
                                <span x-show="mode === 'html'" x-cloak>Source Code</span>
                            </span>
                        </div>

                        {{-- Toolbar (Visual Mode Only) --}}
                        <div x-show="mode === 'visual'" class="flex items-center gap-0.5 px-4 py-2 flex-wrap">
                            <button type="button" @click="execCmd('bold')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Bold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z" /></svg>
                            </button>
                            <button type="button" @click="execCmd('italic')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Italic">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
                            </button>
                            <button type="button" @click="execCmd('underline')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Underline">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"></path><line x1="4" y1="21" x2="20" y2="21"></line></svg>
                            </button>

                            <div class="w-px h-5 bg-gray-200 mx-1"></div>

                            <button type="button" @click="execCmd('formatBlock', '<h2>')"
                                    class="inline-flex items-center justify-center px-2 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors text-xs font-bold" title="Heading 2">
                                H2
                            </button>
                            <button type="button" @click="execCmd('formatBlock', '<h3>')"
                                    class="inline-flex items-center justify-center px-2 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors text-xs font-bold" title="Heading 3">
                                H3
                            </button>
                            <button type="button" @click="execCmd('formatBlock', '<p>')"
                                    class="inline-flex items-center justify-center px-2 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors text-xs font-medium" title="Paragraph">
                                P
                            </button>

                            <div class="w-px h-5 bg-gray-200 mx-1"></div>

                            <button type="button" @click="execCmd('insertUnorderedList')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Bullet List">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </button>
                            <button type="button" @click="execCmd('insertOrderedList')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Numbered List">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12" /><text x="3" y="8" font-size="6" fill="currentColor" stroke="none" font-weight="bold">1</text><text x="3" y="14" font-size="6" fill="currentColor" stroke="none" font-weight="bold">2</text><text x="3" y="19.5" font-size="6" fill="currentColor" stroke="none" font-weight="bold">3</text></svg>
                            </button>

                            <div class="w-px h-5 bg-gray-200 mx-1"></div>

                            <button type="button" @click="insertLink()"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Insert Link">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                            </button>
                            <button type="button" @click="execCmd('formatBlock', '<blockquote>')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Blockquote">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>
                            </button>
                            <button type="button" @click="execCmd('formatBlock', '<pre>')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Code Block">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>
                            </button>

                            <div class="w-px h-5 bg-gray-200 mx-1"></div>

                            <button type="button" @click="execCmd('removeFormat')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors ml-auto" title="Clear Formatting">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Visual Editor --}}
                    <div x-show="mode === 'visual'" class="p-6">
                        <div x-ref="visualEditor"
                             contenteditable="true"
                             @input="syncFromVisual()"
                             @blur="syncFromVisual()"
                             class="prose prose-sm max-w-none text-gray-800 focus:outline-none"
                             style="min-height: 400px; max-height: 600px; overflow-y: auto;">
                        </div>
                    </div>

                    {{-- HTML Source Editor --}}
                    <div x-show="mode === 'html'" x-cloak class="p-6">
                        <textarea wire:model.defer="content_html"
                                  x-model="contentHtml"
                                  rows="20"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm font-mono text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 resize-y"
                                  placeholder="<h2>Your HTML content...</h2>&#10;<p>Write your content here using HTML tags.</p>"
                                  style="min-height: 400px;"></textarea>
                    </div>

                    @error('content_html')
                        <div class="px-6 pb-4">
                            <p class="text-sm text-red-600 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>{{ $message }}</p>
                        </div>
                    @enderror
                </div>
            </div>

            {{-- ===== SIDEBAR ===== --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Status & Publish --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Publish Settings
                        </h4>
                    </div>
                    <div class="p-5">
                        <label for="status" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <div class="relative">
                            <select wire:model="status" id="status"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 appearance-none cursor-pointer">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO Settings --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            SEO Settings
                        </h4>
                    </div>
                    <div class="p-5 space-y-5">
                        {{-- Meta Title --}}
                        <div>
                            <label for="meta_title" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Meta Title</label>
                            <input wire:model="meta_title"
                                   x-ref="metaTitleInput"
                                   @input="metaTitleLen = $event.target.value.length"
                                   type="text"
                                   id="meta_title"
                                   class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                                   placeholder="Auto-generated from title if empty"
                                   maxlength="60">
                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex-1 bg-gray-100 rounded-full overflow-hidden h-1">
                                    <div class="char-count-bar h-full rounded-full"
                                         :class="metaTitleLen > 60 ? 'bg-red-500' : (metaTitleLen > 50 ? 'bg-amber-500' : 'bg-emerald-500')"
                                         :style="'width: ' + Math.min((metaTitleLen / 60) * 100, 100) + '%'"></div>
                                </div>
                                <span class="text-xs ml-2 tabular-nums"
                                      :class="metaTitleLen > 60 ? 'text-red-500 font-medium' : 'text-gray-400'"
                                      x-text="metaTitleLen + '/60'"></span>
                            </div>
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label for="meta_description" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Meta Description</label>
                            <textarea wire:model="meta_description"
                                      x-ref="metaDescInput"
                                      @input="metaDescLen = $event.target.value.length"
                                      id="meta_description"
                                      rows="3"
                                      class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 resize-none"
                                      placeholder="Auto-generated from content if empty"
                                      maxlength="160"></textarea>
                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex-1 bg-gray-100 rounded-full overflow-hidden h-1">
                                    <div class="char-count-bar h-full rounded-full"
                                         :class="metaDescLen > 160 ? 'bg-red-500' : (metaDescLen > 140 ? 'bg-amber-500' : 'bg-emerald-500')"
                                         :style="'width: ' + Math.min((metaDescLen / 160) * 100, 100) + '%'"></div>
                                </div>
                                <span class="text-xs ml-2 tabular-nums"
                                      :class="metaDescLen > 160 ? 'text-red-500 font-medium' : 'text-gray-400'"
                                      x-text="metaDescLen + '/160'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- AI Content Generator --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
                     x-data="{ showCustomPrompt: false }">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-purple-50">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z" />
                            </svg>
                            AI Content Generator
                        </h4>
                    </div>
                    <div class="p-5 space-y-4">
                        {{-- AI Error --}}
                        @if($aiError)
                            <div class="flex items-start gap-2 p-3 bg-red-50 border border-red-100 rounded-xl text-xs text-red-700">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                                <span>{{ $aiError }}</span>
                            </div>
                        @endif

                        {{-- Content Type --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Generate</label>
                            <select wire:model="aiContentType"
                                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all duration-200 appearance-none cursor-pointer"
                                    style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; padding-right: 2.5rem;">
                                <option value="full">Full Page Content</option>
                                <option value="intro">Introduction Only</option>
                                <option value="faq">FAQ Section</option>
                                <option value="features">Features / Benefits</option>
                                <option value="comparison_table">Comparison Table</option>
                                <option value="conclusion">Conclusion</option>
                            </select>
                        </div>

                        {{-- Custom Prompt Toggle --}}
                        <div>
                            <button type="button" @click="showCustomPrompt = !showCustomPrompt"
                                    class="flex items-center gap-1.5 text-xs font-medium text-violet-600 hover:text-violet-700 transition-colors">
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="showCustomPrompt && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                Custom Instructions
                            </button>
                            <div x-show="showCustomPrompt" x-collapse class="mt-2">
                                <textarea wire:model="aiCustomPrompt"
                                          rows="3"
                                          class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all duration-200 resize-none"
                                          placeholder="e.g. Focus on pricing, include local statistics, mention competitors..."></textarea>
                            </div>
                        </div>

                        {{-- Generate Content Button --}}
                        <button type="button"
                                wire:click="generateContent"
                                wire:loading.attr="disabled"
                                wire:target="generateContent"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200 disabled:opacity-60 disabled:cursor-wait">
                            <span wire:loading.remove wire:target="generateContent">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                            </span>
                            <span wire:loading wire:target="generateContent">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            </span>
                            <span wire:loading.remove wire:target="generateContent">Generate Content</span>
                            <span wire:loading wire:target="generateContent">Generating...</span>
                        </button>

                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-xs text-gray-400 mb-3">SEO Meta Tags</p>
                            {{-- Generate SEO Meta Button --}}
                            <button type="button"
                                    wire:click="generateMetaTags"
                                    wire:loading.attr="disabled"
                                    wire:target="generateMetaTags"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-violet-700 bg-white border border-violet-200 rounded-xl hover:bg-violet-50 hover:border-violet-300 transition-all duration-200 disabled:opacity-60 disabled:cursor-wait">
                                <span wire:loading.remove wire:target="generateMetaTags">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                </span>
                                <span wire:loading wire:target="generateMetaTags">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                </span>
                                <span wire:loading.remove wire:target="generateMetaTags">Generate Meta Tags</span>
                                <span wire:loading wire:target="generateMetaTags">Generating...</span>
                            </button>
                        </div>

                        <p class="text-[10px] text-gray-400 leading-relaxed">
                            Uses AI to generate SEO-optimized content based on page title and site niche ({{ $site->niche_type->value }}).
                        </p>
                    </div>
                </div>

                {{-- View Published Page --}}
                @if($isEdit && $page && $status === 'published')
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200 p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Published</h4>
                                <p class="text-xs text-gray-500">This page is live</p>
                            </div>
                        </div>
                        <a href="{{ $site->url }}/{{ $slug }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 text-sm font-medium text-emerald-700 bg-white border border-emerald-200 rounded-xl hover:bg-emerald-50 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            View Published Page
                        </a>
                    </div>
                @endif

                {{-- Page Builder Link --}}
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">Visual Builder</h4>
                            <p class="text-xs text-gray-500">Drag-and-drop page editor</p>
                        </div>
                    </div>
                    @if($isEdit && $page)
                        <a href="{{ route('app.sites.builder.edit', [$site, $page]) }}"
                           class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 text-sm font-medium text-indigo-700 bg-white border border-indigo-200 rounded-xl hover:bg-indigo-50 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            Open in Visual Builder
                        </a>
                    @else
                        <a href="{{ route('app.sites.builder.create', [$site]) }}"
                           class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 text-sm font-medium text-indigo-700 bg-white border border-indigo-200 rounded-xl hover:bg-indigo-50 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            Open Visual Builder
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== STICKY BOTTOM BAR ===== --}}
        <div class="sticky bottom-0 mt-6 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 bg-white/80 backdrop-blur-lg border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgb(0,0,0,0.05)] z-10">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <a href="{{ route('app.sites.show', $site) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                    Cancel
                </a>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400 hidden sm:inline" wire:loading.remove wire:target="save">
                        @if($isEdit)
                            Last saved: {{ $page?->updated_at?->diffForHumans() ?? 'Never' }}
                        @else
                            New page
                        @endif
                    </span>
                    <span class="text-xs text-indigo-600 font-medium hidden sm:inline" wire:loading wire:target="save">
                        <svg class="w-3.5 h-3.5 inline-block animate-spin mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        Saving...
                    </span>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:from-indigo-700 hover:to-purple-700 transform hover:-translate-y-0.5 transition-all duration-200"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75 cursor-wait"
                            wire:target="save">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        {{ $isEdit ? 'Save Changes' : 'Create Page' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
