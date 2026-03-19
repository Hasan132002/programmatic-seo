<div
    x-data="{
        editor: null,
        activePanel: 'blocks',
        showSeoPanel: false,
        saving: false,
        previewMode: false,
        init() {
            this.$nextTick(() => {
                if (typeof window.initGrapesJS !== 'function') {
                    console.error('GrapesJS builder script not loaded. Ensure @@vite directive for resources/js/builder.js is included.');
                    return;
                }

                this.editor = window.initGrapesJS('gjs-editor', {
                    customBlocks: @js($blocks->map(fn($b) => [
                        'id' => 'db-' . $b->id,
                        'name' => $b->name,
                        'category' => $b->category ?? 'Custom',
                        'content' => is_array($b->component_json) ? json_encode($b->component_json) : ($b->component_json ?? ''),
                    ]))
                });

                // Load existing content if editing
                const existingJson = @js($jsonContent);
                const existingHtml = @js($htmlContent);

                if (existingJson) {
                    try {
                        this.editor.loadProjectData(JSON.parse(existingJson));
                    } catch (e) {
                        console.warn('Failed to load project JSON, falling back to HTML:', e);
                        if (existingHtml) {
                            this.editor.setComponents(existingHtml);
                        }
                    }
                } else if (existingHtml) {
                    this.editor.setComponents(existingHtml);
                }

                // Listen for Ctrl+S save shortcut
                document.addEventListener('gjs:save', (e) => {
                    this.save();
                });

                // Add device buttons functionality
                this.editor.on('load', () => {
                    this.editor.runCommand('sw-visibility');
                });
            });
        },
        setDevice(device) {
            if (this.editor) {
                this.editor.setDevice(device);
            }
        },
        togglePreview() {
            if (this.editor) {
                this.previewMode = !this.previewMode;
                if (this.previewMode) {
                    this.editor.runCommand('preview');
                } else {
                    this.editor.stopCommand('preview');
                }
            }
        },
        undo() {
            if (this.editor) {
                this.editor.UndoManager.undo();
            }
        },
        redo() {
            if (this.editor) {
                this.editor.UndoManager.redo();
            }
        },
        clearCanvas() {
            if (this.editor && confirm('Are you sure you want to clear the entire canvas? This cannot be undone.')) {
                this.editor.DomComponents.clear();
                this.editor.CssComposer.clear();
            }
        },
        save() {
            if (!this.editor) return;

            this.saving = true;

            const html = this.editor.getHtml();
            const css = this.editor.getCss();
            const json = JSON.stringify(this.editor.getProjectData());

            $wire.saveFromBuilder(html, css, json).then(() => {
                this.saving = false;
            }).catch(() => {
                this.saving = false;
            });
        }
    }"
    class="flex flex-col h-full"
    wire:ignore.self
