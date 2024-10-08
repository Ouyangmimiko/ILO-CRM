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
        Schema::create('other_engagement', function (Blueprint $table) {
            $table->id();
            $table->uuid('master_record_id');
            $table->foreign('master_record_id')
                ->references('id')
                ->on('master_records')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('society_engaged_or_to_engage')->nullable();
            $table->string('engagement_type')->nullable();
            $table->string('engagement_happened')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_engagement', function (Blueprint $table) {
            $table->dropForeign(['master_record_id']);
        });
        Schema::dropIfExists('other_engagement');
    }
};
