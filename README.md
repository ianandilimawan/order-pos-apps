# ⚡ InPOS — Modern Point of Sale & Self-Ordering System

A lightning-fast, modern Point of Sale (POS) and Self-Service QR ordering system designed for **F&B businesses, restaurants, cafes, retail stores, food courts, and pop-up shops**. Built with the powerful **TALL stack** (Tailwind CSS, Alpine.js, Laravel 11, Livewire 3), InPOS delivers an uncompromisingly fast, responsive, and beautiful experience for both customers and staff.

## ✨ Key Features

### 🌐 Global & Modern UI
- **Bilingual Support (ID / EN)**: Fully localized interface supporting English and Indonesian, easily extensible via simple JSON language files.
- **Dark & Light Mode**: Sleek, instant theme toggle with persistent preferences and glassmorphic design aesthetics.
- **SaaS-Style Landing Page**: Built-in responsive marketing landing page with smooth Framer Motion animations.

### 📱 For Customers (QR Menu)
- **Self-Service Ordering**: Scan QR codes at tables or counters to instantly browse digital menus without waiting in line.
- **Interactive Mobile Cart**: Responsive bottom-sheet cart with smooth animations, customized modifiers, and special notes.
- **Smart Promo & Discount System**: Automatic discount calculation, minimum spend validation, and maximum discount caps.
- **Mobile-First Experience**: Heavy mobile optimization with safe-area awareness for a native app-like feel.

### 💻 For Staff & Admins (POS Dashboard)
- **Lightning-Fast POS Interface**: Built with Livewire 3 and Alpine.js for a zero-reload, high-speed cashier checkout workflow.
- **Lost Revenue & Sales Analytics**: Comprehensive sales dashboard tracking actual revenue, cash opname shifts, and potential revenue lost from abandoned orders.
- **Table & QR Management**: Easily generate, download, and manage QR codes for tables and ordering areas.
- **Order Management & Kitchen Display**: Real-time order status updates (Pending, Processing, Completed, Cancelled).
- **Automated Cron & Backups**: Automated midnight cleanup for abandoned orders and scheduled daily `.sql` backups with a dedicated Backup Manager UI.
- **Role-Based Access Control (RBAC)**: Fine-grained permissions for Admins, Cashiers, and Kitchen staff with OTP login support.

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
