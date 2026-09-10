@extends('adminlte::page')

@section('title', 'Customer Orders')

@section('content_header')

    <div class="d-flex justify-content-between align-items-center">

        <h1>Customer Orders</h1>

        <a href="{{ route('customer.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </div>

@stop

@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user"></i> Customer Details
            </h3>
        </div>

        <div class="card-body">

            <p>
                <strong>Name:</strong>
                <span id="customerName">Loading...</span>
            </p>

            <p>
                <strong>Email:</strong>
                <span id="customerEmail">Loading...</span>
            </p>

        </div>

    </div>

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shopping-cart"></i> Order History
            </h3>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Subtotal</th>
                            <th>Tax</th>
                            <th>Grand Total</th>
                        </tr>

                    </thead>

                    <tbody id="ordersTable">

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

            let email = @json($email);

            $.ajax({

                url: '/api/customers/' + encodeURIComponent(email) + '/orders',

                type: 'GET',

                dataType: 'json',

                success: function(data) {

                    // Customer details

                    $('#customerName').text(data.customer.name);

                    $('#customerEmail').text(data.customer.email);


                    // Orders

                    let orders = data.orders;

                    if (orders.length === 0) {

                        $('#ordersTable').html(`
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            No orders found.
                        </td>
                    </tr>
                `);

                        return;
                    }


                    let rows = '';

                    orders.forEach(function(order, index) {

                        rows += `
                    <tr>

                        <td>
                            ${index + 1}
                        </td>

                        <td>
                            #${order.id}
                        </td>

                        <td>
                            ${new Date(order.created_at).toLocaleDateString()}
                        </td>

                        <td>
                            ₹${parseFloat(order.subtotal).toFixed(2)}
                        </td>

                        <td>
                            ₹${parseFloat(order.tax).toFixed(2)}
                        </td>

                        <td>
                            <strong>
                                ₹${parseFloat(order.grand_total).toFixed(2)}
                            </strong>
                        </td>

                    </tr>
                `;

                    });

                    $('#ordersTable').html(rows);

                },


                error: function(xhr) {

                    let message = 'Unable to load customer orders.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    $('#ordersTable').html(`
                <tr>
                    <td colspan="6" class="text-center text-danger py-4">
                        ${message}
                    </td>
                </tr>
            `);

                }

            });

        });
    </script>

@stop
