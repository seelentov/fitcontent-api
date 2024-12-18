<?php

namespace App\Providers;

use App\Models\File;
use App\Models\Folder;
use App\Models\Info;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Services;
use App\Services\FileService\FileService;
use App\Services\FileService\IFileService;
use App\Services\FolderService\FolderService;
use App\Services\FolderService\IFolderService;
use App\Services\InfoService\IInfoService;
use App\Services\InfoService\InfoService;
use App\Services\UserService\IUserService;
use App\Services\UserService\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            IFileService::class,
            function ($app) {
                return new FileService();
            }
        );

        $this->app->bind(
            IUserService::class,
            function ($app) {
                return new UserService($app->make(User::class));
            }
        );

        $this->app->bind(
            IInfoService::class,
            function ($app) {
                return new InfoService($app->make(Info::class));
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
