<?php

namespace App\Services\InfoService;

use App\Models\Folder;
use App\Models\Info;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class InfoService extends Service implements IInfoService
{
    public function __construct(
        private readonly Info $info,
    ) {}

    public function getAll()
    {
        $info = $this->info->orderBy("position", "asc")->get();
        return $info;
    }
    public function getBySlug(string $slug)
    {
        $info = $this->info->where("header", $slug)->orderBy("position", "asc")->first();
        return $info;
    }
}
