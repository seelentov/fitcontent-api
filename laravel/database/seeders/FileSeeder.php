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

        $music_folder = Folder::where("name", 'Music')->first();
        $music_folders = File::where('parent_id', $music_folder->id->get())->get();

        foreach ($music_folders as $folder) {

            for ($i = 0; $i < 8; $i++) {
                File::create([
                    'name' => "Track" . $i,
                    'path' => "seed/sample.mp3",
                    'folder_id' => $folder->id,
                    'type' => File::TYPE_AUDIO,
                ]);
            }
        }

        $video_folder = Folder::where("name", 'Video')->first();
        $video_folders = File::where('parent_id', $video_folder->id->get())->get();

        foreach ($video_folders as $folder) {

            for ($i = 0; $i < 8; $i++) {
                File::create([
                    'name' => "Video" . $i,
                    'path' => "seed/sample.mp4",
                    'folder_id' => $folder->id,
                    'type' => File::TYPE_VIDEO,
                ]);
            }
        }

        $docs_folder = Folder::where("name", 'Docs')->first();
        $docs_folders = File::where('parent_id', $docs_folder->id->get())->get();

        foreach ($docs_folders as $folder) {

            for ($i = 0; $i < 8; $i++) {
                File::create([
                    'name' => "Doc" . $i,
                    'path' => "seed/sample.pdf",
                    'folder_id' => $folder->id,
                    'type' => File::TYPE_DOC,
                ]);
            }
        }

        $image_folder = Folder::where("name", 'Images')->first();
        $image_folders = File::where('parent_id', $image_folder->id->get())->get();

        foreach ($image_folders as $folder) {

            for ($i = 0; $i < 8; $i++) {
                File::create([
                    'name' => "Image" . $i,
                    'path' => "seed/sample.jpg",
                    'folder_id' => $folder->id,
                    'type' => File::TYPE_IMAGE,
                ]);
            }
        }
    }
}
