<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sampleFiles = [
            "seed/sample.jpg" => File::TYPE_IMAGE,
            "seed/sample.mp3" => File::TYPE_AUDIO,
            "seed/sample.mp4" => File::TYPE_VIDEO,
            "seed/sample.pdf" => File::TYPE_DOC,
            "seed/sample.txt" => File::TYPE_TEXT,
        ];

        $folders = Folder::all();

        foreach ($folders as $folder) {
            foreach ($sampleFiles as $path => $type) {
                File::create([
                    'name' => basename($path),
                    'path' => $path,
                    'folder_id' => $folder->id,
                    'type' => $type,
                ]);
            }
        }

        $users = User::all();

        foreach ($users as $user) {
            foreach ($sampleFiles as $path => $type) {
                File::create([
                    'name' => basename($path),
                    'path' => $path,
                    'folder_id' => null,
                    'type' => $type,
                ]);
            }
        }
    }
}
