<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $r)
    {
        $items = Room::with('roomType')->where('room_number', 'like', '%' . $r->query('search') . '%')->when($r->status, fn($q) => $q->where('status', $r->status))->latest()->paginate(10)->withQueryString();
        return view('rooms.index', compact('items'));
    }
    public function create()
    {
        return view('rooms.form', ['item' => new Room, 'types' => RoomType::all()]);
    }
    public function store(Request $r)
    {
        Room::create($r->validate(['room_number' => 'required|max:20|unique:rooms', 'room_type_id' => 'required|exists:room_types,id', 'floor' => 'required|integer|min:0', 'status' => 'required|in:available,occupied,maintenance']));
        return redirect()->route('rooms.index')->with('success', 'Room created.');
    }
    public function show(Room $room)
    {
        return redirect()->route('rooms.index');
    }
    public function edit(Room $room)
    {
        return view('rooms.form', ['item' => $room, 'types' => RoomType::all()]);
    }
    public function update(Request $r, Room $room)
    {
        $room->update($r->validate(['room_number' => 'required|max:20|unique:rooms,room_number,' . $room->id, 'room_type_id' => 'required|exists:room_types,id', 'floor' => 'required|integer|min:0', 'status' => 'required|in:available,occupied,maintenance']));
        return redirect()->route('rooms.index')->with('success', 'Room updated.');
    }
    public function destroy(Room $room)
    {
        if ($room->bookings()->exists()) return back()->with('error', 'Room has booking records and cannot be deleted.');
        $room->delete();
        return back()->with('success', 'Room deleted.');
    }
}
