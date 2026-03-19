<?php

namespace App\Livewire\App\Content;

use App\Models\DataSource;
use App\Models\PageTemplate;
use App\Models\Site;
use App\Services\Content\TemplateEngine;
use Illuminate\Support\Str;
use Livewire\Component;

class PromptEditor extends Component
{
    public Site $site;

    public string $promptTemplate = '';
    public string $templateName = '';
    public array $variables = [];
    public string $previewOutput = '';
    public ?int $dataSourceId = null;
    public array $sampleData = [];

    // For editing existing template
    public ?int $editingTemplateId = null;

    protected function rules(): array
    {
        return [
            'templateName' => 'required|string|max:255',
            'promptTemplate' => 'required|string|min:10',
        ];
    }

    public function mount(Site $site): void
    {
        $this->site = $site;
        $this->loadDefaultVariables();
    }

    /**
     * Load default variables based on the site niche type.
     */
    protected function loadDefaultVariables(): void
    {
        $this->variables = match ($this->site->niche_type->value) {
            'city' => ['city_name', 'state', 'topic', 'service', 'keyword', 'population'],
            'comparison' => ['item_1', 'item_2', 'category', 'topic', 'keyword'],
            'directory' => ['name', 'business_name', 'category', 'location', 'city', 'type'],
            'custom' => ['title', 'topic', 'keyword', 'description', 'summary'],
            default => ['title', 'topic', 'keyword'],
        };
    }

    /**
     * When data source changes, extract available columns as variables.
     */
    public function updatedDataSourceId($value): void
    {
        if (!$value) {
            $this->loadDefaultVariables();
            $this->sampleData = [];
            $this->previewOutput = '';
            return;
        }

        $dataSource = DataSource::find($value);
        if (!$dataSource) {
            return;
        }

        // Get the first entry to extract column names and sample data
        $firstEntry = $dataSource->entries()->first();
        if ($firstEntry && is_array($firstEntry->data)) {
            $this->variables = array_keys($firstEntry->data);
            $this->sampleData = $firstEntry->data;
        }

        $this->updatePreview();
    }

    /**
     * Insert a variable placeholder at cursor position (handled by Alpine.js).
     */
    public function insertVariable(string $variable): void
    {
        // The actual insertion is handled by Alpine.js on the frontend.
        // This just dispatches an event for the JS handler.
        $this->dispatch('insert-variable', variable: '{{' . $variable . '}}');
    }

    /**
     * Generate a preview of the prompt with sample data.
     */
    public function preview(): void
    {
        if (empty($this->promptTemplate)) {
            $this->previewOutput = '';
            return;
        }

        $engine = app(TemplateEngine::class);

        // Build sample data from variable list if no data source is selected
        $sampleValues = $this->sampleData;
        if (empty($sampleValues)) {
            foreach ($this->variables as $var) {
                $sampleValues[$var] = '[Sample ' . Str::title(str_replace('_', ' ', $var)) . ']';
            }
        }

        $this->previewOutput = $engine->render($this->promptTemplate, $sampleValues);
    }

    /**
     * Trigger preview when the prompt template changes.
     */
    public function updatedPromptTemplate(): void
    {
        $this->updatePreview();
    }

    protected function updatePreview(): void
    {
        if (empty($this->promptTemplate)) {
            $this->previewOutput = '';
            return;
        }

        $engine = app(TemplateEngine::class);

        $sampleValues = $this->sampleData;
        if (empty($sampleValues)) {
            foreach ($this->variables as $var) {
                $sampleValues[$var] = '[Sample ' . Str::title(str_replace('_', ' ', $var)) . ']';
            }
        }

        $this->previewOutput = $engine->render($this->promptTemplate, $sampleValues);
    }

    /**
     * Save the prompt as a reusable page template.
     */
    public function saveAsTemplate(): void
    {
        $this->validate();

        $engine = app(TemplateEngine::class);
        $extractedVars = $engine->extractVariables($this->promptTemplate);

        // Build a variable schema from extracted variables
        $variableSchema = [];
        foreach ($extractedVars as $var) {
            $variableSchema[$var] = [
                'type' => 'string',
                'label' => Str::title(str_replace('_', ' ', $var)),
                'required' => true,
            ];
        }

        $data = [
            'tenant_id' => auth()->id(),
            'site_id' => $this->site->id,
            'name' => $this->templateName,
            'slug' => Str::slug($this->templateName),
            'niche_type' => $this->site->niche_type,
            'layout_html' => $this->promptTemplate,
            'variable_schema' => $variableSchema,
            'is_system' => false,
        ];

        if ($this->editingTemplateId) {
            $template = PageTemplate::findOrFail($this->editingTemplateId);
            $template->update($data);
            session()->flash('success', 'Prompt template updated successfully!');
        } else {
            PageTemplate::create($data);
            session()->flash('success', 'Prompt template saved successfully!');
        }

        $this->dispatch('template-saved');
    }

    /**
     * Load an existing template for editing.
     */
    public function loadTemplate(int $templateId): void
    {
        $template = PageTemplate::findOrFail($templateId);
        $this->editingTemplateId = $template->id;
        $this->templateName = $template->name;
        $this->promptTemplate = $template->layout_html ?? '';

        // Extract variables from the template
        $engine = app(TemplateEngine::class);
        $extracted = $engine->extractVariables($this->promptTemplate);
        if (!empty($extracted)) {
            $this->variables = array_unique(array_merge($this->variables, $extracted));
        }

        $this->updatePreview();
    }

    /**
     * Reset the editor to create a new template.
     */
    public function newTemplate(): void
    {
        $this->reset(['promptTemplate', 'templateName', 'previewOutput', 'editingTemplateId', 'sampleData', 'dataSourceId']);
        $this->loadDefaultVariables();
    }

    /**
     * Delete a prompt template.
     */
    public function deleteTemplate(int $templateId): void
    {
        $template = PageTemplate::findOrFail($templateId);

        // Prevent deleting system templates
        if ($template->is_system) {
            session()->flash('error', 'System templates cannot be deleted.');
            return;
        }

        $template->delete();

        if ($this->editingTemplateId === $templateId) {
            $this->newTemplate();
        }

        session()->flash('success', 'Template deleted successfully.');
    }

    public function render()
    {
        return view('livewire.app.content.prompt-editor', [
            'dataSources' => DataSource::where('site_id', $this->site->id)->get(),
            'savedTemplates' => PageTemplate::where(function ($q) {
                $q->where('is_system', true)->orWhere('tenant_id', auth()->id());
            })->where(function ($q) {
                $q->whereNull('site_id')->orWhere('site_id', $this->site->id);
            })->orderBy('name')->get(),
        ]);
    }
}
