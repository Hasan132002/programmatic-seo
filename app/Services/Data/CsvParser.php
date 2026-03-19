<?php

namespace App\Services\Data;

use RuntimeException;

/**
 * Parses CSV files into structured associative arrays.
 *
 * Handles column detection, preview extraction, and full parsing
 * with the first row treated as column headers.
 */
class CsvParser
{
    /**
     * Parse an entire CSV file and return all rows as associative arrays.
     *
     * The first row is used as column headers. Each subsequent row is
     * returned as an associative array keyed by the header values.
     *
     * @param string $filePath  Absolute path to the CSV file.
     *
     * @return array<int, array<string, string>>  Array of associative row arrays.
     *
     * @throws RuntimeException If the file cannot be read or is empty.
     */
    public function parse(string $filePath): array
    {
        $this->validateFile($filePath);

        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw new RuntimeException("Cannot open CSV file: {$filePath}");
        }

        try {
            // Read header row
            $headers = fgetcsv($handle);

            if ($headers === false || empty($headers)) {
                throw new RuntimeException("CSV file is empty or has no header row: {$filePath}");
            }

            // Normalize headers: trim whitespace, remove BOM
            $headers = array_map(function (string $header) {
                $header = trim($header);
                // Remove UTF-8 BOM if present on first column
                $header = preg_replace('/^\x{FEFF}/u', '', $header);
                return $header;
            }, $headers);

            $rows = [];
            $headerCount = count($headers);

            while (($row = fgetcsv($handle)) !== false) {
                // Skip completely empty rows
                if (count($row) === 1 && ($row[0] === null || $row[0] === '')) {
                    continue;
                }

                // Pad or trim to match header count
                if (count($row) < $headerCount) {
                    $row = array_pad($row, $headerCount, '');
                } elseif (count($row) > $headerCount) {
                    $row = array_slice($row, 0, $headerCount);
                }

                $rows[] = array_combine($headers, $row);
            }

            return $rows;
        } finally {
            fclose($handle);
        }
    }

    /**
     * Detect and return column names from the first row (header) of a CSV file.
     *
     * @param string $filePath  Absolute path to the CSV file.
     *
     * @return array<int, string>  Ordered list of column header names.
     *
     * @throws RuntimeException If the file cannot be read.
     */
    public function detectColumns(string $filePath): array
    {
        $this->validateFile($filePath);

        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw new RuntimeException("Cannot open CSV file: {$filePath}");
        }

        try {
            $headers = fgetcsv($handle);

            if ($headers === false || empty($headers)) {
                return [];
            }

            return array_map(function (string $header) {
                $header = trim($header);
                $header = preg_replace('/^\x{FEFF}/u', '', $header);
                return $header;
            }, $headers);
        } finally {
            fclose($handle);
        }
    }

    /**
     * Return a preview of the first N rows of a CSV file.
     *
     * Includes the header row as keys in each associative array row.
     * Useful for showing a data preview in the UI before full import.
     *
     * @param string $filePath  Absolute path to the CSV file.
     * @param int    $rows      Number of data rows to preview (default 5).
     *
     * @return array<int, array<string, string>>  Array of up to N associative row arrays.
     *
     * @throws RuntimeException If the file cannot be read.
     */
    public function getPreview(string $filePath, int $rows = 5): array
    {
        $this->validateFile($filePath);

        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw new RuntimeException("Cannot open CSV file: {$filePath}");
        }

        try {
            $headers = fgetcsv($handle);

            if ($headers === false || empty($headers)) {
                return [];
            }

            $headers = array_map(function (string $header) {
                $header = trim($header);
                $header = preg_replace('/^\x{FEFF}/u', '', $header);
                return $header;
            }, $headers);

            $headerCount = count($headers);
            $preview = [];
            $count = 0;

            while ($count < $rows && ($row = fgetcsv($handle)) !== false) {
                if (count($row) === 1 && ($row[0] === null || $row[0] === '')) {
                    continue;
                }

                if (count($row) < $headerCount) {
                    $row = array_pad($row, $headerCount, '');
                } elseif (count($row) > $headerCount) {
                    $row = array_slice($row, 0, $headerCount);
                }

                $preview[] = array_combine($headers, $row);
                $count++;
            }

            return $preview;
        } finally {
            fclose($handle);
        }
    }

    /**
     * Validate that the file exists and is readable.
     *
     * @param string $filePath  The file path to validate.
     *
     * @throws RuntimeException If the file does not exist or is not readable.
     */
    protected function validateFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("CSV file not found: {$filePath}");
        }

        if (!is_readable($filePath)) {
            throw new RuntimeException("CSV file is not readable: {$filePath}");
        }
    }
}
