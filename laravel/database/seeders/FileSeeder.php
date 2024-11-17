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
        $music_folder = Folder::where("name", 'Music')->with("folders")->get();

        foreach ($music_folder->folders as $folder) {

            for ($i = 0; $i < 8; $i++) {
                File::create([
                    'name' => "Track" . $i,
                    'path' => "seed/sample.mp3",
                    'folder_id' => $folder->id,
                    'type' => File::TYPE_AUDIO,
                ]);
            }
        }

        $video_folder = Folder::where("name", 'Video')->with("folders")->get();

        foreach ($video_folder->folders as $folder) {

            for ($i = 0; $i < 8; $i++) {
                File::create([
                    'name' => "Video" . $i,
                    'path' => "seed/sample.mp4",
                    'folder_id' => $folder->id,
                    'type' => File::TYPE_VIDEO,
                ]);
            }
        }


        $doc_folder = Folder::where("name", 'Docs')->with("folders")->get();

        foreach ($doc_folder->folders as $folder) {

            for ($i = 0; $i < 8; $i++) {
                File::create([
                    'name' => "Doc" . $i,
                    'path' => "seed/sample.pdf",
                    'folder_id' => $folder->id,
                    'type' => File::TYPE_DOC,
                ]);
            }
        }

        $image_folder = Folder::where("name", 'Images')->with("folders")->get();

        foreach ($image_folder->folders as $folder) {

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
