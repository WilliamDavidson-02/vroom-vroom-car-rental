<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        "brand",
        "model",
        "type",
        "door_count",
        "seat_count",
        "gear_box",
        "year",
        "hp",
        "fuel",
        "fuel_efficiency",
        "registration",
        "price",
        "drive",
        "available",
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
    public function Reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function availableForBooking($startDate, $endDate, $country = null)
    {
        $query = $this->bookings()->where(function ($query) use ($startDate, $endDate) {
            $query->where('start_date', '>', $endDate)
                ->orWhere('end_date', '<', $startDate)
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '>=', $startDate)
                        ->where('start_date', '<=', $endDate);
                });
        });

        // if ($country) {
        //     $query->whereHas('owner', function ($query) use ($country) {
        //         $query->where('country', $country);
        //     });
        // }

        return $query->get();
    }
}
