<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['guest_id', 'room_id', 'user_id', 'check_in', 'check_out', 'total_amount', 'status'];
    protected $casts = ['check_in' => 'date', 'check_out' => 'date'];
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
