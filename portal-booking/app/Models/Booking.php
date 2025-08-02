<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Sesuaikan fillable dengan nama kolom di database yang akan diisi
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'room_id',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Define the relationship to the User model.
     * Sebuah booking dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship to the Room model.
     * Sebuah booking dimiliki oleh satu Room.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
