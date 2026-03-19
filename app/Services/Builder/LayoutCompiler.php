<?php

namespace App\Services\Builder;

use App\Services\Content\TemplateEngine;
use RuntimeException;

/**
 * Compiles GrapesJS layout JSON into rendered HTML.
 *
 * GrapesJS stores project data as JSON containing HTML, CSS, and component
 * structures. This class extracts the HTML and applies variable substitution
 * so the layout can be served as final page content.
 */
class LayoutCompiler
{
    protected TemplateEngine $templateEngine;

    public function __construct(?TemplateEngine $templateEngine = null)
    {
        $this->templateEngine = $templateEngine ?? new TemplateEngine();
    }

    /**
     * Compile GrapesJS project JSON into fully rendered HTML with variable substitution.
     *
     * Extracts the HTML content from the GrapesJS data structure, optionally
     * includes the CSS as an inline <style> block, and replaces any
     * {{variable}} placeholders with the provided values.
     *
     * @param string $layoutJson  The GrapesJS project data JSON string.
     * @param array  $variables   Variables to substitute into the HTML.
     *
     * @return string  The compiled and rendered HTML content.
     *
     * @throws RuntimeException If the JSON cannot be decoded.
     */
    public function compile(string $layoutJson, array $variables = []): string
    {
        $data = $this->decodeJson($layoutJson);

        $html = $this->extractHtmlFromData($data);
        $css = $this->extractCssFromData($data);

        // Prepend CSS as inline style block if present
        $output = '';
        if (!empty($css)) {
            $output .= "<style>{$css}</style>\n";
        }
        $output .= $html;

        // Apply variable substitution
        if (!empty($variables)) {
            $output = $this->templateEngine->render($output, $variables);
        }

        return $output;
    }

    /**
     * Extract just the HTML string from GrapesJS project JSON data.
     *
     * This does not apply variable substitution; it returns the raw HTML
     * template as stored by the GrapesJS editor.
     *
     * @param string $layoutJson  The GrapesJS project data JSON string.
     *
     * @return string  The extracted HTML content.
     *
     * @throws RuntimeException If the JSON cannot be decoded.
     */
    public function extractHtml(string $layoutJson): string
    {
        $data = $this->decodeJson($layoutJson);

        return $this->extractHtmlFromData($data);
    }

    /**
     * Decode JSON and validate the result.
     *
     * @param string $json  The JSON string to decode.
     *
     * @return array  The decoded data array.
     *
     * @throws RuntimeException If decoding fails.
     */
    protected function decodeJson(string $json): array
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                'Failed to decode layout JSON: ' . json_last_error_msg(),
            );
        }

        return $data ?? [];
    }

    /**
     * Extract HTML from the decoded GrapesJS data structure.
     *
     * GrapesJS may store HTML in various locations depending on the version
     * and configuration. This method checks common paths.
     *
     * @param array $data  The decoded GrapesJS project data.
     *
     * @return string  The extracted HTML.
     */
    protected function extractHtmlFromData(array $data): string
    {
        // GrapesJS project data: { "html": "...", "css": "..." }
        if (isset($data['html'])) {
            return (string) $data['html'];
        }

        // GrapesJS stored pages: { "pages": [{ "frames": [{ "component": {...} }] }] }
        if (isset($data['pages'][0]['frames'][0]['component'])) {
            return $this->componentToHtml($data['pages'][0]['frames'][0]['component']);
        }

        // GrapesJS components array
        if (isset($data['components']) && is_array($data['components'])) {
            $html = '';
            foreach ($data['components'] as $component) {
                $html .= $this->componentToHtml($component);
            }
            return $html;
        }

        // Single component object
        if (isset($data['type']) || isset($data['tagName'])) {
            return $this->componentToHtml($data);
        }

        return '';
    }

    /**
     * Extract CSS from the decoded GrapesJS data structure.
     *
     * @param array $data  The decoded GrapesJS project data.
     *
     * @return string  The extracted CSS.
     */
    protected function extractCssFromData(array $data): string
    {
        if (isset($data['css']) && is_string($data['css'])) {
            return $data['css'];
        }

        // GrapesJS styles array
        if (isset($data['styles']) && is_array($data['styles'])) {
            return $this->stylesToCss($data['styles']);
        }

        return '';
    }

    /**
     * Recursively convert a GrapesJS component object to an HTML string.
     *
     * @param array $component  The GrapesJS component definition.
     *
     * @return string  The HTML representation.
     */
    protected function componentToHtml(array $component): string
    {
        $type = $component['type'] ?? '';
        $tagName = $component['tagName'] ?? 'div';

        // Text node
        if ($type === 'textnode' || $type === 'text') {
            return $component['content'] ?? '';
        }

        // Build attributes string
        $attributes = '';
        if (isset($component['attributes']) && is_array($component['attributes'])) {
            foreach ($component['attributes'] as $attr => $value) {
                if ($value === true) {
                    $attributes .= " {$attr}";
                } elseif ($value !== false && $value !== null) {
                    $attributes .= " {$attr}=\"" . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '"';
                }
            }
        }

        // Add classes
        if (!empty($component['classes']) && is_array($component['classes'])) {
            $classNames = [];
            foreach ($component['classes'] as $class) {
                $classNames[] = is_array($class) ? ($class['name'] ?? '') : (string) $class;
            }
            $classNames = array_filter($classNames);
            if (!empty($classNames)) {
                $attributes .= ' class="' . htmlspecialchars(implode(' ', $classNames), ENT_QUOTES, 'UTF-8') . '"';
            }
        }

        // Self-closing tags
        $selfClosing = ['img', 'br', 'hr', 'input', 'meta', 'link'];
        if (in_array($tagName, $selfClosing, true)) {
            return "<{$tagName}{$attributes} />";
        }

        // Build inner content
        $inner = $component['content'] ?? '';

        if (isset($component['components']) && is_array($component['components'])) {
            foreach ($component['components'] as $child) {
                $inner .= $this->componentToHtml($child);
            }
        }

        return "<{$tagName}{$attributes}>{$inner}</{$tagName}>";
    }

    /**
     * Convert GrapesJS styles array to a CSS string.
     *
     * @param array $styles  The GrapesJS styles definitions.
     *
     * @return string  The CSS string.
     */
    protected function stylesToCss(array $styles): string
    {
        $css = '';

        foreach ($styles as $style) {
            $selectors = $style['selectors'] ?? [];
            $properties = $style['style'] ?? [];

            if (empty($selectors) || empty($properties)) {
                continue;
            }

            // Build selector string
            $selectorParts = [];
            foreach ($selectors as $selector) {
                $selectorParts[] = is_array($selector) ? ($selector['name'] ?? '') : (string) $selector;
            }
            $selectorStr = implode(', ', array_filter($selectorParts));

            if (empty($selectorStr)) {
                continue;
            }

            // Build properties string
            $propsStr = '';
            foreach ($properties as $prop => $value) {
                $propsStr .= "  {$prop}: {$value};\n";
            }

            $css .= "{$selectorStr} {\n{$propsStr}}\n";
        }

        return $css;
    }
}
