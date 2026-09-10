# Store Order & Inventory Mini-System

A Laravel-based Store Order & Inventory Management System developed as part of a technical assignment.

The system manages products, customers, orders, inventory stock, customer order history, low-stock products, and order confirmation jobs.

## Tech Stack

* PHP 8.2
* Laravel 12.69.2
* MySQL
* AdminLTE
* Blade
* jQuery / AJAX
* Laravel Queue
* PHPUnit
* Eloquent ORM

## Features

### Products

* View and search products
* Pagination
* Display price, tax, and stock
* Low-stock product detection
* Configurable low-stock threshold

### Customers

* View and search customers
* Pagination
* View customer order history

### Orders

* Create orders for customers
* Add multiple products
* Validate available stock
* Calculate subtotal, tax, and grand total
* Deduct stock after successful order creation
* Store order and order-item details

### Queue

* Dispatch order confirmation job after successful order creation
* Job simulates sending a confirmation through the queue

### Testing

Feature tests cover:

* Successful order creation
* Insufficient stock
* Order confirmation job dispatch

## API Endpoints

### Create Order

```http
POST /api/orders
```

Example request:

```json
{
    "customer_name": "John Doe",
    "customer_email": "john@example.com",
    "products": [
        {
            "product_id": 1,
            "quantity": 2
        }
    ]
}
```

### Customer Order History

```http
GET /api/customers/{email}/orders
```

### Low Stock Products

```http
GET /api/products/low-stock
```

Custom threshold:

```http
GET /api/products/low-stock?threshold=10
```

### Product Details

```http
GET /api/products/{productId}
```

## Installation

### 1. Install Dependencies

```bash
composer install
```

### 2. Configure Environment

Copy `.env.example` to `.env` and configure the MySQL database.

```bash
php artisan key:generate
```

### 3. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

### 4. Start the Application

```bash
php artisan serve
```

## Queue Worker

Run the queue worker:

```bash
php artisan queue:work
```

The order confirmation job is dispatched using `afterCommit()` so it runs only after the order transaction is successfully committed.

## Running Tests

```bash
php artisan test
```

Current order tests cover:

* Successful order creation
* Insufficient stock handling
* Order confirmation job dispatch

## Design Decisions

### Separate Web and API Routes

* `routes/web.php` is used for frontend page rendering.
* `routes/api.php` is used for API operations.
* AJAX is used by the frontend to communicate with the APIs.

### Database Transactions

Order creation is wrapped inside a database transaction so that order creation, order items, and stock deduction succeed or fail together.

### Concurrent Stock Protection

`lockForUpdate()` is used while checking product stock to prevent concurrent orders from overselling inventory.

### Server-Side Calculation

Order subtotal, tax, and grand total are calculated on the server rather than trusting values sent from the frontend.

### Configurable Low Stock

The low-stock API supports a configurable threshold through the `threshold` query parameter.

## Assumptions

* Product code is unique.
* Customer email is unique.
* An order must contain at least one product.
* Stock cannot become negative.
* Tax is calculated using the product's tax percentage.
* Low-stock products are products with stock less than or equal to the selected threshold.

## AI Assistance

AI assistance was used during development for understanding Laravel concepts, debugging, code review, test creation, and documentation.

All suggestions were reviewed and tested before being integrated.
