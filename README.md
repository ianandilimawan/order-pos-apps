# ⚡ InPOS — Modern Point of Sale & Self-Ordering System

A lightning-fast, modern Point of Sale (POS) and Self-Service QR ordering system designed for **F&B businesses, restaurants, cafes, retail stores, food courts, and pop-up shops**. Built with the powerful **TALL stack** (Tailwind CSS, Alpine.js, Laravel 11, Livewire 3), InPOS delivers an uncompromisingly fast, responsive, and beautiful experience for both customers and staff.

## ✨ Comprehensive Key Features

### 🌐 Global & Modern Design Architecture
- **Bilingual Localization (ID / EN)**: Fully localized interface supporting seamless real-time switching between English and Indonesian. Built with modular JSON language files (`lang/en.json`, `lang/id.json`) to allow effortless extension to additional languages.
- **Persistent Dark & Light Mode**: Sleek, instant theme switching with persistent user preference storage in localStorage and system color scheme auto-detection. Designed with curated HSL color palettes and glassmorphic aesthetics.
- **SaaS-Style Landing Page**: Built-in responsive marketing landing page featuring Framer Motion scroll animations, modern typography, and responsive navbar/footer layouts.

### 📱 For Customers — Self-Service QR Menu
- **Table-Based QR Ordering**: Customers scan dynamic table QR codes to open an app-like digital menu directly in their mobile browser—no app download required.
- **Interactive Mobile Cart**: Slide-up interactive bottom-sheet cart featuring real-time price calculations, item modifiers (sizes, add-ons, sugar/ice levels), and custom kitchen notes.
- **Smart Promo & Voucher Engine**: Automatically applies promo codes and calculates discounts in real-time, enforcing rules such as minimum order spend, maximum discount caps, and promotion validity periods.
- **Live Order Status Tracking**: Real-time order progress indicators allowing customers to monitor whether their order is Pending, Processing, or Completed.
- **Mobile-First Ergonomics**: Designed with safe-area awareness, optimized touch targets, and smooth micro-animations for an intuitive self-ordering experience.

### 💻 For Cashiers & Staff — High-Speed POS Interface
- **Zero-Reload POS Checkout**: Powered by Livewire 3 and Alpine.js for instantaneous product searching, category filtering, cart manipulation, and receipt generation without full page refreshes.
- **Order Management & Kitchen Workflow**: Real-time dashboard to monitor incoming QR orders, update kitchen preparation statuses, and manage dine-in, takeaway, or self-pickup workflows.
- **Cash Opname & Shift Accountability**: Shift management system to record starting cash drawers, monitor expected revenue, reconcile actual cash on hand, and log cash discrepancies.
- **Table & QR Code Manager**: Dedicated admin utility to generate, manage, and download print-ready QR code standees for dining tables or ordering kiosks.

### 📊 For Management — Analytics, Security & Automation
- **Lost Revenue & Sales Analytics**: Advanced analytical dashboard tracking actual sales performance alongside potential revenue lost from abandoned or unpaid customer orders.
- **Exportable Financial Reports**: Granular sales reporting powered by Livewire PowerGrid, supporting multi-dimensional date filtering and DataTables exports.
- **Role-Based Access Control (RBAC)**: Fine-grained permission management for diverse operational roles (Super Admin, Store Manager, Cashier, Kitchen Staff) with secure OTP login support.
- **Automated Midnight Order Cleanup (Cron)**: Built-in scheduled task that automatically sweeps and cancels stale or abandoned pending orders every midnight to maintain clean financial records.
- **Automated Database Backups & Manager UI**: Integrated automated daily `.sql` database backups coupled with a visual Backup Manager interface for easy archive downloading and management.

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
