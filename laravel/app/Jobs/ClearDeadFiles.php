<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        $db_paths = File::all()->pluck('path')->toArray();

        $dir_paths = Storage::disk('public')->allFiles();

        $paths_to_delete = array_filter($dir_paths, function ($path) use ($db_paths) {
            $not_in_array = !in_array($path, $db_paths);
            $not_secure = !str_starts_with($path, ".");
            $not_seed = !str_starts_with($path, "seed");

            return $not_in_array && $not_secure && $not_seed;
        });

        foreach ($paths_to_delete as $path)
            Storage::disk('public')->delete($path);
    }
}
