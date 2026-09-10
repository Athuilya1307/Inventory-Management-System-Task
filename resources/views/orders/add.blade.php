@extends('adminlte::page')

@section('title', 'Create Order')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Create Order</h1>

        <a href="{{ route('orders.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@stop

@section('content')

    <div id="message"></div>

    <form id="orderForm">

        {{-- Customer Details --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Customer Details
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_name">
                                Customer Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" id="customer_name" class="form-control" placeholder="Enter customer name"
                                required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_email">
                                Customer Email <span class="text-danger">*</span>
                            </label>

                            <input type="email" id="customer_email" class="form-control"
                                placeholder="Enter customer email" required>
                        </div>
                    </div>

                </div>

            </div>
        </div>


        {{-- Products --}}
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h3 class="card-title">
                    <i class="fas fa-box"></i> Products
                </h3>

                <button type="button" class="btn btn-primary btn-sm" id="addProductBtn">
                    <i class="fas fa-plus"></i> Add Product
                </button>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th style="width: 30%;">Product</th>
                                <th>Price</th>
                                <th>Tax %</th>
                                <th>Stock</th>
                                <th style="width: 120px;">Quantity</th>
                                <th>Total</th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>

                        <tbody id="productRows">

                            {{-- First Product Row --}}
                            <tr>
                                <td>
                                    <select class="form-control product-select" required>
                                        <option value="">Select Product</option>

                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="price">₹0.00</td>

                                <td class="tax">0%</td>

                                <td class="stock">-</td>

                                <td>
                                    <input type="number" class="form-control quantity" min="1" value="1"
                                        disabled required>
                                </td>

                                <td class="line-total">₹0.00</td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Order Summary --}}
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-receipt"></i> Order Summary
                </h3>
            </div>

            <div class="card-body">

                <div class="row justify-content-end">

                    <div class="col-md-4">

                        <table class="table">

                            <tr>
                                <th>Subtotal</th>
                                <td class="text-right">
                                    ₹<span id="subtotal">0.00</span>
                                </td>
                            </tr>

                            <tr>
                                <th>Tax</th>
                                <td class="text-right">
                                    ₹<span id="tax">0.00</span>
                                </td>
                            </tr>

                            <tr>
                                <th>Grand Total</th>
                                <td class="text-right">
                                    <strong>
                                        ₹<span id="grandTotal">0.00</span>
                                    </strong>
                                </td>
                            </tr>

                        </table>

                    </div>

                </div>

            </div>

            <div class="card-footer text-right">

                <button type="submit" class="btn btn-success" id="submitBtn">
                    <i class="fas fa-check"></i>
                    Create Order
                </button>

            </div>

        </div>

    </form>

