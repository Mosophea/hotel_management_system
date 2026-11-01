<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $r)
    {
        $items = Guest::where('name', 'like', '%' . $r->query('search') . '%')->orWhere('email', 'like', '%' . $r->query('search') . '%')->latest()->paginate(10)->withQueryString();
        return view('guests.index', compact('items'));
    }
    public function create()
    {
        return view('guests.form', ['item' => new Guest]);
    }
    public function store(Request $r)
    {
        Guest::create($r->validate(['name' => 'required|max:100', 'email' => 'nullable|email', 'phone' => 'required|max:30', 'address' => 'nullable|string']));
        return redirect()->route('guests.index')->with('success', 'Guest created.');
    }
    public function show(Guest $guest)
    {
        return redirect()->route('guests.index');
    }
    public function edit(Guest $guest)
    {
        return view('guests.form', ['item' => $guest]);
    }
    public function update(Request $r, Guest $guest)
    {
        $guest->update($r->validate(['name' => 'required|max:100', 'email' => 'nullable|email', 'phone' => 'required|max:30', 'address' => 'nullable|string']));
        return redirect()->route('guests.index')->with('success', 'Guest updated.');
    }
    public function destroy(Guest $guest)
    {
        if ($guest->bookings()->exists()) return back()->with('error', 'Guest has booking records.');
        $guest->delete();
        return back()->with('success', 'Guest deleted.');
    }
}
