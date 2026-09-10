@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <div class="row">

        {{-- Products --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $productCount }}</h3>
                    <p>Products</p>
                </div>

                <div class="icon m-2">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $orderCount }}</h3>
                    <p>Orders</p>
                </div>

                <div class="icon  m-2">
                    <i class="bi bi-cart"></i>
                </div>
            </div>
        </div>

        {{-- Customers --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $customerCount }}</h3>
                    <p>Customers</p>
                </div>

                <div class="icon  m-2">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $lowStockProducts->count() }}</h3>
                    <p>Low Stock</p>
                </div>

                <div class="icon  m-2">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>

    </div>

@stop
