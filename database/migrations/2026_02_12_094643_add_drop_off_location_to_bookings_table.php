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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('drop_off_location')->nullable()->after('pickup_location');
            $table->foreignId('drop_off_point_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('drop_off_location');
            // Assuming drop_off_point_id was NOT nullable before
            //$table->foreignId('drop_off_point_id')->nullable(false)->change(); 
        });
    }
};
