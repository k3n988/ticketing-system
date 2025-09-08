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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('customer_name');
            $table->string('email');
            $table->integer('quantity');
            $table->decimal('price', 8, 2)->nullable(); // optional
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending'); // match form
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
