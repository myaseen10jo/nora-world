# NORA WORLD — Architecture

## Overview

NORA WORLD is a Laravel 13 application following the MVC pattern with a Filament admin panel. It uses Blade templates with Tailwind CSS for the storefront, and PayPal REST API for payment processing.

```
┌─────────────────────────────────────────────────────────┐
│                     CLIENT (Browser)                     │
│  ┌──────────────┐  ┌──────────────┐  ┌───────────────┐  │
│  │  Blade Views  │  │ PayPal JS SDK │  │  Alpine.js    │  │
│  └──────┬───────┘  └──────┬───────┘  └───────────────┘  │
├─────────┼──────────────────┼──────────────────────────────┤
│         │     Laravel Router│                             │
│  ┌──────▼──────────────────▼──────────────────────────┐  │
│  │              Controllers                            │  │
│  │  HomeController | ProductController | CartController │  │
│  │  CheckoutController | OrderController | ...         │  │
│  └──────────────────────┬─────────────────────────────┘  │
│                         │                                 │
│  ┌──────────────────────▼─────────────────────────────┐  │
│  │              Services                               │  │
│  │  PayPalService          │  ShippingService           │  │
│  └──────────┬─────────────┴────────────┬──────────────┘  │
│             │                          │                   │
│  ┌──────────▼──────────┐  ┌────────────▼──────────────┐  │
│  │   Eloquent Models   │  │   PayPal REST API         │  │
│  │   (20+ tables)      │  │   (Server-side)           │  │
│  └──────────┬──────────┘  └───────────────────────────┘  │
│             │                                             │
│  ┌──────────▼──────────┐  ┌───────────────────────────┐  │
│  │   MySQL Database    │  │   Filament Admin Panel    │  │
│  └─────────────────────┘  └───────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## Database Schema

### Core Tables

| Table | Purpose |
|---|---|
| `users` | Customer and admin accounts |
| `products` | Product catalog with handmade-specific fields |
| `product_images` | Product image gallery |
| `categories` | Hierarchical categories (parent/child) |
| `product_category` | Product ↔ Category pivot |
| `collections` | Curated product collections |
| `collection_product` | Collection ↔ Product pivot |

### Order & Payment Tables

| Table | Purpose |
|---|---|
| `orders` | Customer orders with shipping address |
| `order_items` | Line items in an order |
| `payments` | PayPal payment records with capture data |
| `cart_items` | User shopping cart (session-based per user) |

### Shipping Tables

| Table | Purpose |
|---|---|
| `shipping_zones` | Geographic shipping zones (US, EU, UK, etc.) |
| `shipping_methods` | Methods per zone (Standard, Express, Free) |

### Content Tables

| Table | Purpose |
|---|---|
| `homepage_sections` | Configurable homepage sections |
| `homepage_section_product` | Section ↔ Product pivot |
| `testimonials` | Customer testimonials |
| `pages` | Static pages (About, Policies, etc.) |
| `settings` | Key-value store settings |
| `tags` | Product tags |
| `newsletter_subscribers` | Newsletter subscriber list |
| `reviews` | Product reviews |
| `recently_viewed_products` | User browsing history |
| `wishlist_items` | User wishlists |
| `addresses` | Saved shipping addresses |

---

## Key Models & Relationships

```
User
├── hasMany: CartItem, WishlistItem, Order, Address, RecentlyViewedProduct
│
Product
├── belongsToMany: Category, Collection, Tag
├── hasMany: ProductImage, OrderItem, Review
│
Category (self-referencing)
├── belongsToMany: Product
├── hasMany: subcategories (children)
│
Order
├── belongsTo: User
├── hasMany: OrderItem, Payment
├── belongsTo: ShippingZone
│
Payment
├── belongsTo: Order
│
ShippingZone
├── hasMany: ShippingMethod
│
HomepageSection
├── belongsToMany: Product
```

---

## PayPal Order Creation Flow

```
1. Customer fills shipping address + selects shipping method
        │
2. Customer clicks "Pay with PayPal"
        │
3. Browser calls POST /checkout/create-paypal-order
        │
4. CheckoutController.validateRequest()
   ├── Check cart is not empty
   ├── Validate shipping address fields
   ├── Validate shipping_method_id exists
        │
5. DB Transaction:
   ├── Calculate subtotal from cart items (server-side, not browser)
   ├── Calculate shipping cost via ShippingService
   ├── Create Order record (status: pending)
   ├── Create OrderItem records
   ├── Create Payment record (status: pending)
   └── Return order
        │
6. PayPalService.createOrder(order, total)
   ├── Get OAuth2 access token from PayPal
   ├── POST /v2/checkout/orders to PayPal
   ├── Include order total, currency (USD), return/cancel URLs
   └── Return PayPal order ID
        │
7. Return PayPal order ID to browser
        │
8. PayPal JS SDK renders approval flow
        │
9. Customer approves payment in PayPal popup
```

---

## PayPal Approval & Capture Flow

```
1. Customer approves payment in PayPal
        │
2. PayPal redirects to /checkout/paypal/success?token={paypal_order_id}
        │
3. CheckoutController.paypalSuccess()
   ├── Find Payment record by paypal_order_id
   ├── Load associated Order
        │
4. PayPalService.captureOrder(paypal_order_id)
   ├── POST /v2/checkout/orders/{id}/capture
   └── Return capture data
        │
