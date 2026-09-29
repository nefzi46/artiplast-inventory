# Inventryx - Inventory Management System

A powerful and comprehensive inventory management system built with Laravel 11, designed to streamline product tracking, warehouse operations, and supply chain management. Inventryx provides businesses with real-time inventory visibility, role-based access control, and seamless third-party API integrations.

## 🚀 Key Features

### Inventory & Product Management
- **Product Catalog** - Complete product management with categories, brands, and multiple image support
- **Product Categories** - Organize products into hierarchical categories
- **Brand Management** - Track and manage product brands
- **Product Images** - Multi-image upload support using Intervention Image library
- **Stock Tracking** - Real-time inventory levels across multiple warehouses
- **Product Variants** - Handle different sizes, colors, and specifications

### Warehouse Operations
- **Multi-Warehouse Support** - Manage inventory across multiple locations
- **Warehouse Dashboard** - Individual warehouse analytics and stock levels
- **Stock Transfers** - Move inventory between warehouses with full audit trail
- **Low Stock Alerts** - Automated notifications for inventory replenishment

### Purchase & Sales Management
- **Purchase Orders** - Create and manage supplier purchase orders
- **Purchase Items** - Track individual items in each purchase
- **Sales Management** - Process sales orders with automatic inventory deduction
- **Sales Items** - Detailed line-item tracking for all sales
- **Return Management** - Handle purchase returns and sale returns with proper inventory adjustments

### Supplier & Customer Management
- **Supplier Database** - Maintain detailed supplier information and contacts
- **Customer Database** - Track customer details and purchase history
- **Supplier/Customer Analytics** - View transaction history and performance metrics

### User Management & Security
- **Role-Based Access Control (RBAC)** - Powered by Spatie Laravel Permission
- **Custom Roles & Permissions** - Define granular permissions for different user types
- **User Authentication** - Secure login, registration, and password recovery
- **Multi-User Support** - Multiple staff members with different access levels
- **Activity Logging** - Track user actions and system changes

### Dashboard & Reporting
- **Admin Dashboard** - Real-time overview of inventory, sales, and purchases
- **Interactive Charts** - Visual representations using ApexCharts
- **Key Performance Indicators** - Track important business metrics
- **Stock Reports** - Current inventory levels and valuations
- **Sales Reports** - Revenue tracking and sales analytics

### External Integrations
- **Currency Exchange API** - Real-time currency conversion rates displayed on dashboard
- **API Documentation** - See [API_INTEGRATION.md](API_INTEGRATION.md) for integration details
- **Extensible Architecture** - Easy to add new third-party integrations

### UI/UX Features
- **Responsive Design** - Fully responsive Bootstrap 5 admin panel
- **Modern Interface** - Clean and intuitive user interface
- **DataTables Integration** - Advanced sortable, searchable, and filterable data tables
- **Form Validation** - Comprehensive client and server-side validation
- **Toast Notifications** - User-friendly success/error notifications using Toastr
- **Modal Dialogs** - Quick actions without page reloads
- **Custom Color Themes** - Fresh, professional color palette

### Developer Features
- **Clean Code Architecture** - Following Laravel best practices
- **MVC Pattern** - Organized model-view-controller structure
- **Database Migrations** - Version-controlled database schema
- **Seeders** - Sample data for testing and development
- **Form Requests** - Organized validation logic
- **Eloquent Relationships** - Efficient database queries

## 🛠 Tech Stack

### Backend
- **Laravel 11.x** - PHP framework
- **MySQL** - Primary database
- **PHP 8.2+** - Programming language

### Frontend
- **Bootstrap 5** - CSS framework
- **jQuery** - JavaScript library
- **Feather Icons** - Icon set
- **ApexCharts** - Data visualization
- **DataTables** - Enhanced tables

### Key Laravel Packages
- **Spatie Laravel Permission** - Role and permission management
- **Intervention Image** - Image processing and manipulation
- **Laravel Breeze** - Authentication scaffolding
- **Toastr** - Toast notifications

## 📦 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL >= 5.7
- Node.js & NPM

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/soruprohan/Inventryx.git
   cd Inventryx
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` file and configure your database and other settings

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Compile assets**
   ```bash
   npm run build
   ```

10. **Start development server**
    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` in your browser.

## 🔒 Default Login Credentials

After seeding, you can login with default admin credentials (if seeders are configured).

## 📝 Usage

1. **Setup Warehouses** - Add your warehouse locations
2. **Add Suppliers & Customers** - Build your contact database
3. **Create Product Categories & Brands** - Organize your catalog
4. **Add Products** - Start building your product inventory
5. **Manage Purchases** - Record incoming inventory
6. **Process Sales** - Track outgoing inventory
7. **Monitor Dashboard** - Keep track of your business metrics

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Developer

Developed by **Soruprohan**

## 📞 Support

For support, please open an issue in the GitHub repository.
