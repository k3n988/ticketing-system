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
            if (!Schema::hasColumn('tickets', 'event_name')) {
                $table->string('event_name')->after('id');
            }
            if (!Schema::hasColumn('tickets', 'customer_name')) {
                $table->string('customer_name')->after('event_name');
            }
            if (!Schema::hasColumn('tickets', 'email')) {
                $table->string('email')->after('customer_name');
            }
            if (!Schema::hasColumn('tickets', 'quantity')) {
                $table->integer('quantity')->after('email');
            }
            if (!Schema::hasColumn('tickets', 'price')) {
                $table->decimal('price', 8, 2)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('tickets', 'status')) {
                $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending')->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['event_name', 'customer_name', 'email', 'quantity', 'price', 'status']);
        });
    }
};