>
    {{-- Top Toolbar --}}
    <div class="bg-gray-900 text-white px-4 py-2 flex items-center justify-between gap-4 flex-shrink-0 z-50 border-b border-gray-700">
        {{-- Left: Back + Title --}}
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <a href="{{ route('app.sites.pages.index', $site) }}"
               class="flex items-center gap-1.5 text-gray-400 hover:text-white transition text-sm flex-shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>

            <div class="h-5 w-px bg-gray-700 flex-shrink-0"></div>

            <input
                type="text"
                wire:model.blur="title"
                placeholder="Page Title"
                class="bg-gray-800 border border-gray-700 rounded-md px-3 py-1.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 w-full max-w-xs"
            />
            <div class="flex items-center gap-1 text-gray-500 text-sm flex-shrink-0">
                <span>/</span>
                <input
                    type="text"
                    wire:model.blur="slug"
                    placeholder="page-slug"
                    class="bg-gray-800 border border-gray-700 rounded-md px-2 py-1.5 text-sm text-gray-300 placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 w-36"
                />
            </div>
        </div>

        {{-- Center: Device Buttons + Undo/Redo --}}
        <div class="flex items-center gap-1 flex-shrink-0">
            <button @click="undo()" class="p-1.5 rounded hover:bg-gray-700 text-gray-400 hover:text-white transition" title="Undo (Ctrl+Z)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a5 5 0 015 5v2M3 10l4-4M3 10l4 4" />
                </svg>
            </button>
            <button @click="redo()" class="p-1.5 rounded hover:bg-gray-700 text-gray-400 hover:text-white transition" title="Redo (Ctrl+Shift+Z)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 10H11a5 5 0 00-5 5v2M21 10l-4-4M21 10l-4 4" />
                </svg>
            </button>

            <div class="h-5 w-px bg-gray-700 mx-1"></div>

            <button @click="setDevice('Desktop')" class="p-1.5 rounded hover:bg-gray-700 text-gray-400 hover:text-white transition" title="Desktop">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>
            <button @click="setDevice('Tablet')" class="p-1.5 rounded hover:bg-gray-700 text-gray-400 hover:text-white transition" title="Tablet">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </button>
            <button @click="setDevice('Mobile')" class="p-1.5 rounded hover:bg-gray-700 text-gray-400 hover:text-white transition" title="Mobile">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </button>

            <div class="h-5 w-px bg-gray-700 mx-1"></div>

            <button @click="togglePreview()" class="p-1.5 rounded hover:bg-gray-700 transition" :class="previewMode ? 'text-indigo-400' : 'text-gray-400 hover:text-white'" title="Preview">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>

            <button @click="clearCanvas()" class="p-1.5 rounded hover:bg-gray-700 text-gray-400 hover:text-red-400 transition" title="Clear Canvas">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>

        {{-- Right: SEO + Status + Save --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            <button @click="showSeoPanel = !showSeoPanel"
                    class="px-3 py-1.5 rounded-md text-sm transition"
                    :class="showSeoPanel ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 border border-gray-700'">
                SEO
            </button>

            <select wire:model="status" class="bg-gray-800 border border-gray-700 rounded-md px-2 py-1.5 text-sm text-gray-300 focus:outline-none focus:border-indigo-500">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>

            <button @click="save()"
                    :disabled="saving"
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-md text-sm font-semibold transition flex items-center gap-2">
                <svg x-show="saving" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="saving ? 'Saving...' : 'Save Page'">Save Page</span>
            </button>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-900/50 border-b border-red-700 px-4 py-2">
            <ul class="text-red-300 text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Layout: Left Sidebar + Canvas + Right Panel --}}
    <div class="flex flex-1 overflow-hidden" style="height: calc(100vh - 52px);">
        {{-- Left Sidebar --}}
        <div class="w-64 bg-gray-900 border-r border-gray-700 flex flex-col flex-shrink-0 overflow-hidden" x-show="!previewMode">
            {{-- Sidebar Tabs --}}
            <div class="flex border-b border-gray-700 flex-shrink-0">
                <button @click="activePanel = 'blocks'"
                        class="flex-1 py-2.5 text-xs font-medium text-center transition"
                        :class="activePanel === 'blocks' ? 'text-indigo-400 border-b-2 border-indigo-400 bg-gray-800/50' : 'text-gray-400 hover:text-gray-300'">
                    Blocks
                </button>
                <button @click="activePanel = 'layers'"
                        class="flex-1 py-2.5 text-xs font-medium text-center transition"
                        :class="activePanel === 'layers' ? 'text-indigo-400 border-b-2 border-indigo-400 bg-gray-800/50' : 'text-gray-400 hover:text-gray-300'">
                    Layers
                </button>
            </div>

            {{-- Blocks Panel --}}
            <div x-show="activePanel === 'blocks'" class="flex-1 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #4b5563 #1f2937;">
                <div id="blocks-panel" class="p-2"></div>
            </div>

            {{-- Layers Panel --}}
            <div x-show="activePanel === 'layers'" class="flex-1 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #4b5563 #1f2937;">
                <div id="layers-panel"></div>
            </div>
        </div>

        {{-- Center: GrapesJS Canvas --}}
        <div class="flex-1 bg-gray-200 overflow-hidden" wire:ignore>
            <div id="gjs-editor" style="height: 100%;"></div>
        </div>

        {{-- Right Sidebar: Styles + Settings + SEO --}}
        <div class="w-72 bg-gray-900 border-l border-gray-700 flex flex-col flex-shrink-0 overflow-hidden" x-show="!previewMode"
             x-data="{ rightTab: 'styles' }">
            {{-- Right Sidebar Tabs --}}
            <div class="flex border-b border-gray-700 flex-shrink-0">
                <button @click="rightTab = 'styles'"
                        class="flex-1 py-2.5 text-xs font-medium text-center transition"
                        :class="rightTab === 'styles' ? 'text-indigo-400 border-b-2 border-indigo-400 bg-gray-800/50' : 'text-gray-400 hover:text-gray-300'">
                    Styles
                </button>
                <button @click="rightTab = 'settings'"
                        class="flex-1 py-2.5 text-xs font-medium text-center transition"
                        :class="rightTab === 'settings' ? 'text-indigo-400 border-b-2 border-indigo-400 bg-gray-800/50' : 'text-gray-400 hover:text-gray-300'">
                    Settings
                </button>
            </div>

            {{-- Styles Tab: Selector Manager + Style Manager --}}
            <div x-show="rightTab === 'styles'" class="flex-1 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #4b5563 #1f2937;">
                <div id="selectors-panel" class="px-2 pt-2 border-b border-gray-700/50"></div>
                <div id="styles-panel" class="p-2"></div>
            </div>

            {{-- Settings Tab: Trait Manager (Component Properties) --}}
            <div x-show="rightTab === 'settings'" class="flex-1 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #4b5563 #1f2937;">
                <div class="px-3 py-2 border-b border-gray-700/50">
                    <p class="text-xs text-gray-500">Select a component to edit its properties</p>
                </div>
                <div id="traits-panel" class="p-2"></div>
            </div>

            {{-- SEO Panel (slide open) --}}
            <div x-show="showSeoPanel" x-transition class="border-t border-gray-700 bg-gray-800 p-4 overflow-y-auto max-h-80" style="scrollbar-width: thin; scrollbar-color: #4b5563 #1f2937;">
                <h4 class="text-sm font-semibold text-gray-300 mb-3">SEO Settings</h4>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-400 mb-1">Meta Title</label>
                    <input
                        type="text"
                        wire:model.blur="metaTitle"
                        placeholder="Auto-generated from title"
                        class="w-full bg-gray-900 border border-gray-700 rounded-md px-2.5 py-1.5 text-sm text-gray-300 placeholder-gray-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    />
                    <p class="mt-1 text-xs text-gray-500">{{ strlen($metaTitle) }}/60</p>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-400 mb-1">Meta Description</label>
                    <textarea
                        wire:model.blur="metaDescription"
                        rows="3"
                        placeholder="Auto-generated from content"
                        class="w-full bg-gray-900 border border-gray-700 rounded-md px-2.5 py-1.5 text-sm text-gray-300 placeholder-gray-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none"
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">{{ strlen($metaDescription) }}/160</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Builder-specific styles --}}
    <style>
        /* GrapesJS overrides for dark theme */
        #blocks-panel .gjs-block {
            width: calc(50% - 6px);
            min-height: 65px;
            margin: 3px;
            border-radius: 6px;
            background: #374151;
            border: 1px solid #4b5563;
            color: #d1d5db;
            font-size: 11px;
            transition: all 0.15s ease;
        }
        #blocks-panel .gjs-block:hover {
            background: #4b5563;
            border-color: #6366f1;
            color: white;
        }
        #blocks-panel .gjs-block-category .gjs-title {
            background: #1f2937;
            color: #9ca3af;
            border-bottom: 1px solid #374151;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 12px;
        }
        #blocks-panel .gjs-block-category {
            border: none;
        }

        /* Layers panel styling */
        #layers-panel .gjs-layer {
            background: transparent;
            color: #d1d5db;
            font-size: 12px;
        }
        #layers-panel .gjs-layer:hover {
            background: #374151;
        }
        #layers-panel .gjs-layer-title {
            padding: 6px 10px;
        }

        /* Selector manager */
        #selectors-panel .gjs-clm-tags {
            padding: 6px 0;
        }
        #selectors-panel .gjs-clm-tag {
            background: #4f46e5;
            color: white;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
        }
        #selectors-panel .gjs-clm-tag-close {
            color: rgba(255,255,255,0.7);
        }
        #selectors-panel .gjs-clm-tag-close:hover {
            color: white;
        }
        #selectors-panel .gjs-clm-sels-info {
            color: #9ca3af;
            font-size: 11px;
        }
        #selectors-panel .gjs-field {
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 4px;
            color: #d1d5db;
        }
        #selectors-panel .gjs-sm-label,
        #selectors-panel .gjs-clm-label {
            color: #9ca3af;
            font-size: 11px;
        }
        #selectors-panel .gjs-clm-select option {
            background: #1f2937;
            color: #d1d5db;
        }

        /* Style manager */
        #styles-panel .gjs-sm-sector .gjs-sm-sector-title {
            background: #1f2937;
            color: #9ca3af;
            border-bottom: 1px solid #374151;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 12px;
        }
        #styles-panel .gjs-sm-sector .gjs-sm-properties {
            padding: 8px;
        }
        #styles-panel .gjs-field {
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 4px;
            color: #d1d5db;
        }
        #styles-panel .gjs-field input,
        #styles-panel .gjs-field select {
            color: #d1d5db;
        }
        #styles-panel .gjs-sm-label {
            color: #9ca3af;
            font-size: 11px;
        }
        #styles-panel .gjs-sm-composite .gjs-sm-label {
            color: #6b7280;
        }

        /* Trait manager (Settings tab) */
        #traits-panel .gjs-trt-trait {
            padding: 6px 4px;
            border-bottom: 1px solid #374151;
        }
        #traits-panel .gjs-trt-trait:last-child {
            border-bottom: none;
        }
        #traits-panel .gjs-label-wrp {
            color: #9ca3af;
            font-size: 11px;
            min-width: 70px;
        }
        #traits-panel .gjs-field {
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 4px;
            color: #d1d5db;
        }
        #traits-panel .gjs-field input,
        #traits-panel .gjs-field select,
        #traits-panel .gjs-field textarea {
            color: #d1d5db;
            background: transparent;
        }
        #traits-panel .gjs-field select option {
            background: #1f2937;
            color: #d1d5db;
        }
        #traits-panel .gjs-trt-trait--checkbox input[type="checkbox"] {
            accent-color: #6366f1;
        }

        /* Canvas area */
        .gjs-cv-canvas {
            width: 100%;
            height: 100%;
        }
        .gjs-frame-wrapper {
            background: #e5e7eb;
        }

        /* Toolbar on selected element */
        .gjs-toolbar {
            background: #4f46e5;
            border-radius: 6px;
        }
        .gjs-toolbar-item {
            color: white;
            padding: 4px 6px;
        }

        /* Resizer */
        .gjs-resizer-h {
            border-color: #4f46e5;
        }

        /* Scrollbar for panels */
        #blocks-panel::-webkit-scrollbar,
        #layers-panel::-webkit-scrollbar,
        #styles-panel::-webkit-scrollbar,
        #selectors-panel::-webkit-scrollbar,
        #traits-panel::-webkit-scrollbar {
            width: 6px;
        }
        #blocks-panel::-webkit-scrollbar-thumb,
        #layers-panel::-webkit-scrollbar-thumb,
        #styles-panel::-webkit-scrollbar-thumb,
        #selectors-panel::-webkit-scrollbar-thumb,
        #traits-panel::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 3px;
        }
        #blocks-panel::-webkit-scrollbar-track,
        #layers-panel::-webkit-scrollbar-track,
        #styles-panel::-webkit-scrollbar-track,
        #selectors-panel::-webkit-scrollbar-track,
        #traits-panel::-webkit-scrollbar-track {
            background: #1f2937;
        }
    </style>
</div>