5. PayPalService.processSuccessfulCapture(order, captureData)
   ├── validateCaptureResponse() — verify status is COMPLETED
   ├── Idempotency check — skip if already captured
   ├── Extract capture details (capture ID, amount, payer)
   ├── Validate captured amount matches DB total (±$0.01)
   ├── Create Payment record (status: captured)
   ├── Update Order status → processing
   └── reduceStock() — decrement inventory
        │
6. Clear user's cart
        │
7. Redirect to order confirmation page
```

---

## Stock Reduction Rules

- Stock is **never** reduced when the order is created
- Stock is reduced **only** after PayPal confirms a successful capture
- If payment fails, is cancelled, or is pending, stock remains unchanged
- Stock cannot go below 0 (`max(0, newStock)`)
- When stock reaches 0, `in_stock` is set to `false`

---

## Payment Failure & Cancellation Rules

### Payment Failure

```
1. PayPalService.captureOrder() throws exception
2. PayPalService.handlePaymentFailure(order, reason)
   ├── Create Payment record (status: failed, failure_reason stored)
3. Redirect customer to checkout with error message
4. Order status remains pending
5. Stock unchanged
```

### Payment Cancellation

```
1. Customer cancels in PayPal popup
2. Browser redirects to /checkout/paypal/cancel
3. CheckoutController.paypalCancel()
   ├── Find Payment by paypal_order_id
   ├── PayPalService.handlePaymentCancellation(order)
   │   └── Create Payment record (status: cancelled)
4. Redirect to checkout with info message
5. Order status remains pending
6. Stock unchanged
```

---

## Shipping Zone Determination

```
ShippingService.getZoneForCountry(countryCode)
│
├── Load all active ShippingZones
├── For each zone:
│   ├── Decode countries JSON array
│   ├── Check if countryCode is in the list
│   └── Return matching zone (or null)
│
ShippingService.getAvailableMethods(countryCode)
│
├── Find zone for country
├── If no zone → return empty (country not served)
├── Return active ShippingMethods for that zone
│
ShippingService.calculateCost(method, subtotal)
│
├── Check if subtotal >= free_shipping_threshold
│   ├── Yes → return 0 (free shipping)
│   └── No → return method.flat_rate
```

### Shipping Zones

| Zone | Countries |
|---|---|
| United States | US |
| European Union | AT, BE, BG, HR, CY, CZ, DK, EE, FI, FR, DE, GR, HU, IE, IT, LV, LT, LU, MT, NL, PL, PT, RO, SK, SI, ES, SE |
| United Kingdom | GB |
| Other European | CH, NO, IS, AL, BA, ME, MK, RS, UA, MD, BY, TR |
| Rest of World | All other countries |

---

## Future Webhook Architecture (Production Ready)

The current implementation uses the PayPal JS SDK redirect flow. For production, the architecture is prepared for PayPal webhooks:

```
PayPal → POST /webhooks/paypal → WebhookController
    │
    ├── Verify webhook signature (PayPal API)
    ├── Parse event type:
    │   ├── PAYMENT.CAPTURE.COMPLETED → confirm order
    │   ├── PAYMENT.CAPTURE.DENIED → mark failed
    │   └── PAYMENT.CAPTURE.REFUNDED → process refund
    ├── Idempotency check (webhook_id in metadata)
    └── Update Order + Payment records
```

---

## Admin Panel (Filament)

### Resources

| Resource | Management |
|---|---|
| `ProductResource` | Full product CRUD with handmade fields, images, categories |
| `CategoryResource` | Hierarchical category management |
| `OrderResource` | Order listing, status updates, payment info |
| `HomepageSectionResource` | Homepage section management (activate, reorder, content) |
| `CollectionResource` | Curated collection management |
| `ShippingZoneResource` | Shipping zone and method configuration |
| `TestimonialResource` | Customer testimonial management |
| `PageResource` | Static page content (About, Policies) |
| `SettingResource` | Store settings (key-value) |
| `NewsletterSubscriberResource` | Newsletter subscriber management |

---

## File Structure

```
nora-world/
├── app/
│   ├── Filament/Resources/          # Admin panel resources
│   ├── Http/Controllers/            # Storefront + API controllers
│   │   └── Auth/                    # Breeze auth controllers
│   ├── Models/                      # 20+ Eloquent models
│   ├── Providers/                   # Service providers (incl. Filament)
│   └── Services/
│       ├── PayPalService.php        # PayPal API integration
│       └── ShippingService.php      # Shipping zone/cost logic
├── database/
│   ├── factories/                   # Model factories for testing
│   ├── migrations/                  # 30+ migration files
│   └── seeders/                     # Initial data seeders
├── resources/views/
│   ├── layouts/                     # App layout + navigation
│   ├── home/                        # Homepage
│   ├── products/                    # Product pages
│   ├── cart/                        # Cart page
│   ├── checkout/                    # Checkout with PayPal
│   ├── orders/                      # Order history
│   ├── collections/                 # Collection pages
│   ├── pages/                       # Static pages
│   ├── wishlist/                    # Wishlist
│   └── components/                  # Reusable components
├── routes/web.php                   # All routes
├── tests/Feature/                   # 54 tests
├── .env.example                     # Environment template
├── README.md                        # Setup & usage guide
└── ARCHITECTURE.md                  # This file
```
