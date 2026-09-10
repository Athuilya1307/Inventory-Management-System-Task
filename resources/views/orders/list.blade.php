@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Orders</h1>
        <button class="btn btn-primary">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Order
            </a>
        </button>
    </div>
@stop

@section('content')

    <div class="card">

        <div class="card-header">
            <form method="GET" action="{{ route('orders.list') }}">
                <div class="row">

                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search customer name or email..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Subtotal</th>
                            <th>Tax</th>
                            <th>Grand Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($orders as $order)
                            <tr>

                                <td>
                                    #{{ $order->id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $order->customer->name ?? 'N/A' }}
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $order->customer->email ?? '' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $order->orderItems->sum('quantity') }}
                                </td>

                                <td>
                                    ₹{{ number_format($order->subtotal, 2) }}
                                </td>

                                <td>
                                    ₹{{ number_format($order->tax, 2) }}
                                </td>

                                <td>
                                    <strong>
                                        ₹{{ number_format($order->grand_total, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if ($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

@stop
