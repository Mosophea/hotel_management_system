@extends('layouts.app') 

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Payments</h2>
    <a class="btn btn-primary" href="{{ route('payments.create') }}">+ Add New</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Paid At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>#{{ $item->booking_id }} — {{ $item->booking?->guest?->name ?? 'N/A' }}</td>
                    <td>${{ number_format($item->amount, 2) }}</td>
                    <td>{{ strtoupper($item->payment_method) }}</td>
                    <td>{{ $item->paid_at }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('payments.edit', $item) }}">Edit</a> 
                        <form class="d-inline" method="POST" action="{{ route('payments.destroy', $item) }}" onsubmit="return confirm('Delete this record?')">
                            @csrf 
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $items->links() }}
</div>
@endsection