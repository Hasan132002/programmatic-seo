<?php

namespace App\Enums;

enum NicheType: string
{
    case City = 'city';
    case Comparison = 'comparison';
    case Directory = 'directory';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::City => 'City / Location Based',
            self::Comparison => 'Comparison Pages',
            self::Directory => 'Directory / Listings',
            self::Custom => 'Custom',
        };
    }
}
