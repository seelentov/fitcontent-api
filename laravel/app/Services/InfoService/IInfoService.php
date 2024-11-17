<?php

namespace App\Services\InfoService;


interface IInfoService
{
    public function getAll();
    public function getBySlug(string $slug);
}
