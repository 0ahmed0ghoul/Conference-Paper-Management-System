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
        Schema::create('paper_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reviewer_id')->index('paper_assignments_reviewer_id_foreign');
            $table->unsignedBigInteger('paper_id')->index('paper_assignments_paper_id_foreign');
            $table->timestamps();
            $table->unsignedBigInteger('assigned_by')->index('paper_assignments_assigned_by_foreign');
            $table->string('status')->default('assigned');
            $table->timestamp('assigned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paper_assignments');
    }
};
