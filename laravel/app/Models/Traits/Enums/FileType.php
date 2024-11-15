<?php

namespace App\Models\Traits\Enums;





trait FileType
{
    const TYPE_IMAGE = 'image';
    const TYPE_TEXT = 'text';
    const TYPE_DOC = 'doc';
    const TYPE_AUDIO = 'audio';
    const TYPE_VIDEO = 'video';
    const TYPE_ARCHIVE = 'archive';
    const TYPE_UNKNOWN = 'unknown';
    public static function getTypes()
    {
        return [
            self::TYPE_IMAGE,
            self::TYPE_TEXT,
            self::TYPE_DOC,
            self::TYPE_AUDIO,
            self::TYPE_VIDEO,
            self::TYPE_ARCHIVE,
            self::TYPE_UNKNOWN
        ];
    }
}
