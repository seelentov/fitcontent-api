<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Folder;
use App\Models\User;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Folder::create([
            'name' => "Music",
            'parent_id' => null,
        ]);

        Folder::create([
            'name' => "Video",
            'parent_id' => null,
        ]);

        Folder::create([
            'name' => "Docs",
            'parent_id' => null,
        ]);

        Folder::create([
            'name' => "Images",
            'parent_id' => null,
        ]);

        foreach (Folder::all() as $folder) {
            for ($i = 0; $i < 4; $i++) {
                Folder::create([
                    'name' => "SubFolder" . $i,
                    'parent_id' => $folder->id,
                ]);
            }
        }
    }
}
