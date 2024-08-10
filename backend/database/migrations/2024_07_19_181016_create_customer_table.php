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
        Schema::create('customer', function (Blueprint $table) {
            // ID字段（主键）
            $table->string('id')->primary(); //自定义主键写法
            //$table->id('increment_id'); // 自增辅助字段

            // 其他字段
            $table->string('organisation')->nullable();
            $table->string('organisation_sector')->nullable();
            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('job_title')->nullable();
            $table->string('email')->unique();
            $table->string('location')->nullable();
            $table->enum('uob_alumni', ['yes', 'no'])->nullable();
            $table->string('programme_of_study_engaged')->nullable();
            $table->enum('mentoring_2021_22', ['yes', 'no'])->nullable();
            $table->enum('mentoring_2022_23', ['yes', 'no'])->nullable();
            $table->enum('mentoring_2023_24', ['yes', 'no'])->nullable();
            $table->enum('yii_2021_22', ['yes', 'no'])->nullable();
            $table->enum('yii_2022_23', ['yes', 'no'])->nullable();
            $table->enum('yii_2023_24', ['yes', 'no'])->nullable();
            $table->enum('projects_2021_22', ['yes', 'no'])->nullable();
            $table->enum('projects_2022_23', ['yes', 'no'])->nullable();
            $table->enum('projects_2023_24', ['yes', 'no'])->nullable();
            $table->text('info_related_to_contacting_the_partner')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
