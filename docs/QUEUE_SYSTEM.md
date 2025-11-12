# Queue System Documentation

This document provides detailed information about the queue system implementation in the Digital Marketplace application.

## Overview

The application uses Laravel's queue system to handle time-consuming tasks asynchronously, improving response times and user experience. Tasks are queued and processed in the background by queue workers.

## Queue Configuration

### Development Environment

The application uses the `database` queue driver by default, which stores jobs in the `jobs` table:

```env
QUEUE_CONNECTION=database
```

**Advantages:**
- No additional infrastructure required
- Easy to set up and debug
- Good for development and small applications

**Disadvantages:**
- Slower than Redis
- Not suitable for high-volume production use

### Production Environment

For production, use Redis for better performance:

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Advantages:**
- Much faster than database driver
- Better for high-volume applications
- Supports advanced features like delayed jobs

## Queued Components

### 1. Notifications

All notification classes implement `ShouldQueue` interface for asynchronous delivery:

#### ProductApproved Notification
- **Trigger**: Admin approves a product
- **Channels**: Email + Database
- **Recipient**: Product seller
- **Retry**: 3 attempts

#### ProductRejected Notification
- **Trigger**: Admin rejects a product
- **Channels**: Email + Database
- **Recipient**: Product seller
- **Includes**: Rejection reason
- **Retry**: 3 attempts

### 2. Jobs

#### ProcessWebhook Job
- **Purpose**: Process payment gateway webhooks asynchronously
- **Trigger**: Stripe or Paytm webhook received
- **Actions**:
  - Verify payment status
  - Update transaction record
  - Fire PurchaseCompleted event
  - Implement idempotency check
- **Retry**: 3 attempts with 10-second backoff
- **Timeout**: 60 seconds

**Example:**
```php
ProcessWebhook::dispatch($paymentId, 'stripe', $metadata);
```

#### GenerateInvoice Job
- **Purpose**: Generate PDF invoices asynchronously
- **Trigger**: Customer requests invoice download
- **Actions**:
  - Load transaction with relationships
  - Generate PDF using DomPDF
  - Store PDF in storage
- **Retry**: 3 attempts
- **Timeout**: 120 seconds

**Example:**
```php
GenerateInvoice::dispatch($transactionId);
```

#### ProcessFileUpload Job (Optional)
- **Purpose**: Process and optimize uploaded files
- **Trigger**: Product thumbnail upload
- **Actions**:
  - Create optimized thumbnail versions
  - Generate multiple sizes
  - Update product record
- **Retry**: 3 attempts
- **Timeout**: 180 seconds
- **Note**: Requires `intervention/image` package

**Example:**
```php
ProcessFileUpload::dispatch($productId, $thumbnailPath);
```

### 3. Event Listeners

#### ProcessPurchase Listener
- **Event**: PurchaseCompleted
- **Implements**: ShouldQueue
- **Actions**:
  - Create transaction items
  - Update seller balances
  - Generate download links
  - Update product download counts
  - Clear customer cart
  - Send confirmation email
- **Retry**: 3 attempts
- **Timeout**: 120 seconds

**Flow:**
```
Payment Webhook → ProcessWebhook Job → PurchaseCompleted Event → ProcessPurchase Listener
```

## Queue Workers

### Starting Queue Workers

#### Development
```bash
# Basic worker
php artisan queue:work

# With verbose output
php artisan queue:work --verbose

# Process specific queue
php artisan queue:work --queue=high,default

# Process one job then exit
php artisan queue:work --once
```

#### Production
```bash
# Daemon mode with retries and timeout
php artisan queue:work --daemon --tries=3 --timeout=90

# With memory limit
php artisan queue:work --daemon --tries=3 --timeout=90 --memory=512

# Multiple workers for different queues
php artisan queue:work --queue=high --tries=3 &
php artisan queue:work --queue=default --tries=3 &
```

### Supervisor Configuration

For production, use Supervisor to manage queue workers:

**File:** `/etc/supervisor/conf.d/marketplace-worker.conf`

```ini
[program:marketplace-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/marketplace/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/marketplace/storage/logs/worker.log
stopwaitsecs=3600
```

