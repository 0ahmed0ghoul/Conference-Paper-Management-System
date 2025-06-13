<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_id')->index('papers_author_id_foreign');
            $table->string('title');
            $table->text('abstract');
            $table->string('file_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('status')->nullable()->default('submitted');
            $table->timestamps();
            $table->string('paper_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papers');
    }
};
