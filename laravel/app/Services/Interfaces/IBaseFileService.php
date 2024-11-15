<?php


namespace App\Services\Interfaces;

interface IBaseFileService
{
    public function getById(int $id);
    public function getRoot();
}
