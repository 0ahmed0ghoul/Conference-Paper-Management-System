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
        Schema::table('review_assignments', function (Blueprint $table) {
            $table->foreign(['paper_id'])->references(['id'])->on('papers')->onDelete('CASCADE');
            $table->foreign(['assigned_by'])->references(['id'])->on('users')->onDelete('CASCADE');
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
        Schema::table('review_assignments', function (Blueprint $table) {
            $table->dropForeign('review_assignments_paper_id_foreign');
            $table->dropForeign('review_assignments_assigned_by_foreign');
            $table->dropForeign('review_assignments_reviewer_id_foreign');
        });
    }
};
