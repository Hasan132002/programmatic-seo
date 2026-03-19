<?php

namespace App\Services\Data;

use Illuminate\Support\Str;

/**
 * Maps data source columns to template variables based on a mapping configuration.
 *
 * Handles column-to-variable mapping for CSV imports and other data sources,
 * and can auto-suggest mappings by comparing column names to a variable schema.
 */
class DataMapper
{
    /**
     * Map a single data row's columns to template variable names using the mapping config.
     *
     * The mapping config is an associative array where keys are template variable
     * names and values are source column names. For example:
     *   ['city_name' => 'City', 'state' => 'State/Province']
     *
     * @param array $row      A single data row (associative array keyed by column names).
     * @param array $mapping  Mapping config: [variable_name => source_column_name].
     *
     * @return array  Associative array keyed by template variable names with mapped values.
     */
    public function map(array $row, array $mapping): array
    {
        $result = [];

        foreach ($mapping as $variableName => $sourceColumn) {
            if (is_string($sourceColumn) && array_key_exists($sourceColumn, $row)) {
                $result[$variableName] = $row[$sourceColumn];
            } elseif (is_array($sourceColumn)) {
                // Support composite mapping: combine multiple columns
                // e.g. ['full_address' => ['columns' => ['Street', 'City', 'State'], 'separator' => ', ']]
                $columns = $sourceColumn['columns'] ?? [];
                $separator = $sourceColumn['separator'] ?? ' ';

                $parts = [];
                foreach ($columns as $col) {
                    if (array_key_exists($col, $row) && !empty($row[$col])) {
                        $parts[] = $row[$col];
                    }
                }

                $result[$variableName] = implode($separator, $parts);
            } else {
                // If the source column doesn't exist, use empty string
                $result[$variableName] = '';
            }
        }

        return $result;
    }

    /**
     * Auto-suggest a column-to-variable mapping by comparing source column names
     * to the template's variable schema.
     *
     * Uses fuzzy matching: exact match, case-insensitive match, snake_case
     * normalisation, and substring containment.
     *
     * @param array $columns         Source column names from the data file.
     * @param array $variableSchema  Template variable schema. Each entry can be:
     *                               - A simple string (the variable name), or
     *                               - An array with 'name' and optional 'aliases' keys.
     *
     * @return array  Suggested mapping: [variable_name => best_matching_column_name].
     */
    public function generateMapping(array $columns, array $variableSchema): array
    {
        $mapping = [];
        $usedColumns = [];

        foreach ($variableSchema as $schema) {
            // Normalise schema entry
            if (is_string($schema)) {
                $variableName = $schema;
                $aliases = [];
            } else {
                $variableName = $schema['name'] ?? '';
                $aliases = $schema['aliases'] ?? [];
            }

            if (empty($variableName)) {
                continue;
            }

            // Build a list of names to match against (variable name + aliases)
            $namesToMatch = array_merge([$variableName], $aliases);
            $normalised = array_map(fn (string $n) => $this->normalise($n), $namesToMatch);

            $bestMatch = null;
            $bestScore = 0;

            foreach ($columns as $column) {
                if (in_array($column, $usedColumns, true)) {
                    continue;
                }

                $score = $this->calculateMatchScore($column, $namesToMatch, $normalised);

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = $column;
                }
            }

            if ($bestMatch !== null && $bestScore >= 40) {
                $mapping[$variableName] = $bestMatch;
                $usedColumns[] = $bestMatch;
            }
        }

        return $mapping;
    }

    /**
     * Calculate a match score between a column name and a set of target names.
     *
     * @param string $column         The source column name.
     * @param array  $targetNames    Original target names to match against.
     * @param array  $normalisedNames Normalised versions of the target names.
     *
     * @return int  Match score (0-100). Higher is better.
     */
    protected function calculateMatchScore(string $column, array $targetNames, array $normalisedNames): int
    {
        $normalisedColumn = $this->normalise($column);

        // Exact match (case-insensitive)
        foreach ($targetNames as $name) {
            if (strtolower($column) === strtolower($name)) {
                return 100;
            }
        }

        // Normalised exact match (e.g. "City Name" matches "city_name")
        foreach ($normalisedNames as $normalised) {
            if ($normalisedColumn === $normalised) {
                return 90;
            }
        }

        // Column contains variable name or vice versa
        foreach ($normalisedNames as $normalised) {
            if (str_contains($normalisedColumn, $normalised)) {
                return 70;
            }
            if (str_contains($normalised, $normalisedColumn)) {
                return 60;
            }
        }

        // Partial word overlap
        $columnWords = explode('_', $normalisedColumn);
        $maxOverlap = 0;

        foreach ($normalisedNames as $normalised) {
            $targetWords = explode('_', $normalised);
            $overlap = count(array_intersect($columnWords, $targetWords));
            $total = max(count($columnWords), count($targetWords));

            if ($total > 0) {
                $score = (int) ($overlap / $total * 50);
                $maxOverlap = max($maxOverlap, $score);
            }
        }

        return $maxOverlap;
    }

    /**
     * Normalise a string to snake_case for comparison.
     *
     * @param string $value  The string to normalise.
     *
     * @return string  The normalised snake_case string.
     */
    protected function normalise(string $value): string
    {
        // Convert to snake_case and lowercase
        $value = Str::snake($value);
        $value = strtolower($value);

        // Remove non-alphanumeric characters except underscores
        $value = preg_replace('/[^a-z0-9_]/', '', $value);

        return $value;
    }
}
