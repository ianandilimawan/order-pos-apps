# ☕ Cafe POS & Self-Service QR Menu

A comprehensive, modern Point of Sale (POS) and Self-Service ordering system designed specifically for cafes and restaurants. Built with the powerful TALL stack (Tailwind, Alpine, Laravel, Livewire), it provides a seamless experience for both customers and staff.

## ✨ Key Features

### 📱 For Customers (QR Menu)
- **Self-Service Ordering**: Scan QR code on the table to view the digital menu.
- **Interactive Cart**: Beautiful, responsive bottom-sheet cart with smooth animations.
- **Promo System**: Auto-calculate discounts, minimum spend validation, and max discount caps.
- **Mobile First**: UI optimized heavily for mobile devices (Safe-area aware, app-like feel).

### 💻 For Staff & Admins (POS Dashboard)
- **Modern POS Interface**: Built with Livewire and Alpine.js for a seamless cashier experience.
- **Auto-Cancel Orders (Cron)**: Automatically sweeps and cancels abandoned orders at midnight.
- **Lost Revenue Analytics**: Smart dashboard tracking potential revenue lost from pending orders.
- **Automated Database Backups**: Scheduled daily `.sql` backups with a beautiful Backup Manager UI.
- **Promotional Landing Page**: Built-in SaaS-style landing page for marketing the POS application.
- **Order Management**: Real-time order tracking with status badges (Pending, Processing, Completed).
- **Table Management**: Generate and print QR codes for each dining table easily.
- **Cash Opname**: Shift management to track starting cash, expected revenue, and actual cash on hand.
- **Sales Reports**: Detailed reporting and analytics with exportable DataTables.
- **Role-Based Access (RBAC)**: Secure access control (Admin, Cashier, Kitchen, etc.) with OTP Login support.

## 🛠️ Tech Stack
- **Framework**: Laravel 11
- **Frontend**: Livewire 3 + Alpine.js
- **Styling**: Tailwind CSS
- **DataTables**: Livewire PowerGrid
- **Alerts & Modals**: SweetAlert2

## 🚀 Getting Started

1. Clone the repository
2. Install dependencies: `composer install` & `npm install`
3. Copy `.env.example` to `.env` and set your database credentials
4. Run migrations and seeders: `php artisan migrate --seed`
5. Compile assets: `npm run build`
6. Start the server: `php artisan serve`
