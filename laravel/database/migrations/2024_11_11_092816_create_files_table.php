<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("path");
            $table->enum("type", ["image", "text", "doc", "audio", "video", "archive", "unknown"])->default('unknown');
            $table->string("icon_url")->default(value: 'seed/file.svg');


            $table->unsignedBigInteger("folder_id")->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
