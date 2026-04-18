# GearTech Ecommerce

Laravel 13 ecommerce application built with PHP, Blade, Tailwind CSS, and MySQL. The project includes a public storefront, an admin dashboard, Midtrans payment integration, role-based access control, and transactional stock handling that only deducts inventory after a successful payment callback.

## Features

- Public storefront: home, catalog, product detail, cart, checkout, profile, order history, and order detail
- Admin dashboard: category CRUD, product CRUD, order management, status updates, and sales report summary
- Roles: `admin` and `user`
- Payment provider: Midtrans only
- Stock rule: cart does not reserve stock, checkout creates `pending_payment`, Midtrans callback is the source of truth, and stock is reduced exactly once when an order becomes `paid`
- Architecture: service classes for cart, checkout, payment, stock, order, and reporting logic, plus Form Requests, Policies, and middleware

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan storage:link
php artisan serve
```

Configure MySQL and Midtrans in `.env` before checkout:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=geartech
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_MERCHANT_ID=
MIDTRANS_CLIENT_KEY=
MIDTRANS_SERVER_KEY=
MIDTRANS_IS_PRODUCTION=false
```

## Seeded Accounts

- Admin: `admin@geartech.test` / `password`
- Admin 2: `admin@gmail.com` / `admin`
- User: `user@geartech.test` / `password`
- User 2: `user@gmail.com` / `user1234`

## Verification

```bash
php artisan test
npm run build
```
