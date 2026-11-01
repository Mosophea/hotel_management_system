<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index(Request $r)
    {
        $items = RoomType::withCount('rooms')->where('name', 'like', '%' . $r->query('search') . '%')->latest()->paginate(10)->withQueryString();
        return view('room-types.index', compact('items'));
    }
    public function create()
    {
        return view('room-types.form', ['item' => new RoomType]);
    }
    public function store(Request $r)
    {
        RoomType::create($r->validate(['name' => 'required|max:100', 'description' => 'nullable|string', 'price_per_night' => 'required|numeric|min:0']));
        return redirect()->route('room-types.index')->with('success', 'Room type created.');
    }
    public function edit(RoomType $room_type)
    {
        return view('room-types.form', ['item' => $room_type]);
    }
    public function update(Request $r, RoomType $room_type)
    {
        $room_type->update($r->validate(['name' => 'required|max:100', 'description' => 'nullable|string', 'price_per_night' => 'required|numeric|min:0']));
        return redirect()->route('room-types.index')->with('success', 'Room type updated.');
    }
    public function destroy(RoomType $room_type)
    {
        if ($room_type->rooms()->exists()) return back()->with('error', 'Cannot delete a type with rooms.');
        $room_type->delete();
        return back()->with('success', 'Deleted.');
    }
}
