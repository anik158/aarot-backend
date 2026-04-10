# Aarot API (Laravel Backend)

A robust, high-performance E-commerce backend built with Laravel, featuring a hybrid cart management system and secure OAuth2 authentication.

## 🚀 Key Features

- **Authentication**: Secure API authentication using **Laravel Passport (OAuth2)**.
- **Hybrid Cart System**: 
  - **Guest Cart**: high-speed guest session handling using **Redis**.
  - **Persistent Cart**: Database-backed cart persistence for logged-in users.
  - **Auto-Merge**: Intelligent cart synchronization that merges guest items into the user's account upon login.
- **Product Management**: Multi-attribute product support (Colors, Sizes, Categories).
- **Coupon System**: Dynamic discount calculation with fixed and percentage-based coupons.
- **Order Management**: Full checkout flow with payment method support (COD & Stripe integration).
- **Performance**: Optimized queries and Redis-driven session management.

## 🛠️ Tech Stack

- **Framework**: Laravel 11.x
- **Database**: MySQL 8.x
- **Cache/Store**: Redis
- **Auth**: Laravel Passport
- **Language**: PHP 8.2+

## 🏁 Getting Started

1. **Clone the repository**
2. **Install dependencies**: `composer install`
3. **Setup environment**: Rename `.env.example` to `.env` and configure DB/Redis.
4. **Generate Keys**: `php artisan key:generate`
5. **Migrate & Seed**: `php artisan migrate --seed`
6. **Passport Install**: `php artisan passport:install`
7. **Run Server**: `php artisan serve`

## 📡 API Endpoints (Highlights)

- `POST /api/login` - User authentication
- `GET /api/cart` - Retrieve current cart (Guest/User)
- `POST /api/cart/add` - Add item to cart
- `POST /api/cart/merge` - Sync guest cart to user account
- `POST /api/checkout` - Process orders
