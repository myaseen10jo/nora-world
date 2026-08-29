# NORA WORLD — Authentic Handmade Heritage from Jordan & Palestine

NORA WORLD is an English-language e-commerce platform selling authentic handmade and heritage-inspired home products from Jordan and Palestine. Built with **Laravel 13**, **Filament 3**, and **PayPal Checkout**, it targets customers in the **United States** and **Europe**.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13 (PHP 8.2+) |
| Admin Panel | Filament 3 |
| Auth | Laravel Breeze (Blade) |
| Database | MySQL / SQLite (testing) |
| Payments | PayPal REST API + JS SDK |
| CSS | Tailwind CSS |
| Frontend | Blade Templates + Alpine.js |

---

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL 5.7+ or SQLite
- Node.js & NPM (for Vite asset compilation)
- PayPal Sandbox account (for testing payments)

---

## Installation

### 1. Clone and install dependencies

```bash
cd nora-world
composer install
npm install && npm run build
```

### 2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure database

Edit `.env` to set your database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nora-world
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

### 5. Configure PayPal (see section below)

### 6. Start the server

```bash
php artisan serve
```

The storefront is available at `http://localhost:8000`.

The admin panel is available at `http://localhost:8000/admin`.

---

## PayPal Sandbox Testing

### Creating Sandbox Accounts

1. Go to [PayPal Developer Dashboard](https://developer.paypal.com/developer/applications/)
2. Log in with your PayPal account
3. Navigate to **Sandbox → Accounts**
4. Click **Create Account**
5. Create two accounts:
   - **Business** account (merchant — receives payments)
   - **Personal** account (buyer — makes test payments)
6. Copy the **Client ID** and **Secret** from the Business account's API credentials

### Configuring Sandbox Credentials

Add these to your `.env` file:

```env
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_sandbox_client_id_here
PAYPAL_CLIENT_SECRET=your_sandbox_client_secret_here
PAYPAL_CURRENCY=USD
```

### Testing a Successful Payment

1. Start the app and add products to cart
2. Proceed to checkout
3. Enter a valid shipping address (US or EU)
4. Select a shipping method
5. Click **Pay with PayPal**
6. Log in with your sandbox **Personal** (buyer) account
7. Confirm the payment
8. You should be redirected to the order confirmation page

### Testing a Cancelled Payment

1. During the PayPal login screen, click **Cancel** or close the popup
2. You should be redirected back to checkout with a cancellation message
3. The order status remains `pending` in the database

### Testing a Failed Payment

1. Use an invalid payment method in the sandbox buyer account
2. Or simulate a failure by using a declined test card in the sandbox
3. The checkout page will display a failure message
4. The order is marked as `failed`

### ⚠️ Production Warning

> **Never use Sandbox credentials in production.** After the site is fully tested and deployed securely, replace Sandbox credentials with production credentials obtained from the PayPal live environment. Store production secrets securely using environment variables — never in source code.

---

## Environment Variables

| Variable | Description | Default |
|---|---|---|
| `PAYPAL_MODE` | `sandbox` or `live` | `sandbox` |
| `PAYPAL_CLIENT_ID` | PayPal API Client ID | — |
| `PAYPAL_CLIENT_SECRET` | PayPal API Client Secret | — |
| `PAYPAL_CURRENCY` | Currency for transactions | `USD` |

---

## Admin Panel

Access the admin panel at `/admin`. The seeded admin account:

- **Email:** admin@nora-world.com
- **Password:** password

### Admin capabilities:

- Manage products with all handmade-specific fields
- Manage categories (parent/child)
- Manage collections
- Manage homepage sections (activate, deactivate, reorder, edit content)
- Manage orders and view payment status
- Configure shipping zones and methods
- Manage testimonials
- Manage pages (About Us, Shipping Policy, etc.)
- View newsletter subscribers
- Store settings

---

## Running Tests

```bash
php artisan test
```

All 54 tests cover:
- USD product pricing
- Shipping zone determination and cost calculation
- Checkout total calculation
- PayPal order creation validation
- Successful PayPal payment capture
- Failed/cancelled PayPal payment handling
- Duplicate payment prevention (idempotency)
- Stock management (no negative stock)
- Customer order viewing
- Authentication (Breeze)

---

## Currency

All prices are displayed in **USD ($)** with 2 decimal places. No other currencies are supported.

---

## IIS Deployment (Local)

This project is designed to run on IIS for local development only. No external deployment, GitHub repositories, or live PayPal credentials should be used until explicitly requested.

---

## License

Proprietary — All rights reserved.