@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let products = [];
        let rowCount = 1;

        // Add Product
        function addProductRow() {

            rowCount++;

            let row = `
                <tr data-row="${rowCount}">

                    <td>
                        <select class="form-control product-select" required>
                            <option value="">Select Product</option>

                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach

                        </select>
                    </td>

                    <td class="price">₹0.00</td>

                    <td class="tax">0%</td>

                    <td class="stock">-</td>

                    <td>
                        <input
                            type="number"
                            class="form-control quantity"
                            min="1"
                            value="1"
                            disabled
                            required
                        >
                    </td>

                    <td class="line-total">₹0.00</td>

                    <td class="text-center">
                        <button
                            type="button"
                            class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>

                </tr>
            `;

            $('#productRows').append(row);

            calculateTotals();
        }


        $(document).on('change', '.product-select', function() {

            let row = $(this).closest('tr');
            let productId = $(this).val();

            if (!productId) {

                row.removeData('product');

                row.find('.price').text('₹0.00');
                row.find('.tax').text('0%');
                row.find('.stock').text('-');

                row.find('.quantity')
                    .prop('disabled', true)
                    .val(1)
                    .removeAttr('max');

                row.find('.line-total').text('₹0.00');

                calculateTotals();

                return;
            }


            $.ajax({

                url: '/api/products/' + productId,

                type: 'GET',

                dataType: 'json',

                success: function(product) {

                    console.log('Product:', product);


                    // IMPORTANT:
                    // Store product data inside this row
                    row.data('product', product);


                    // Price
                    row.find('.price').text(
                        '₹' + parseFloat(product.price).toFixed(2)
                    );


                    // Tax
                    row.find('.tax').text(
                        parseFloat(product.tax_percentage).toFixed(2) + '%'
                    );


                    // Stock
                    row.find('.stock').text(product.stock);


                    // Enable quantity
                    row.find('.quantity')
                        .prop('disabled', false)
                        .attr('max', product.stock)
                        .val(1);


                    // Calculate row
                    calculateRowTotal(row);
                },

                error: function(xhr) {

                    console.log(xhr);

                    row.removeData('product');

                    row.find('.price').text('₹0.00');
                    row.find('.tax').text('0%');
                    row.find('.stock').text('-');
                    row.find('.line-total').text('₹0.00');

                    row.find('.quantity')
                        .prop('disabled', true)
                        .val(1);

                    calculateTotals();
                }

            });

        });

        $(document).on('input change', '.quantity', function() {

            let row = $(this).closest('tr');
            let product = row.data('product');


            if (!product) {
                return;
            }


            let quantity = parseInt($(this).val()) || 0;


            // Check stock
            if (quantity > parseInt(product.stock)) {

                quantity = parseInt(product.stock);

                $(this).val(quantity);

                showMessage(
                    `Only ${product.stock} units available.`,
                    'warning'
                );
            }


            // Minimum quantity
            if (quantity < 1) {

                quantity = 1;

                $(this).val(1);
            }


            calculateRowTotal(row);

        });

        $(document).on('click', '.remove-row', function() {

            $(this).closest('tr').remove();

            calculateTotals();
        });

        function calculateRowTotal(row) {

            let product = row.data('product');

            if (!product) {

                row.find('.line-total').text('₹0.00');

                calculateTotals();

                return;
            }


            let quantity = parseInt(
                row.find('.quantity').val()
            ) || 0;


            let price = parseFloat(product.price) || 0;

            let taxPercentage =
                parseFloat(product.tax_percentage) || 0;


            // Subtotal for this product
            let lineSubtotal =
                price * quantity;


            // Tax for this product
            let lineTax =
                lineSubtotal * (taxPercentage / 100);


            // Total including tax
            let lineTotal =
                lineSubtotal + lineTax;


            console.log({
                product: product.name,
                price: price,
                quantity: quantity,
                subtotal: lineSubtotal,
                tax: lineTax,
                total: lineTotal
            });


            row.find('.line-total').text(
                '₹' + lineTotal.toFixed(2)
            );


            calculateTotals();

        }

        function calculateTotals() {

            let subtotal = 0;

            let tax = 0;


            $('#productRows tr').each(function() {

                let row = $(this);

                let product = row.data('product');


                if (!product) {
                    return;
                }


                let quantity = parseInt(
                    row.find('.quantity').val()
                ) || 0;


                let price =
                    parseFloat(product.price) || 0;


                let taxPercentage =
                    parseFloat(product.tax_percentage) || 0;

                let lineSubtotal =
                    price * quantity;

                let lineTax =
                    lineSubtotal * (taxPercentage / 100);


                subtotal += lineSubtotal;

                tax += lineTax;

            });


            let grandTotal =
                subtotal + tax;

            $('#subtotal').text(
                subtotal.toFixed(2)
            );


            $('#tax').text(
                tax.toFixed(2)
            );


            $('#grandTotal').text(
                grandTotal.toFixed(2)
            );

        }

        // Submit Order-

        $('#orderForm').on('submit', function(event) {

            event.preventDefault();


            let customerName =
                $('#customer_name').val().trim();


            let customerEmail =
                $('#customer_email').val().trim();


            let orderProducts = [];


            $('#productRows tr').each(function() {

                let row = $(this);

                let productId =
                    row.find('.product-select').val();

                let quantity =
                    row.find('.quantity').val();


                if (productId && quantity) {

                    orderProducts.push({
                        product_id: parseInt(productId),
                        quantity: parseInt(quantity)
                    });
                }
            });

            if (orderProducts.length === 0) {

                showMessage(
                    'Please add at least one product.',
                    'warning'
                );

                return;
            }
            let payload = {

                customer_name: customerName,

                customer_email: customerEmail,

                products: orderProducts
            };


            let submitBtn = $('#submitBtn');


            submitBtn
                .prop('disabled', true)
                .html(
                    '<i class="fas fa-spinner fa-spin"></i> Creating...'
                );


            $.ajax({

                url: '/api/orders',

                type: 'POST',

                contentType: 'application/json',

                dataType: 'json',

                headers: {
                    'Accept': 'application/json'
                },

                data: JSON.stringify(payload),

                success: function(data) {

                    showMessage(
                        'Order created successfully!',
                        'success'
                    );
                    setTimeout(function() {
                        window.location.href = "{{ route('orders.list') }}";
                    }, 1000);
                },


                error: function(xhr) {

                    let data = xhr.responseJSON;

                    let message =
                        'Unable to create order.';


                    if (data && data.message) {

                        message = data.message;
                    }


                    if (data && data.errors) {

                        message = Object.values(data.errors)
                            .flat()
                            .join('<br>');
                    }


                    showMessage(
                        message,
                        'danger'
                    );
                },


                complete: function() {

                    submitBtn
                        .prop('disabled', false)
                        .html(
                            '<i class="fas fa-check"></i> Create Order'
                        );
                }

            });

        });


        // Show Message

        function showMessage(message, type) {

            $('#message').html(`

            <div class="alert alert-${type} alert-dismissible fade show">

                ${message}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

            </div>

        `);
        }

        // Add Product Button
        $('#addProductBtn').on('click', function() {
            addProductRow();

        });
    </script>

@stop
