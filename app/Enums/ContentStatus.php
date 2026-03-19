<?php

namespace App\Enums;

enum ContentStatus: string
{
    case Draft = 'draft';
    case Generating = 'generating';
    case Published = 'published';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Generating => 'Generating',
            self::Published => 'Published',
            self::Failed => 'Failed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Generating => 'yellow',
            self::Published => 'green',
            self::Failed => 'red',
        };
    }
}
