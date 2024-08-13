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
        Schema::create('mentoring_periods', function (Blueprint $table) {
            $table->id();
            $table->uuid('master_id');
            $table->foreign('master_id')
                ->references('id')
                ->on('master_records')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('academic_year');
            $table->enum('mentoring_status', ['yes', 'no'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_period');
    }
};
