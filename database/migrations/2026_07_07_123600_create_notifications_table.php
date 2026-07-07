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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('content_ar');
            $table->text('content_en');
            $table->string('recipient_type'); // individual, role, event, all
            $table->uuid('user_id')->nullable(); // Target user (individual)
            $table->unsignedBigInteger('role_id')->nullable(); // Target role (role-based)
            $table->uuid('event_id')->nullable(); // Target event attendees (event-based)
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('status')->default('draft'); // draft, scheduled, processing, sent, failed
            $table->jsonb('delivery_logs')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('role_id');
            $table->index('event_id');
            $table->index('status');
            $table->index('scheduled_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
