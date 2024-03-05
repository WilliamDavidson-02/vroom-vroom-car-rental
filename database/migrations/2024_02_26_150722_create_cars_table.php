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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("brand");
            $table->string("model");
            $table->string("type");
            $table->integer("door_count");
            $table->integer("seat_count");
            $table->boolean("gear_box");
            $table->string("image")->default("default_car.svg");
            $table->integer("year");
            $table->integer("hp");
            $table->string("fuel");
            $table->float("fuel_efficiency");
            $table->string("registration");
            $table->float("price");
            $table->string("drive");
            $table->boolean("available");
            $table->foreignIdFor(User::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
