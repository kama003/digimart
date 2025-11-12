# Database Query Optimization Guide

This document outlines the database optimization strategies implemented in the Digital Marketplace application.

## Eager Loading

### What is N+1 Query Problem?

The N+1 query problem occurs when you load a collection of models and then access a relationship on each model, causing N additional queries (one for each model).

**Example of N+1 Problem:**
```php
// This causes N+1 queries
$products = Product::all(); // 1 query
foreach ($products as $product) {
    echo $product->category->name; // N queries (one per product)
}
```

**Solution with Eager Loading:**
```php
// This causes only 2 queries
$products = Product::with('category')->all(); // 2 queries total
foreach ($products as $product) {
    echo $product->category->name; // No additional queries
}
```

### Implemented Eager Loading

The following components use eager loading to prevent N+1 queries:

#### Product Components
- **ProductList**: Loads `category` and `user` relationships
- **ProductSearch**: Loads `category` and `user` relationships
- **ProductCard**: Receives pre-loaded relationships from parent
- **ProductDetail**: Loads `category` and `user` relationships

#### Seller Components
- **SellerDashboard**: Loads `product` relationship for top products
- **SellerProducts**: Loads `category` relationship and counts downloads
- **SellerAnalytics**: Uses aggregated queries to minimize database hits

#### Admin Components
- **UserManagement**: No relationships needed (users only)
- **ProductModeration**: Loads `user` and `category` relationships
- **SellerRoleRequests**: Loads `user` relationship

#### Cart System
- **CartService**: Loads `product.category` and `product.user` relationships

#### Download System
- **Download Model**: Automatically eager loads `product` relationship using `$with` property

## Column Selection

### Why Limit Columns?

Selecting only needed columns reduces:
- Memory usage
- Network transfer time
- Database I/O

**Example:**
```php
// Bad: Loads all columns including large text fields
$products = Product::all();

// Good: Loads only needed columns
$products = Product::select(['id', 'title', 'price', 'thumbnail_path'])->get();
```

### Implemented Column Selection

- **ProductList**: Selects only display columns (excludes large description fields)
- **ProductSearch**: Selects only display columns
- **SellerDashboard**: Selects only needed columns for top products

## Query Logging

### Development Environment

Query logging is enabled in development to identify slow queries:

```php
// In AppServiceProvider.php
if (config('app.debug')) {
    DB::listen(function ($query) {
        if ($query->time > 100) {
            Log::warning('Slow query detected', [
                'sql' => $query->sql,
                'time' => $query->time . 'ms',
            ]);
        }
    });
}
```

### Viewing Slow Queries

Check the Laravel log file for slow query warnings:
```bash
tail -f storage/logs/laravel.log | grep "Slow query"
```

## Database Indexes

### Existing Indexes

The following indexes are defined in migrations:

**Users Table:**
- `email` (unique)
- `role`
- `is_banned`

**Products Table:**
- `user_id`
- `category_id`
- `slug` (unique)
- `(is_approved, is_active)` (composite)
- `created_at`

**Transactions Table:**
- `user_id`
- `payment_id` (unique)
- `status`
- `created_at`

**Transaction Items Table:**
- `transaction_id`
- `product_id`
- `seller_id`

**Downloads Table:**
- `user_id`
- `product_id`
- `expires_at`

**Carts Table:**
- `user_id`
- `session_id`
- `(user_id, product_id)` (composite)
- `(session_id, product_id)` (composite)

**Withdrawals Table:**
- `user_id`
- `status`

### Adding New Indexes

If you identify slow queries, consider adding indexes:

```php
// In a new migration
Schema::table('products', function (Blueprint $table) {
    $table->index('downloads_count'); // If sorting by downloads frequently
});
```

## Pagination Strategies

### Standard Pagination

Used for most lists with page numbers:
```php
$products = Product::paginate(15);
```

**Pros:**
- Shows total count
- Allows jumping to specific pages
- Familiar UX

