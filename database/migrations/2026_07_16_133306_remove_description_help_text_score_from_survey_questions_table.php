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
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropColumn(['description_ar', 'description_en', 'help_text_ar', 'help_text_en', 'score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('help_text_ar')->nullable();
            $table->text('help_text_en')->nullable();
            $table->integer('score')->default(0);
        });
    }
};
