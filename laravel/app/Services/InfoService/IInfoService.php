<?php

namespace App\Services\InfoService;


interface IInfoService
{
    public function getBySlug(string $slug);
}
