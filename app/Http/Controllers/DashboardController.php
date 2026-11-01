<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Guest;
use App\Models\Booking;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', ['rooms' => Room::count(), 'guests' => Guest::count(), 'bookings' => Booking::count(), 'revenue' => Payment::where('status', 'paid')->sum('amount'), 'recent' => Booking::with(['guest', 'room'])->latest()->take(5)->get()]);
    }
}
