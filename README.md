# Digital Marketplace

A production-ready digital product marketplace built with Laravel 11 and Livewire 3.

## Features

- User authentication with role-based access control (Customer, Seller, Admin)
- Product browsing and search functionality
- Shopping cart system
- Secure payment processing (Stripe and Paytm)
- Digital asset delivery with time-limited download links
- Seller dashboard and analytics
- Admin moderation and management tools
- Responsive design with dark mode support
- Comprehensive notification system

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL 8.0 or higher
- Redis (for production)

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Copy `.env.example` to `.env` and configure your environment variables
4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run migrations:
   ```bash
   php artisan migrate
   ```

6. Seed the database (optional):
   ```bash
   php artisan db:seed
   ```

7. Build frontend assets:
   ```bash
   npm run dev
   ```

## Deployment

### Quick Deployment

Use the provided deployment scripts to optimize caches:

**Linux/Mac:**
```bash
chmod +x deploy.sh
./deploy.sh
```

**Windows:**
```bash
deploy.bat
```

### Manual Deployment Steps

1. **Clear existing caches:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Build optimized caches:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Optimize autoloader:**
   ```bash
   composer dump-autoload --optimize
   ```

4. **Run migrations:**
   ```bash
   php artisan migrate --force
   ```

5. **Build frontend assets:**
   ```bash
   npm run build
   ```

6. **Start queue workers:**
   ```bash
   php artisan queue:work --daemon
   ```

7. **Set up cron job for scheduled tasks:**
   ```
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

## Caching Strategy

The application implements several caching strategies for optimal performance:

### Application Caching

- **Categories**: Cached for 1 hour on homepage and search pages
- **Top-selling products**: Cached for 1 hour on homepage
- **Configuration**: Cached in production using `php artisan config:cache`
- **Routes**: Cached in production using `php artisan route:cache`
- **Views**: Cached in production using `php artisan view:cache`

### Cache Management Commands

**Clear all caches:**
```bash
php artisan cache:clear
```

**Clear specific caches:**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Rebuild caches:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Note:** Always clear caches before rebuilding them during deployment.

## Queue System

The application uses Laravel queues for background processing to improve performance and user experience.

### Development

The application is configured to use the `database` queue driver by default:

```bash
# Start the queue worker
php artisan queue:work

# Start with verbose output
php artisan queue:work --verbose

# Process only one job then exit
php artisan queue:work --once
```

### Production

For production, it's recommended to use Redis for better performance:

1. **Configure Redis in .env:**
   ```env
   QUEUE_CONNECTION=redis
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   ```

2. **Start queue worker as daemon:**
   ```bash
   php artisan queue:work --daemon --tries=3 --timeout=90
   ```

3. **Use Supervisor for process management:**

   Create a Supervisor configuration file at `/etc/supervisor/conf.d/marketplace-worker.conf`:

   ```ini
   [program:marketplace-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
   autostart=true
   autorestart=true
   stopasgroup=true
   killasgroup=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/path/to/your/project/storage/logs/worker.log
   stopwaitsecs=3600
   ```

   Then reload Supervisor:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start marketplace-worker:*
   ```

### Queued Jobs

The following operations are processed asynchronously:

- **Email notifications**: Product approvals, rejections, purchase confirmations
- **Webhook processing**: Payment gateway webhooks (Stripe, Paytm)
- **Invoice generation**: PDF invoice creation
- **File processing**: Thumbnail optimization (optional)
- **Purchase processing**: Download link generation, seller balance updates

### Queue Management Commands

```bash
# View failed jobs
php artisan queue:failed

# Retry a failed job
php artisan queue:retry {id}

# Retry all failed jobs
php artisan queue:retry all

# Delete a failed job
php artisan queue:forget {id}

# Clear all failed jobs
php artisan queue:flush

# Monitor queue in real-time (requires Laravel Horizon)
php artisan horizon
```

### Queue Monitoring

For production environments, consider using [Laravel Horizon](https://laravel.com/docs/horizon) for queue monitoring and management:

```bash
composer require laravel/horizon
php artisan horizon:install
php artisan horizon
```

Access the Horizon dashboard at `/horizon`.

## Environment Variables

Key environment variables to configure:

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# File Storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

# Payment Gateways
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

PAYTM_MERCHANT_ID=
PAYTM_MERCHANT_KEY=
PAYTM_WEBSITE=
PAYTM_ENVIRONMENT=production

# Application Settings
DOWNLOAD_LINK_EXPIRY_HOURS=24
PLATFORM_COMMISSION_PERCENT=10
```

## Performance Optimization

### Database Optimization
- Eager loading relationships to prevent N+1 queries
- Strategic database indexes on frequently queried columns
- Query result caching for expensive operations

### File Storage
- CDN integration for static assets
- Lazy loading for images
- Compressed thumbnails

### Frontend Optimization
- Minified CSS and JavaScript in production
- Lazy loading for images
- Responsive images with srcset

## Security

- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade template escaping
- Secure file storage with signed URLs
- Rate limiting on API and download endpoints
- Password hashing with bcrypt

## API Documentation

The Digital Marketplace provides a comprehensive REST API for accessing products, categories, and managing user purchases.

### Accessing the Documentation

**Interactive Documentation:**
Visit `/docs` on your application to access the interactive API documentation with a "Try It Out" feature.

**OpenAPI Specification:**
- YAML format: `/docs.openapi`
- Postman Collection: `/docs.postman`

### API Endpoints

#### Public Endpoints (No Authentication Required)

- `GET /api/v1/products` - List products with filters
- `GET /api/v1/products/{slug}` - Get product details
- `GET /api/v1/categories` - List all categories

#### Authenticated Endpoints (Requires API Token)

- `GET /api/v1/user/purchases` - Get purchased products
- `POST /api/v1/user/downloads/{download}` - Generate download link

### Authentication

The API uses Laravel Sanctum for authentication with Bearer tokens.

**Getting an API Token:**
1. Log in to your marketplace account
2. Navigate to **Settings > API Tokens**
3. Click **Create New Token**
4. Copy the generated token (shown only once)

**Using the Token:**
```bash
curl -H "Authorization: Bearer YOUR_API_TOKEN" \
     https://your-domain.com/api/v1/user/purchases
```

### Rate Limiting

| Endpoint Type | Limit | Window |
|--------------|-------|--------|
| Public endpoints | 60 requests | per minute per IP |
| Authenticated endpoints | 120 requests | per minute per user |
| Download generation | 10 requests | per hour per user |

### Regenerating Documentation

After making changes to API controllers or annotations:

```bash
php artisan scribe:generate
```

### API Documentation Files

- **Interactive docs**: `resources/views/scribe/index.blade.php`
- **OpenAPI spec**: `storage/app/private/scribe/openapi.yaml`
- **Postman collection**: `storage/app/private/scribe/collection.json`
- **Markdown files**: `.scribe/` directory

For detailed API usage examples and best practices, see [docs/API.md](docs/API.md).

## Testing

Run the test suite:

```bash
php artisan test
```

## License

This project is proprietary software.

## Support

For support, please contact the development team.
