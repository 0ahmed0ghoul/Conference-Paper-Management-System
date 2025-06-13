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
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('paper_id')->index('reviews_paper_id_foreign');
            $table->unsignedBigInteger('reviewer_id')->index('reviews_reviewer_id_foreign');
            $table->unsignedBigInteger('assignment_id')->nullable()->index('reviews_assignment_id_foreign');
            $table->tinyInteger('score');
            $table->text('comments')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->dateTime('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
