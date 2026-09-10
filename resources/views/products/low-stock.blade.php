@extends('adminlte::page')

@section('title', 'Low Stock Products')

@section('content_header')

    <div class="d-flex justify-content-between align-items-center">

        <h1>Low Stock Products</h1>

        <a href="{{ route('products.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </div>

@stop

@section('content')

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-exclamation-triangle"></i>
                Products Below Stock Threshold
            </h3>

        </div>

        <div class="card-body">

            <div class="form-group">

                <label for="threshold">
                    Low Stock Threshold
                </label>

                <div class="input-group" style="max-width: 300px;">

                    <input type="number" id="threshold" class="form-control" value="5" min="0">

                    <div class="input-group-append">

                        <button type="button" id="loadProducts" class="btn btn-primary">
                            Check
                        </button>

                    </div>

                </div>

            </div>


            <p>
                <strong>Current Threshold:</strong>
                <span id="currentThreshold">-</span>
            </p>


            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Price</th>
                            <th>Tax</th>
                            <th>Stock</th>
                        </tr>

                    </thead>

                    <tbody id="productsTable">

                        <tr>
                            <td colspan="6" class="text-center">
                                Loading...
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@stop

@section('js')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {

            function loadLowStockProducts() {

                let threshold = $('#threshold').val();

                $.ajax({

                    url: '/api/products/low-stock',

                    type: 'GET',

                    data: {
                        threshold: threshold
                    },

                    dataType: 'json',

                    success: function(data) {

                        $('#currentThreshold').text(data.threshold);

                        let products = data.products;

                        if (products.length === 0) {

                            $('#productsTable').html(`
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                No low-stock products found.
                            </td>
                        </tr>
                    `);

                            return;
                        }

                        let rows = '';

                        products.forEach(function(product, index) {

                            rows += `
                        <tr>

                            <td>
                                ${index + 1}
                            </td>

                            <td>
                                ${product.name}
                            </td>

                            <td>
                                ${product.code}
                            </td>

                            <td>
                                ₹${parseFloat(product.price).toFixed(2)}
                            </td>

                            <td>
                                ${parseFloat(product.tax_percentage).toFixed(2)}%
                            </td>

                            <td>
                                <span class="badge badge-warning">
                                    ${product.stock}
                                </span>
                            </td>

                        </tr>
                    `;

                        });

                        $('#productsTable').html(rows);

                    },

                    error: function(xhr) {

                        $('#productsTable').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger py-4">
                            Unable to load low-stock products.
                        </td>
                    </tr>
                `);

                    }

                });

            }


            // Load when page opens

            loadLowStockProducts();


            // Load when threshold changes

            $('#loadProducts').on('click', function() {

                loadLowStockProducts();

            });

        });
    </script>

@stop
