<?php

namespace App\Models;

use App\Models\Traits\Enums\FileType;
use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];

    use FileType;

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
