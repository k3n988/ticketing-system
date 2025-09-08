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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('event_name');
            $table->string('venue')->nullable()->after('order_number');
            $table->string('university')->nullable()->after('venue');
            $table->date('date')->nullable()->after('university');
            $table->time('time')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['order_number', 'venue', 'university', 'date', 'time']);
        });
    }
};
