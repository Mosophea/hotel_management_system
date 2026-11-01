<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // 1. Import Auth facade

class BookingController extends Controller
{
    public function index(Request $r)
    {
        $items = Booking::with(['guest', 'room.roomType'])->when($r->search, fn($q) => $q->whereHas('guest', fn($g) => $g->where('name', 'like', '%' . $r->search . '%')))->latest()->paginate(10)->withQueryString();
        return view('bookings.index', compact('items'));
    }
    public function create()
    {
        return view('bookings.form', ['item' => new Booking, 'guests' => Guest::all(), 'rooms' => Room::with('roomType')->where('status', 'available')->get()]);
    }
    public function store(Request $r)
    {
        $d = $r->validate(['guest_id' => 'required|exists:guests,id', 'room_id' => 'required|exists:rooms,id', 'check_in' => 'required|date|after_or_equal:today', 'check_out' => 'required|date|after:check_in', 'status' => 'required|in:confirmed,checked_in,checked_out,cancelled']);
        $room = Room::with('roomType')->findOrFail($d['room_id']);
        $overlap = Booking::where('room_id', $room->id)->whereIn('status', ['confirmed', 'checked_in'])->where('check_in', '<', $d['check_out'])->where('check_out', '>', $d['check_in'])->exists();
        if ($overlap) return back()->withErrors(['room_id' => 'Room is already booked for these dates.'])->withInput();
        
        $n = (new \DateTime($d['check_in']))->diff(new \DateTime($d['check_out']))->days;
        $d['total_amount'] = $n * $room->roomType->price_per_night; // 2. Added missing '*'
        $d['user_id'] = Auth::id(); // 3. Fixed Intelephense error using Auth facade
        
        Booking::create($d);
        return redirect()->route('bookings.index')->with('success', 'Booking created.');
    }
    public function show(Booking $booking)
    {
        return redirect()->route('bookings.index');
    }
    public function edit(Booking $booking)
    {
        return view('bookings.form', ['item' => $booking, 'guests' => Guest::all(), 'rooms' => Room::with('roomType')->get()]);
    }
    public function update(Request $r, Booking $booking)
    {
        $d = $r->validate(['guest_id' => 'required|exists:guests,id', 'room_id' => 'required|exists:rooms,id', 'check_in' => 'required|date', 'check_out' => 'required|date|after:check_in', 'status' => 'required|in:confirmed,checked_in,checked_out,cancelled']);
        $room = Room::with('roomType')->findOrFail($d['room_id']);
        $overlap = Booking::where('room_id', $room->id)->where('id', '!=', $booking->id)->whereIn('status', ['confirmed', 'checked_in'])->where('check_in', '<', $d['check_out'])->where('check_out', '>', $d['check_in'])->exists();
        if ($overlap) return back()->withErrors(['room_id' => 'Room is already booked for these dates.'])->withInput();
        
        $d['total_amount'] = (new \DateTime($d['check_in']))->diff(new \DateTime($d['check_out']))->days * $room->roomType->price_per_night; // 4. Added missing '*'
        
        $booking->update($d);
        return redirect()->route('bookings.index')->with('success', 'Booking updated.');
    }
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Booking deleted.');
    }
}