**Commands:**
```bash
# Reload configuration
sudo supervisorctl reread
sudo supervisorctl update

# Start workers
sudo supervisorctl start marketplace-worker:*

# Stop workers
sudo supervisorctl stop marketplace-worker:*

# Restart workers
sudo supervisorctl restart marketplace-worker:*

# Check status
sudo supervisorctl status
```

### Systemd Configuration (Alternative)

**File:** `/etc/systemd/system/marketplace-worker.service`

```ini
[Unit]
Description=Marketplace Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/marketplace
ExecStart=/usr/bin/php /var/www/marketplace/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

**Commands:**
```bash
# Enable service
sudo systemctl enable marketplace-worker

# Start service
sudo systemctl start marketplace-worker

# Stop service
sudo systemctl stop marketplace-worker

# Restart service
sudo systemctl restart marketplace-worker

# Check status
sudo systemctl status marketplace-worker
```

## Queue Priorities

You can prioritize certain jobs by using different queues:

```php
// High priority (payment processing)
ProcessWebhook::dispatch($paymentId, 'stripe', $metadata)
    ->onQueue('high');

// Normal priority (notifications)
$user->notify(new ProductApproved($product));

// Low priority (file processing)
ProcessFileUpload::dispatch($productId, $thumbnailPath)
    ->onQueue('low');
```

Start workers with priority:
```bash
php artisan queue:work --queue=high,default,low
```

## Failed Jobs

### Viewing Failed Jobs

```bash
# List all failed jobs
php artisan queue:failed

# View failed job table
php artisan queue:failed-table
php artisan migrate
```

### Retrying Failed Jobs

```bash
# Retry specific job
php artisan queue:retry {id}

# Retry all failed jobs
php artisan queue:retry all

# Retry jobs that failed in the last hour
php artisan queue:retry --range=1-100
```

### Deleting Failed Jobs

```bash
# Delete specific failed job
php artisan queue:forget {id}

# Clear all failed jobs
php artisan queue:flush
```

### Failed Job Handling

All jobs implement a `failed()` method for custom failure handling:

```php
public function failed(\Throwable $exception): void
{
    Log::error('Job failed', [
        'job' => static::class,
        'error' => $exception->getMessage(),
        'timestamp' => now()->toIso8601String(),
    ]);
    
    // Send alert to admin
    // Update database status
    // etc.
}
```

## Job Chaining

Chain multiple jobs to run sequentially:

```php
use Illuminate\Support\Facades\Bus;

Bus::chain([
    new ProcessWebhook($paymentId, 'stripe', $metadata),
    new GenerateInvoice($transactionId),
    new SendInvoiceEmail($transactionId),
])->dispatch();
```

## Job Batching

Process multiple jobs as a batch:

```php
use Illuminate\Support\Facades\Bus;

$batch = Bus::batch([
    new ProcessFileUpload($productId1, $path1),
    new ProcessFileUpload($productId2, $path2),
    new ProcessFileUpload($productId3, $path3),
])->then(function (Batch $batch) {
    // All jobs completed successfully
})->catch(function (Batch $batch, Throwable $e) {
    // First batch job failure detected
})->finally(function (Batch $batch) {
    // The batch has finished executing
})->dispatch();
```

## Delayed Jobs

Delay job execution:

```php
// Delay by 10 minutes
ProcessFileUpload::dispatch($productId, $thumbnailPath)
    ->delay(now()->addMinutes(10));

// Delay until specific time
GenerateInvoice::dispatch($transactionId)
    ->delay(now()->addHour());
```

## Rate Limiting

Limit job execution rate:

```php
use Illuminate\Support\Facades\Redis;

public function handle()
{
    Redis::throttle('key')
        ->allow(10)
        ->every(60)
        ->then(function () {
            // Job logic
        }, function () {
            // Could not obtain lock, release job back to queue
            return $this->release(10);
        });
}
```

## Monitoring and Debugging

### Laravel Horizon

For advanced queue monitoring, use Laravel Horizon:

```bash
composer require laravel/horizon
php artisan horizon:install
php artisan migrate
php artisan horizon
```

Access dashboard at: `http://your-domain.com/horizon`

