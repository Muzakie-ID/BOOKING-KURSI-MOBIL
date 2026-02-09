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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('route_id')->constrained();
            $table->foreignId('schedule_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name');
            $table->string('user_phone');
            $table->text('pickup_location');
            $table->foreignId('drop_off_point_id')->constrained();
            $table->integer('quantity');
            $table->integer('total_price')->nullable();
            $table->string('payment_method');
            $table->string('status')->default('pending'); // pending, assigned, paid, seated, completed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
