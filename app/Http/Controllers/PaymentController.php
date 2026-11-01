<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $r)
    {
        $items = Payment::with(['booking.guest'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('payments.index', compact('items'));
    }

    public function create()
    {
        return view('payments.form', [
            'item' => new Payment(),
            'bookings' => Booking::all()
        ]);
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'paid_at' => 'required|date',
            'status' => 'required|string'
        ]);

        $d['user_id'] = Auth::id();

        Payment::create($d);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function edit(Payment $payment)
    {
        return view('payments.form', [
            'item' => $payment,
            'bookings' => Booking::all()
        ]);
    }

    public function update(Request $r, Payment $payment)
    {
        $d = $r->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'paid_at' => 'required|date',
            'status' => 'required|string'
        ]);

        $payment->update($d);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return back()->with('success', 'Payment deleted successfully.');
    }
}
