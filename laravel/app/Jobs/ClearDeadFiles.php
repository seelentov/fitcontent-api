<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ClearDeadFiles implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct()
    {
        $this
            ->onConnection('rabbitmq')
            ->onQueue('default');
    }

    public function handle()
    {
        $db_paths = File::all()->pluck(['path', 'icon_url'])->merge(Folder::all()->pluck('icon_url'));

        $folder_icon_urls = Folder::all()->pluck('icon_url')->toArray();
        $file_urls = File::select(['path', 'icon_url'])->get()->toArray();

        $urls = $folder_icon_urls;

        foreach ($file_urls as $key => $value) {
            $urls[] = $value['path'];
            $urls[] = $value['icon_url'];
        }

        $urls = array_unique($urls);

        $dir_paths = Storage::disk('public')->allFiles();

        $paths_to_delete = array_filter($dir_paths, function ($path) use ($urls) {
            $not_in_array = !in_array($path, $urls);
            $not_secure = !str_starts_with($path, ".");
            $not_seed = !str_starts_with($path, "seed");

            return $not_in_array && $not_secure && $not_seed;
        });

        foreach ($paths_to_delete as $path)
            Storage::disk('public')->delete($path);
    }
}
