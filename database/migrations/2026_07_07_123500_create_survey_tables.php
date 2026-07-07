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
        // 1. Survey Templates Table
        Schema::create('survey_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('version')->default('1.0.0');
            $table->string('status')->default('draft'); // draft, active, archived
            $table->string('category')->nullable();
            $table->boolean('is_reusable')->default(true);
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('created_at');
        });

        // 2. Survey Questions Table
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('survey_template_id');
            $table->string('type'); // text, textarea, rating, multiple_choice, checkbox, number, date, email, phone
            $table->string('question_text_ar');
            $table->string('question_text_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('help_text_ar')->nullable();
            $table->text('help_text_en')->nullable();
            $table->jsonb('options')->nullable(); // For multiple choice / checkbox options
            $table->boolean('is_required')->default(true);
            $table->integer('score')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Foreign Key
            $table->foreign('survey_template_id')->references('id')->on('survey_templates')->onDelete('cascade');

            // Indexes
            $table->index('survey_template_id');
            $table->index('sort_order');
        });

        // 3. Event Evaluations Table
        Schema::create('event_evaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id');
            $table->uuid('survey_template_id');
            $table->string('evaluation_type'); // pre, post
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('survey_template_id')->references('id')->on('survey_templates')->onDelete('restrict');

            // Constraints
            // One evaluation of each type per event
            $table->unique(['event_id', 'evaluation_type']);

            // Indexes
            $table->index('event_id');
            $table->index('survey_template_id');
            $table->index('evaluation_type');
        });

        // 4. Survey Responses Table
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('event_evaluation_id');
            $table->uuid('question_id');
            $table->text('response_text')->nullable();
            $table->jsonb('response_json')->nullable(); // For checkbox array, multi-select, complex inputs
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_evaluation_id')->references('id')->on('event_evaluations')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('survey_questions')->onDelete('cascade');

            // Constraints
            // One survey response per user per question per evaluation session
            $table->unique(['user_id', 'event_evaluation_id', 'question_id']);

            // Indexes
            $table->index(['user_id', 'event_evaluation_id', 'question_id'], 'user_eval_question_unique_idx');
            $table->index('user_id');
            $table->index('event_evaluation_id');
            $table->index('question_id');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('event_evaluations');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('survey_templates');
    }
};
