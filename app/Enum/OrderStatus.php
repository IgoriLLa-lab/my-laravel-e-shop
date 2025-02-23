<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel
{
    case NEW = '0';

    case PROCESSED = '1';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NEW => 'Новый',
            self::PROCESSED => 'Обработан'
        };
    }

    public function toInt(): int
    {
        return (int)$this->value;
    }
}
