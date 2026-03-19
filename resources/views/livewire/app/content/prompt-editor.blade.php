<div x-data="{
    promptText: @entangle('promptTemplate'),
    insertAtCursor(variable) {
        const textarea = $refs.promptTextarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        textarea.value = text.substring(0, start) + variable + text.substring(end);
        textarea.selectionStart = textarea.selectionEnd = start + variable.length;
        textarea.focus();
        promptText = textarea.value;
        $wire.set('promptTemplate', textarea.value);
    }
}" @insert-variable.window="insertAtCursor($event.detail.variable)" class="space-y-6">

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="flex items-center p-4 rounded-lg bg-green-50 border border-green-200">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="flex items-center p-4 rounded-lg bg-red-50 border border-red-200">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Header Row: Template Name + Data Source Selector --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Template Name --}}
        <div class="md:col-span-2">
            <label for="templateName" class="block text-sm font-semibold text-gray-700 mb-1.5">Template Name</label>
            <input
                wire:model="templateName"
                type="text"
                id="templateName"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                placeholder="e.g., City Service Guide, Product Comparison"
            >
            @error('templateName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Data Source Selector --}}
        <div>
            <label for="dataSourceId" class="block text-sm font-semibold text-gray-700 mb-1.5">Data Source (optional)</label>
            <select
                wire:model.live="dataSourceId"
                id="dataSourceId"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
                <option value="">-- Use default variables --</option>
                @foreach($dataSources as $ds)
                    <option value="{{ $ds->id }}">{{ $ds->name }} ({{ $ds->entries()->count() }} entries)</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Split Pane Editor --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left: Prompt Editor --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                        </svg>
                        Prompt Editor
                    </h3>
                    <span class="text-xs text-gray-400">Click a variable to insert it</span>
                </div>

                {{-- Variable Chips --}}
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                    <p class="text-xs text-gray-500 mb-2 font-medium">Available Variables:</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($variables as $variable)
                            <button
                                type="button"
                                @click="insertAtCursor('{'+'{' + '{{ $variable }}' + '}' + '}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition-colors cursor-pointer border border-indigo-200"
                            >
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                {{ $variable }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Prompt Textarea --}}
                <div class="p-4">
                    <textarea
                        x-ref="promptTextarea"
                        x-model="promptText"
                        @input="$wire.set('promptTemplate', $event.target.value)"
                        rows="16"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono leading-relaxed"
                        placeholder="Write your prompt template here...&#10;&#10;Use {{variable_name}} syntax to insert dynamic data.&#10;&#10;Example:&#10;Write a comprehensive guide about {{topic}} in {{city_name}}, {{state}}.&#10;Include information about local {{service}} options, pricing, and tips&#10;for visitors. Target the keyword: {{keyword}}"
                    ></textarea>
                    @error('promptTemplate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-xs text-gray-400">{{ strlen($promptTemplate) }} characters</p>
                        <button
                            wire:click="preview"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Update Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Live Preview --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Live Preview
                    </h3>
                    @if($dataSourceId)
                        <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium">Using real data</span>
                    @else
                        <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full font-medium">Using sample data</span>
                    @endif
                </div>

                <div class="p-4">
                    @if($previewOutput)
                        <div class="prose prose-sm max-w-none bg-gray-50/50 rounded-lg p-4 border border-gray-100 min-h-[24rem]">
                            <pre class="whitespace-pre-wrap text-sm text-gray-800 font-sans leading-relaxed">{{ $previewOutput }}</pre>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center text-center py-16 min-h-[24rem]">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-sm text-gray-400 font-medium">Preview will appear here</p>
                            <p class="text-xs text-gray-400 mt-1">Write a prompt template and click "Update Preview"</p>
                        </div>
                    @endif
                </div>

                {{-- Sample Data Display --}}
                @if(!empty($sampleData))
                    <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                        <p class="text-xs text-gray-500 font-medium mb-2">Sample Data Values:</p>
                        <div class="grid grid-cols-2 gap-1.5">
                            @foreach($sampleData as $key => $value)
                                <div class="text-xs">
                                    <span class="font-medium text-gray-600">{{ $key }}:</span>
                                    <span class="text-gray-500">{{ is_array($value) ? json_encode($value) : Str::limit((string) $value, 30) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button
                wire:click="saveAsTemplate"
                type="button"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-lg shadow-indigo-200"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                </svg>
                {{ $editingTemplateId ? 'Update Template' : 'Save as Template' }}
            </button>

            @if($editingTemplateId)
                <button
                    wire:click="newTemplate"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-800 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    New Template
                </button>
            @endif
        </div>
    </div>

    {{-- Saved Templates List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                </svg>
                Saved Prompt Templates
            </h3>
        </div>

        @if($savedTemplates->isEmpty())
            <div class="p-8 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <p class="text-sm text-gray-500 font-medium">No prompt templates yet</p>
                <p class="text-xs text-gray-400 mt-1">Create your first prompt template above to reuse across pages</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($savedTemplates as $template)
                    <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 transition-colors {{ $editingTemplateId === $template->id ? 'bg-indigo-50 border-l-2 border-indigo-500' : '' }}">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $template->name }}</p>
                                @if($template->is_system)
                                    <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded bg-blue-100 text-blue-700">System</span>
                                @endif
                                @if($template->niche_type)
                                    <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium rounded bg-gray-100 text-gray-600">{{ $template->niche_type->label() }}</span>
                                @endif
                            </div>
                            @if($template->variable_schema)
                                <p class="text-xs text-gray-400 mt-0.5 truncate">
                                    Variables: {{ implode(', ', array_keys($template->variable_schema)) }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5">
                            <button
                                wire:click="loadTemplate({{ $template->id }})"
                                type="button"
                                class="inline-flex items-center p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                title="Edit template"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                </svg>
                            </button>

                            @if(!$template->is_system)
                                <button
                                    wire:click="deleteTemplate({{ $template->id }})"
                                    wire:confirm="Are you sure you want to delete this template?"
                                    type="button"
                                    class="inline-flex items-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Delete template"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
