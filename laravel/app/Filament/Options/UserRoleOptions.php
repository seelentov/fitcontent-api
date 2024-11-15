<?php

namespace App\Filament\Options;

use Filament\Support\Contracts\HasLabel;

enum UserRoleOptions: string implements HasLabel
{
    case TYPE_ADMIN = 'admin';
    case TYPE_USER = 'user';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::TYPE_ADMIN => 'admin',
            self::TYPE_USER => 'user'
        };
    }
}
