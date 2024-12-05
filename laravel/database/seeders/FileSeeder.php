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
        foreach (Folder::whereNotNull('parent_id')->where("name", 'not like', '%music%')->get() as $folder) {

            File::create([
                'name' => "Doc" . "" . $folder->name,
                'path' => "seed/sample.pdf",
                'folder_id' => $folder->id,
                'type' => File::TYPE_DOC,
            ]);

            File::create([
                'name' => "Video" . "" . $folder->name,
                'path' => "seed/sample.mp4",
                'folder_id' => $folder->id,
                'type' => File::TYPE_DOC,
            ]);
        }

        foreach (Folder::whereNotNull('parent_id')->where("name", 'like', '%music%')->get() as $folder) {
            File::create([
                'name' => "Track" . "1" . $folder->name,
                'path' => "01JD7TXEKMTMFEV4QQPFY8806V.mp3",
                'folder_id' => $folder->id,
                'type' => File::TYPE_AUDIO,
            ]);

            File::create([
                'name' => "Track" . "2" . $folder->name,
                'path' => "01JD7TVVTG273ZSYBFSKHVMA46.mp3",
                'folder_id' => $folder->id,
                'type' => File::TYPE_AUDIO,
            ]);

            File::create([
                'name' => "Track" . "3" . $folder->name,
                'path' => "01JD7TWFAZF13RDFFZMM7SCWKB.mp3",
                'folder_id' => $folder->id,
                'type' => File::TYPE_AUDIO,
            ]);
        }
    }
}
