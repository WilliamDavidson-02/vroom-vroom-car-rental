<?php

use App\Models\User;
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
            $table->timestamps();
            $table->foreignId("owner_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("renter_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("car_id")->constrained("cars")->onDelete("cascade");
            $table->float("total_price");
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->boolean("accepted")->nullable();
            $table->boolean("completed")->default(false);
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
