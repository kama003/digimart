# Technology Stack

## Backend

- **Framework**: Laravel 12 (PHP 8.2+)
- **UI Framework**: Livewire 3 with Volt for reactive components
- **Authentication**: Laravel Fortify with Sanctum for API tokens
- **Database**: MySQL 8.0+ (SQLite for development)
- **Queue**: Database driver (development), Redis (production recommended)
- **Cache**: Database driver (development), Redis (production recommended)
- **File Storage**: Local (development), S3/DigitalOcean Spaces (production)

## Frontend

- **CSS Framework**: Tailwind CSS 4
- **Build Tool**: Vite 7
- **Component Library**: Livewire Flux 2
- **JavaScript**: Axios for HTTP requests

## Key Dependencies

- **Payment**: Stripe PHP SDK, Paytm Checksum
- **PDF Generation**: DomPDF (for invoices)
- **API Documentation**: Scribe 5
- **Testing**: Pest 4

## Common Commands

### Development

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate
php artisan migrate

# Start development servers
composer dev  # Runs server, queue, and vite concurrently
# OR manually:
php artisan serve
php artisan queue:work
npm run dev
```

### Testing

```bash
php artisan test
composer test  # Clears config and runs tests
```

### Building

```bash
npm run build  # Production build
```

### Deployment

```bash
# Quick deployment (Linux/Mac)
./deploy.sh

# Quick deployment (Windows)
deploy.bat

# Manual deployment steps
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize
php artisan migrate --force
npm run build
```

### Queue Management

```bash
# Development
php artisan queue:work
php artisan queue:work --verbose

# Production (with Supervisor recommended)
php artisan queue:work --daemon --tries=3 --timeout=90

# Queue monitoring
php artisan queue:failed
php artisan queue:retry all
php artisan queue:flush
```

### API Documentation

```bash
# Regenerate API docs after changes
php artisan scribe:generate
```

### Database

```bash
# Run migrations
php artisan migrate

# Seed database (includes admin user)
php artisan db:seed

# Create admin user only
php artisan db:seed --class=AdminUserSeeder
```

## Architecture Patterns

- **Service Layer**: Singleton services for cart, storage, and notifications
- **Policy-Based Authorization**: Laravel policies for User, Product, Transaction, Withdrawal
- **Event-Driven**: PurchaseCompleted event triggers purchase processing
- **Queue-Based Processing**: Email notifications, webhooks, invoice generation, file processing
- **Repository Pattern**: Not used; Eloquent models accessed directly
- **Component-Based UI**: Livewire components with Blade templates
