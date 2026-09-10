@extends('adminlte::page')

@section('title', 'Products')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Products</h1>

        {{-- <button class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </button> --}}

        <a href="{{ route('products.lowStock') }}" class="btn btn-warning">

            <i class="fas fa-exclamation-triangle"></i>
            Low Stock

        </a>
    </div>
@stop

@section('content')

    <div class="card">

        <div class="card-header">
            <form method="GET" action="{{ route('products.list') }}">
                <div class="row">

                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search product name or code..." value="{{ request('search') }}">
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
                            <th>Product</th>
                            <th>Code</th>
                            <th>Price / Unit</th>
                            <th>Tax %</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)
                            <tr>

                                <td>
                                    {{ $products->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>{{ $product->name }}</strong>
                                </td>

                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $product->code }}
                                    </span>
                                </td>

                                <td>
                                    ₹{{ number_format($product->price, 2) }}
                                </td>

                                <td>
                                    {{ number_format($product->tax_percentage, 2) }}%
                                </td>

                                <td>
                                    {{ $product->stock }}
                                </td>

                                <td>

                                    @if ($product->stock < $lowStockThreshold)
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i>
                                            In Stock
                                        </span>
                                    @endif

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

        @if ($products->hasPages())
            <div class="card-footer">
                {{ $products->links() }}
            </div>
        @endif

    </div>

@stop
