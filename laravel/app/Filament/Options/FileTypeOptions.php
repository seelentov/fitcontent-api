<?php

namespace App\Filament\Options;

use Filament\Support\Contracts\HasLabel;

enum FileTypeOptions: string implements HasLabel
{
    case TYPE_IMAGE = 'image';
    case TYPE_TEXT = 'text';
    case TYPE_DOC = 'doc';
    case TYPE_AUDIO = 'audio';
    case TYPE_VIDEO = 'video';
    case TYPE_ARCHIVE = 'archive';
    case TYPE_UNKNOWN = 'unknown';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TYPE_IMAGE => 'image',
            self::TYPE_TEXT => 'text',
            self::TYPE_DOC => 'doc',
            self::TYPE_AUDIO => 'audio',
            self::TYPE_VIDEO => 'video',
            self::TYPE_ARCHIVE => 'archive',
            self::TYPE_UNKNOWN => 'unknown',
        };
    }
}
