<?php

namespace App\Enums;

enum GenerationMethod: string
{
    case AI = 'ai';
    case Template = 'template';
    case Hybrid = 'hybrid';
    case Manual = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::AI => 'AI Generated',
            self::Template => 'Template Only',
            self::Hybrid => 'Hybrid (Template + AI)',
            self::Manual => 'Manual',
        };
    }
}
