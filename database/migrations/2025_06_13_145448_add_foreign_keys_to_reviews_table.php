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
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign(['paper_id'])->references(['id'])->on('papers')->onDelete('CASCADE');
            $table->foreign(['assignment_id'])->references(['id'])->on('paper_assignments')->onDelete('CASCADE');
            $table->foreign(['reviewer_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign('reviews_paper_id_foreign');
            $table->dropForeign('reviews_assignment_id_foreign');
            $table->dropForeign('reviews_reviewer_id_foreign');
        });
    }
};
