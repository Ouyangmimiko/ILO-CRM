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
        Schema::create('master_records', function (Blueprint $table) {
            $table->uuid('id')->primary(); // use UUID as the primary key

            $table->string('organisation');
            $table->string('organisation_sector')->nullable();
            $table->string('first_name');
            $table->string('surname');
            $table->string('job_title')->nullable();
            $table->string('email')->unique();
            $table->string('location')->nullable();
            $table->enum('uob_alumni', ['yes', 'no'])->nullable();
            $table->string('programme_of_study_engaged')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_records');
    }
};
