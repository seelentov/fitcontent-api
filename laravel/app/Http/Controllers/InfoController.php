<?php

namespace App\Http\Controllers;

use App\Services\InfoService\IInfoService;
use App\Services\InfoService\InfoService;

class InfoController extends Controller
{
    public function __construct(
        private readonly IInfoService $info,
    ) {}

    public function index()
    {
        $info = $this->info->getAll();

        return response()->json($info);
    }

    public function show(string $slug)
    {
        $info = $this->info->getBySlug($slug);

        return response()->json($info);
    }
}
