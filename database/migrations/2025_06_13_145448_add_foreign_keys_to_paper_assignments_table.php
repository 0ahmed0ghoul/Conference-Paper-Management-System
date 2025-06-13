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
        Schema::table('paper_assignments', function (Blueprint $table) {
            $table->foreign(['paper_id'])->references(['id'])->on('papers')->onDelete('CASCADE');
            $table->foreign(['assigned_by'])->references(['id'])->on('users');
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
        Schema::table('paper_assignments', function (Blueprint $table) {
            $table->dropForeign('paper_assignments_paper_id_foreign');
            $table->dropForeign('paper_assignments_assigned_by_foreign');
            $table->dropForeign('paper_assignments_reviewer_id_foreign');
        });
    }
};
