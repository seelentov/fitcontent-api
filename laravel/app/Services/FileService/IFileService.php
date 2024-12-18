<?php

namespace App\Services\FileService;

use App\Services\Interfaces\IBaseFileService;

interface IFileService
{
    public function getFile(string $id);
    public function getFolder(string $id);
    public function getRoot();
}