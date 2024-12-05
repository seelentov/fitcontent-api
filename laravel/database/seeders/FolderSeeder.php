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
            'name' => "BODYPUMP",
            'parent_id' => null,
            "icon_url" => "01JD7NC5J4J7M32250Z4DYH7S3.png"
        ]);

        Folder::create([
            'name' => "BODYCOMBAT",
            'parent_id' => null,
            "icon_url" => "01JD7R9VQN0ZAHB0MB48HRQ8DP.png"
        ]);

        Folder::create([
            'name' => "BODYBALACE",
            'parent_id' => null,
            "icon_url" => "01JD7REBB7DJ9PNSB26GR7WAYZ.png"
        ]);

        Folder::create([
            'name' => "RPM",
            'parent_id' => null,
            "icon_url" => "01JD7RFG1E078CCWB5RB0B27HB.png"
        ]);

        foreach (Folder::all() as $folder) {
            for ($i = 0; $i < 4; $i++) {
                Folder::create([
                    'name' => $folder->name . " " . $i,
                    'parent_id' => $folder->id,
                ]);
            }
        }

        foreach (Folder::whereNotNull('parent_id')->get() as $folder){
            Folder::create([
                'name' => 'music' . " " . $folder->name,
                'parent_id' => $folder->id,
            ]);
        }
    }
}
