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
        Schema::table('events', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('event_date');
            $table->time('end_time')->nullable()->after('event_time');
            $table->text('recording_url')->nullable()->after('venue_map_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['end_date', 'end_time', 'recording_url']);
        });
    }
};
