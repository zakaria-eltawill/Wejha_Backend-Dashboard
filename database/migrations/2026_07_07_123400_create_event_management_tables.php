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
        // 1. Events Table
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('type'); // Seminar, Workshop, Exhibition
            $table->string('banner_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('speaker')->nullable();
            $table->date('event_date');
            $table->time('event_time');
            $table->string('venue');
            $table->text('venue_map_url')->nullable();
            $table->integer('capacity');
            $table->timestamp('registration_opens_at')->nullable();
            $table->timestamp('registration_closes_at')->nullable();
            $table->boolean('qr_attendance_enabled')->default(true);
            $table->boolean('requires_approval')->default(false);
            $table->string('status')->default('draft'); // draft, published, archived
            $table->string('visibility')->default('public'); // public, private
            $table->boolean('featured')->default(false);
            $table->text('organizer_notes')->nullable();
            $table->string('contact_person')->nullable();
            $table->uuid('creator_id');
            $table->softDeletes();
            $table->timestamps();

            // Foreign Key
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');

            // Indexes
            $table->index('event_date');
            $table->index('status');
            $table->index('creator_id');
            $table->index('created_at');
        });

        // 2. Registrations Table
        Schema::create('registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('event_id');
            $table->string('qr_hash')->unique();
            $table->string('source')->default('web'); // web, mobile, admin
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled, checked_in
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            // Unique Constraint (One registration per user per event)
            $table->unique(['user_id', 'event_id']);

            // Indexes
            $table->index('user_id');
            $table->index('event_id');
            $table->index('status');
            $table->index('registered_at');
        });

        // 3. Attendance Table
        Schema::create('attendance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('registration_id')->unique(); // One attendance per registration
            $table->uuid('scanner_user_id')->nullable();
            $table->timestamp('scan_time')->useCurrent();
            $table->string('device')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->foreign('scanner_user_id')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('registration_id');
            $table->index('scanner_user_id');
            $table->index('scan_time');
        });

        // 4. Event Activities (Timeline)
        Schema::create('event_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id');
            $table->string('description_ar');
            $table->string('description_en');
            $table->string('type'); // event_created, registration_opened, etc.
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();

            // Foreign Key
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            // Indexes
            $table->index('event_id');
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_activities');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('events');
    }
};