**Cons:**
- Slower on large datasets
- COUNT(*) query can be expensive

### Cursor Pagination

Consider for infinite scroll or very large datasets:
```php
$products = Product::cursorPaginate(20);
```

**Pros:**
- Faster on large datasets
- No COUNT(*) query
- Consistent performance

**Cons:**
- No page numbers
- Can't jump to specific page
- Only forward/backward navigation

### Current Implementation

- **Product Lists**: Standard pagination (user-friendly)
- **Admin Lists**: Standard pagination (need page numbers)
- **Infinite Scroll**: Load more button (ProductList component)

## Caching Strategies

### Query Result Caching

Expensive queries are cached:

```php
// Cache categories for 1 hour
$categories = cache()->remember('categories', 3600, function () {
    return Category::orderBy('order')->get();
});
```

### Cached Queries

- **Categories**: 1 hour (rarely change)
- **Top Products**: 1 hour (updated periodically)
- **Featured Products**: 30 minutes

### Cache Invalidation

Clear caches when data changes:

```php
// After creating/updating a category
cache()->forget('categories');
cache()->forget('homepage.categories');
cache()->forget('search.categories');
```

## Best Practices

### 1. Always Use Eager Loading

```php
// Bad
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->name; // N+1 query
}

// Good
$products = Product::with('category')->all();
foreach ($products as $product) {
    echo $product->category->name; // No extra queries
}
```

### 2. Select Only Needed Columns

```php
// Bad
$products = Product::all(); // Loads all columns

// Good
$products = Product::select(['id', 'title', 'price'])->get();
```

### 3. Use Indexes for Filtered Columns

```php
// If you frequently filter by a column, add an index
Schema::table('products', function (Blueprint $table) {
    $table->index('is_featured');
});
```

### 4. Avoid SELECT * in Production

```php
// Bad
$products = DB::select('SELECT * FROM products');

// Good
$products = DB::select('SELECT id, title, price FROM products');
```

### 5. Use Chunk for Large Datasets

```php
// Process large datasets in chunks to avoid memory issues
Product::chunk(100, function ($products) {
    foreach ($products as $product) {
        // Process product
    }
});
```

### 6. Monitor Query Performance

Use Laravel Telescope or Debugbar in development:

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

## Troubleshooting

### Identifying N+1 Queries

1. **Enable Query Logging:**
   ```php
   DB::enableQueryLog();
   // Your code here
   dd(DB::getQueryLog());
   ```

2. **Use Laravel Debugbar:**
   ```bash
   composer require barryvdh/laravel-debugbar --dev
   ```

3. **Check Telescope:**
   Visit `/telescope/queries` to see all queries

### Fixing Slow Queries

1. **Add Eager Loading:**
   ```php
   $products = Product::with('category', 'user')->get();
   ```

2. **Add Database Index:**
   ```php
   Schema::table('products', function (Blueprint $table) {
       $table->index('column_name');
   });
   ```

3. **Cache Results:**
   ```php
   $data = cache()->remember('key', 3600, function () {
       return ExpensiveQuery::get();
   });
   ```

4. **Optimize Query:**
   ```php
   // Use select() to limit columns
   // Use whereHas() instead of has() when you need to filter
   // Use exists() instead of count() when checking existence
   ```

## Performance Monitoring

### Production Monitoring

Consider using:
- **Laravel Telescope** (development only)
- **New Relic** (production APM)
- **Blackfire** (profiling)
- **Scout APM** (Laravel-specific)

### Key Metrics to Monitor

- Average query time
- Number of queries per request
- Slow query count
- Database connection pool usage
- Cache hit rate

## Additional Resources

- [Laravel Query Optimization](https://laravel.com/docs/queries)
- [Eloquent Performance](https://laravel.com/docs/eloquent)
- [Database Indexing Best Practices](https://use-the-index-luke.com/)
