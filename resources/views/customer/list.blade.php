@extends('adminlte::page')

@section('title', 'Customer')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Customers</h1>
    </div>
@stop

@section('content')

    <div class="card">

        <div class="card-header">
            <form method="GET" action="{{ route('products.list') }}">
                <div class="row">

                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search Customer name or email..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-secondary">
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
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($customers as $customer)
                            <tr>

                                <td>
                                    {{ $customers->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $customer->name }}
                                </td>

                                <td>
                                    {{ $customer->email }}
                                </td>

                                <td>
                                    <a href="{{ route('orders.customerList', $customer->email) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Order History
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if ($customers->hasPages())
            <div class="card-footer">
                {{ $customers->links() }}
            </div>
        @endif

    </div>

@stop
