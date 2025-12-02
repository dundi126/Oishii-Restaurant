# 🍜 Oishii - Japanese Restaurant Management System

A comprehensive web application for managing a Japanese restaurant, featuring online ordering, menu management, staff administration, and payment processing.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Payment Setup (Stripe)](#payment-setup-stripe)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

### Customer Features
- 🏠 **Beautiful Home Page** - Japanese-themed landing page with restaurant information
- 📱 **Responsive Menu** - Browse menu items with categories, filters, and search
- 🛒 **Shopping Cart** - Add items with customizations, manage quantities
- 💳 **Secure Checkout** - Stripe payment integration and cash on delivery
- 📦 **Order Tracking** - Real-time order status updates
- 👤 **User Profiles** - Account management and order history

### Admin Features
- 👥 **Staff Management** - Create, update, and delete staff user accounts
- 🍽️ **Menu Management** - Full CRUD operations for menu items and categories
- ⚙️ **Customizations** - Add customization options to menu items
- 📊 **Order Management** - View and update order statuses
- 🔐 **Role-Based Access** - Admin, staff, and customer roles

### Design Features
- 🎨 **Japanese Aesthetic** - Traditional Japanese design elements
- 🌸 **Modern UI/UX** - Clean, responsive design with smooth animations
- 📱 **Mobile-Friendly** - Fully responsive across all devices
- 🎭 **Split Layout Auth** - Beautiful login/register pages with menu carousel

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates, Tailwind CSS, JavaScript
- **Authentication**: Laravel Fortify
- **Payment**: Stripe
- **Database**: MySQL/SQLite
- **UI Components**: Livewire Flux
- **Icons**: Font Awesome

## 📦 Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL 5.7+ or SQLite
- Web Server (Apache/Nginx) or PHP Built-in Server

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/oishii-restaurant.git
cd oishii-restaurant
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Environment

Edit `.env` file with your configuration:

```env
APP_NAME="Oishii Restaurant"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oishii_restaurant
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Stripe Configuration (for payments)
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
```

### 5. Database Migration

```bash
# Run migrations
php artisan migrate

# (Optional) Seed with sample data
php artisan db:seed
```

### 6. Build Assets

```bash
# Build for production
npm run build

# Or for development with hot reload
npm run dev
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## ⚙️ Configuration

### Database Configuration

The application supports both MySQL and SQLite:

**MySQL (Recommended for Production):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oishii_restaurant
DB_USERNAME=root
DB_PASSWORD=
```

**SQLite (For Development):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### Session Configuration

By default, sessions are stored in files. To use database sessions:

```env
SESSION_DRIVER=database
```

Then run:
```bash
php artisan session:table
php artisan migrate
```

## 💳 Payment Setup (Stripe)

### 1. Get Stripe API Keys

1. Sign up at [Stripe](https://stripe.com)
2. Go to Dashboard → Developers → API keys
3. Copy your **Publishable key** (starts with `pk_test_`)
4. Copy your **Secret key** (starts with `sk_test_`)

### 2. Add to .env

```env
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
```

### 3. Test Cards

Use these test card numbers for testing:

- **Success**: `4242 4242 4242 4242`
- **Declined**: `4000 0000 0000 0002`
- **3D Secure**: `4000 0027 6000 3184`

Any future expiry date, CVC, and ZIP code will work.

### 4. Currency

The application is configured for **USD (US Dollar)**. To change, edit:
`app/Http/Controllers/OrderController.php` line 167.

## 📖 Usage

### Creating Admin User

After running migrations, create an admin user:

```bash
php artisan tinker
```

```php
$user = \App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@oishii.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
]);
```

### Default Routes

- `/` - Home page
- `/menu` - Menu page
- `/login` - Login page
- `/register` - Registration page
- `/cart` - Shopping cart
- `/checkout` - Checkout page
- `/orderstrack` - Order tracking
- `/admin` - Admin dashboard (admin only)
- `/admin/users` - Staff management (admin only)

### User Roles

- **Admin**: Full access to all features
- **Staff**: Can manage orders and menu items
- **Customer**: Can place orders and track them

## 📁 Project Structure

```
oishii-restaurant/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       │   └── AdminController.php
│   │       ├── CartController.php
│   │       ├── CheckoutController.php
│   │       ├── HomeController.php
│   │       ├── MenuController.php
│   │       └── OrderController.php
│   ├── Models/
│   │   ├── Cart.php
│   │   ├── Category.php
│   │   ├── MenuItem.php
│   │   ├── Order.php
│   │   └── User.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── customer/
│   │   ├── components/
│   │   ├── livewire/
│   │   └── partials/
│   └── js/
├── routes/
│   └── web.php
├── public/
└── .env
```

## 🎨 Key Features Explained

### Menu Management
- Add/edit/delete menu items
- Categorize items
- Add images
- Set vegetarian/non-vegetarian tags
- Add customizations (sizes, toppings, etc.)

### Order Processing
- Real-time cart updates
- Multiple payment methods
- Order status tracking
- Email notifications (can be configured)

### Staff Management
- Admin can create staff accounts
- Role-based permissions
- Secure authentication

## 🔒 Security Features

- CSRF protection
- XSS prevention
- SQL injection protection (Eloquent ORM)
- Password hashing (bcrypt)
- Role-based authorization (Gates)
- Secure session management

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📝 Environment Variables

Key environment variables:

```env
APP_NAME="Oishii Restaurant"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oishii_restaurant
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

SESSION_DRIVER=file
```

## 🐛 Troubleshooting

### Database Connection Issues
- Ensure database credentials in `.env` are correct
- Check if database exists
- Verify MySQL service is running

### Payment Not Working
- Verify Stripe keys are correct
- Check if using test keys in test mode
- Ensure currency matches your region

### Session Issues
- Clear config cache: `php artisan config:clear`
- Check `storage/framework/sessions` permissions
- Verify `SESSION_DRIVER` in `.env`

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👤 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- Laravel Framework
- Stripe for payment processing
- Tailwind CSS for styling
- Font Awesome for icons
- All contributors and users

## 📞 Support

For support, email support@oishii.com or open an issue in the repository.

---

**Made with ❤️ for authentic Japanese cuisine**

