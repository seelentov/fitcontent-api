<?php

namespace App\Models\Traits\Enums;





trait FileType
{
    const TYPE_IMAGE = 'image';
    const TYPE_DOC = 'doc';
    const TYPE_AUDIO = 'audio';
    const TYPE_VIDEO = 'video';
    const TYPE_UNKNOWN = 'unknown';

    const TYPES_IMAGE_LIST = [
        'jpg',
        'jpeg',
        'jpe',
        'png',
        'gif',
        'bmp',
        'tiff',
        'tif',
        'svg',
        'webp',
        'heif',
        'psd',
        'raw'
    ];
    const TYPES_DOC_LIST = [
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'txt',
        'rtf',
        'odt',
        'ods',
        'odp',
        'csv',
        'zip',
        'rar',
        '7z',
        'gz',
        'tar'
    ];
    const TYPES_AUDIO_LIST = [
        'mp3',
        'wav',
        'ogg',
        'flac',
        'aac',
        'wma',
        'm4a',
        'aiff'
    ];
    const TYPES_VIDEO_LIST = [
        'mp4',
        'mov',
        'avi',
        'wmv',
        'mkv',
        'flv',
        'mpeg',
        'mpg',
        'webm',
        'ts',
        'm4v'
    ];


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