**Features:**
- Real-time queue monitoring
- Job metrics and statistics
- Failed job management
- Queue configuration
- Worker supervision

### Queue Metrics

Monitor these metrics in production:

- **Jobs processed per minute**: Throughput
- **Average job duration**: Performance
- **Failed job rate**: Reliability
- **Queue depth**: Backlog
- **Worker memory usage**: Resource utilization

### Debugging

Enable verbose logging:

```bash
# Verbose output
php artisan queue:work --verbose

# Very verbose output
php artisan queue:work -vvv
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

## Best Practices

### 1. Keep Jobs Small and Focused

```php
// Bad: One large job
class ProcessEverything implements ShouldQueue
{
    public function handle()
    {
        $this->processPayment();
        $this->sendEmails();
        $this->generateReports();
        $this->updateAnalytics();
    }
}

// Good: Multiple focused jobs
ProcessWebhook::dispatch($paymentId, 'stripe', $metadata);
SendPurchaseConfirmation::dispatch($transactionId);
GenerateInvoice::dispatch($transactionId);
UpdateAnalytics::dispatch($transactionId);
```

### 2. Implement Idempotency

Ensure jobs can be safely retried:

```php
public function handle()
{
    // Check if already processed
    if ($this->transaction->status === 'completed') {
        return;
    }
    
    // Process transaction
    $this->transaction->update(['status' => 'completed']);
}
```

### 3. Set Appropriate Timeouts

```php
public $timeout = 120; // 2 minutes

public function handle()
{
    // Long-running task
}
```

### 4. Use Job Middleware

```php
public function middleware()
{
    return [
        new RateLimited('webhooks'),
        new WithoutOverlapping($this->paymentId),
    ];
}
```

### 5. Handle Failures Gracefully

```php
public $tries = 3;
public $backoff = [10, 30, 60]; // Exponential backoff

public function failed(\Throwable $exception)
{
    // Notify admin
    // Log error
    // Update status
}
```

## Troubleshooting

### Jobs Not Processing

1. **Check if worker is running:**
   ```bash
   ps aux | grep "queue:work"
   ```

2. **Check queue connection:**
   ```bash
   php artisan queue:work --once
   ```

3. **Check failed jobs:**
   ```bash
   php artisan queue:failed
   ```

### High Memory Usage

1. **Restart workers regularly:**
   ```bash
   php artisan queue:restart
   ```

2. **Set memory limit:**
   ```bash
   php artisan queue:work --memory=512
   ```

3. **Use max-time option:**
   ```bash
   php artisan queue:work --max-time=3600
   ```

### Slow Job Processing

1. **Increase worker count:**
   ```ini
   numprocs=8  # In Supervisor config
   ```

2. **Use Redis instead of database:**
   ```env
   QUEUE_CONNECTION=redis
   ```

3. **Optimize job code:**
   - Use eager loading
   - Minimize database queries
   - Cache expensive operations

## Security Considerations

### 1. Validate Job Data

```php
public function handle()
{
    if (!$this->transaction->exists) {
        throw new \Exception('Invalid transaction');
    }
    
    // Process job
}
```

### 2. Encrypt Sensitive Data

```php
use Illuminate\Contracts\Encryption\Encrypter;

public function __construct(
    private Encrypter $encrypter,
    private string $sensitiveData
) {
    $this->sensitiveData = $encrypter->encrypt($sensitiveData);
}
```

### 3. Limit Job Retries

```php
public $tries = 3;
public $maxExceptions = 3;
```

### 4. Implement Authorization

```php
public function handle()
{
    if (!Gate::allows('process-payment', $this->transaction)) {
        throw new \Exception('Unauthorized');
    }
    
    // Process job
}
```

## Additional Resources

- [Laravel Queue Documentation](https://laravel.com/docs/queues)
- [Laravel Horizon Documentation](https://laravel.com/docs/horizon)
- [Supervisor Documentation](http://supervisord.org/)
- [Redis Documentation](https://redis.io/documentation)
