# Tunza Challenge ERP System
## Password and User name are
            'name' => 'Test User',
            'email' => 'test@example.com'
##where patched through seeder
A modern Enterprise Resource Planning (ERP) system built with Laravel, featuring sales management, inventory tracking, and a beautiful Amber-themed UI.

## Features

### Authentication & Authorization
- Custom-styled login/register pages
- Policy-based authorization
- Secure session management
- Remember me functionality
- Automatic redirect to dashboard after login

### Sales Management
- Create and track sales
- Multiple product selection per sale
- Real-time stock validation
- Automatic inventory updates
- Export sales to CSV
- Print sales receipts
- Sales history with detailed views

### Product Management
- Product CRUD operations
- Stock level tracking
- Price management
- Stock alerts (Low/Medium/In Stock)
- Inventory movement tracking

### Dashboard
- Sales overview chart
- 7-day sales analytics
- Recent sales list
- Quick action buttons
- Real-time statistics

## Tech Stack

- **Frontend**
  - Tailwind CSS
  - Alpine.js
  - Chart.js for analytics
  - SweetAlert2 for notifications

- **Backend**
  - Laravel 10
  - MySQL/PostgreSQL
  - Policy-based authorization
  - RESTful API architecture

## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/tunza-erp.git
cd tunza-erp
```

## Project Structure

```
tunza-erp/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthenticatedSessionController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   └── SalesController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Product.php
│   │   ├── Sale.php
│   │   ├── SaleItem.php
│   │   └── InventoryMovement.php
│   └── Policies/
│       ├── ProductPolicy.php
│       └── SalePolicy.php
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── products/
│   │   ├── sales/
│   │   └── layouts/
│   └── js/
│       ├── app.js
│       └── sales-chart.js
└── routes/
    └── web.php
```

## Additional Information

This README provides:
- Complete project overview
- Installation instructions
- Feature documentation
- Tech stack details
- Project structure
- Security measures
- Contribution guidelines
