<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        "owner_id",
        "renter_id",
        "car_id",
        "start_date",
        "end_date",
        "total_price",
        "accepted",
        "completed"
    ];

    public function owner(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function renter(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
