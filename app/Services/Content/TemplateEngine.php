<?php

namespace App\Services\Content;

use Illuminate\Support\Arr;

/**
 * Simple template engine for replacing {{variable}} placeholders with data.
 *
 * Supports flat variables, dot-notation nested access, and automatic
 * JSON encoding of array values. Used by the content generation pipeline
 * to render page templates before or instead of AI generation.
 */
class TemplateEngine
{
    /**
     * Render a template string by replacing all {{variable_name}} placeholders
     * with the corresponding values from the variables array.
     *
     * Supports:
     *  - Simple variables:         {{city_name}}
     *  - Dot-notation nesting:     {{location.state}}
     *  - Array values:             Automatically JSON-encoded
     *  - Unresolved placeholders:  Left as-is (empty string)
     *
     * @param string $template   The template HTML containing {{variable}} placeholders.
     * @param array  $variables  Associative array of variable names to values.
     *
     * @return string  The rendered HTML with placeholders replaced.
     */
    public function render(string $template, array $variables): string
    {
        return preg_replace_callback(
            '/\{\{\s*([\w.]+)\s*\}\}/',
            function (array $matches) use ($variables) {
                $key = $matches[1];

                $value = Arr::get($variables, $key);

                if ($value === null) {
                    return '';
                }

                if (is_array($value)) {
                    return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }

                return (string) $value;
            },
            $template,
        );
    }

    /**
     * Extract all {{variable_name}} placeholder names from a template string.
     *
     * @param string $template  The template HTML to scan for placeholders.
     *
     * @return array<int, string>  Unique list of variable names found.
     */
    public function extractVariables(string $template): array
    {
        preg_match_all('/\{\{\s*([\w.]+)\s*\}\}/', $template, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }
}
