# Project Structure

## Directory Organization

### `/app`

- **Actions**: Reusable action classes for business logic
- **Console**: Artisan commands
- **Contracts**: Interfaces for dependency injection
- **Enums**: Enumeration classes for type-safe constants
- **Events**: Event classes (e.g., PurchaseCompleted)
- **Helpers**: Helper functions and utilities (e.g., PaytmChecksum)
- **Http/Controllers**: Traditional controllers (API, Download, Invoice, Webhook, Paytm Callback)
- **Http/Middleware**: Custom middleware (RoleMiddleware for authorization)
- **Http/Resources**: API resource transformers (ProductResource)
- **Jobs**: Queueable job classes
- **Listeners**: Event listeners (e.g., ProcessPurchase)
- **Livewire**: Livewire components organized by feature
  - `Admin/*`: Admin dashboard and management components
  - `Cart/*`: Shopping cart components
  - `Checkout/*`: Checkout flow components
  - `Customer/*`: Customer purchase and billing history
  - `Notifications/*`: Notification center and dropdown
  - `Pages/*`: Static page components (About, Contact, Terms, etc.)
  - `Product/*`: Product browsing, detail, search, reviews
  - `Profile/*`: User profile and seller role request
  - `Seller/*`: Seller dashboard, analytics, products, withdrawals
  - `Settings/*`: User settings (API tokens)
- **Models**: Eloquent models with relationships and scopes
- **Notifications**: Notification classes (database + mail channels)
- **Policies**: Authorization policies for models
- **Providers**: Service providers (AppServiceProvider registers singletons and policies)
- **Services**: Business logic services (CartService, StorageService, NotificationService)

### `/bootstrap`

- `app.php`: Application bootstrap with routing, middleware, rate limiters
- `providers.php`: Service provider registration

### `/config`

Configuration files for app, auth, cache, database, filesystems, fortify, logging, mail, payment, queue, sanctum, scribe, services, session

### `/database`

- **factories**: Model factories for testing
- **migrations**: Database schema migrations
- **seeders**: Database seeders (AdminUserSeeder)

### `/docs`

Project documentation:
- `API.md`: API usage guide
- `DATABASE_OPTIMIZATION.md`: Database performance strategies
- `PERFORMANCE_OPTIMIZATION_SUMMARY.md`: Performance improvements
- `PREVIEW_SYSTEM.md`: Product preview functionality
- `QUEUE_SYSTEM.md`: Queue configuration and management
- `REVIEWS.md`: Review system documentation
- `STATIC_PAGES.md`: Static page implementation

### `/public`

Public assets and entry point (`index.php`)

### `/resources`

- **css**: Tailwind CSS source files
- **docs**: Scribe API documentation markdown files
- **js**: JavaScript source files
- **views**: Blade templates
  - `components/`: Reusable Blade components
    - `layouts/`: Layout components (app, guest)
  - `livewire/`: Livewire component views (mirrors `/app/Livewire` structure)
  - `scribe/`: API documentation views
  - `dashboard.blade.php`: Main dashboard
  - `welcome.blade.php`: Homepage

### `/routes`

- `web.php`: Web routes (Livewire components, static pages, downloads)
- `api.php`: API routes (v1 prefix, Sanctum authentication)
- `console.php`: Artisan command routes

### `/storage`

- **app/private**: Private file storage (digital products, invoices)
- **app/public**: Public file storage (thumbnails)
- **framework**: Framework cache and sessions
- **logs**: Application logs

### `/tests`

- **Feature**: Feature tests (API tests, rate limiting)
- **Unit**: Unit tests

## Naming Conventions

### Livewire Components

- **Class names**: PascalCase (e.g., `ProductSearch`, `SellerDashboard`)
- **File location**: `/app/Livewire/{Feature}/{ComponentName}.php`
- **View location**: `/resources/views/livewire/{feature}/{component-name}.blade.php`
- **Route naming**: Kebab-case (e.g., `seller.products.create`)

### Models

- **Singular PascalCase**: `Product`, `User`, `Transaction`, `Review`
- **Table names**: Plural snake_case (e.g., `products`, `users`, `transactions`)

### Routes

- **Web routes**: Named routes using dot notation (e.g., `seller.dashboard`, `product.show`)
- **API routes**: Versioned with prefix `/api/v1`
- **Route groups**: Organized by feature with middleware and prefix

### Blade Components

- **Component tags**: Kebab-case (e.g., `<x-footer-links />`, `<x-layouts.app />`)
- **File location**: `/resources/views/components/`

## Key Architectural Patterns

### Livewire Component Structure

Components follow this pattern:
1. Public properties for state (with `$queryString` for URL persistence)
2. `mount()` method for initialization
3. Event listeners using `#[On('event-name')]` attribute
4. Action methods for user interactions
5. `render()` method returning view with data

### Service Layer

Services registered as singletons in `AppServiceProvider`:
- `CartService`: Session-based cart management
- `StorageService`: File upload/download handling
- `NotificationService`: Centralized notification dispatch

### Authorization Flow

1. Middleware checks role and ban status (`RoleMiddleware`)
2. Policies authorize specific actions (`ProductPolicy`, `UserPolicy`, etc.)
3. Gates registered in `AppServiceProvider`

### Payment Flow

1. User initiates checkout → Transaction created (pending)
2. Payment gateway processes payment
3. Webhook confirms payment → Transaction updated (completed)
4. PurchaseCompleted event fired → ProcessPurchase listener queued
5. Background job: Generate download links, update seller balance, send notifications

### File Storage

- **Thumbnails**: Public disk (`storage/app/public`)
- **Digital products**: Private disk with signed URLs
- **Production**: S3/Spaces with private ACL
- **Download links**: Time-limited signed URLs (24-hour expiry)

## Configuration Files

- **payment.php**: Payment gateway settings (Stripe, Paytm)
- **scribe.php**: API documentation configuration
- **fortify.php**: Authentication features (login, registration, 2FA)
- **sanctum.php**: API token configuration
