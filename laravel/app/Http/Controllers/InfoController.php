<?php

namespace App\Http\Controllers;

use App\Services\InfoService\InfoService;

class InfoController extends Controller
{
    public function __construct(
        private readonly InfoService $info,
    ) {}

    public function show(string $slug)
    {
        $info = $this->info->getBySlug($slug);

        return response()->json($info);
    }
}
