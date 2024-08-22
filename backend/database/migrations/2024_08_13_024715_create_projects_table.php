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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid('master_record_id');
            $table->foreign('master_record_id')
                ->references('id')
                ->on('master_records')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('academic_year');
            $table->string('project_client')->nullable();
            $table->timestamps();

            // make sure foreign_key-year  unique
            $table->unique(['master_record_id', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['master_record_id']);
        });
        Schema::dropIfExists('projects');
    }
};
