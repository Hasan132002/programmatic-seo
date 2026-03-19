<?php

namespace App\Enums;

enum DataSourceType: string
{
    case Csv = 'csv';
    case Api = 'api';
    case Manual = 'manual';
    case Scrape = 'scrape';

    public function label(): string
    {
        return match ($this) {
            self::Csv => 'CSV Upload',
            self::Api => 'API Integration',
            self::Manual => 'Manual Entry',
            self::Scrape => 'Web Scrape',
        };
    }
}
