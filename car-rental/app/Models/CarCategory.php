<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class CarCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image_url',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class, 'category_id');
    }

    public function getAvailableCarsCount($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return $this->cars()->count();
        }

        return $this->cars()->where('is_available', true)
            ->whereDoesntHave('bookings', function ($query) use ($startDate, $endDate) {
                $query->where('status', '!=', 'cancelled')
                      ->where(function ($q) use ($startDate, $endDate) {
                          $q->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($q2) use ($startDate, $endDate) {
                                $q2->where('start_date', '<=', $startDate)
                                   ->where('end_date', '>=', $endDate);
                            });
                      });
            })->count();
    }
}
