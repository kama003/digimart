# Logging Configuration

This document describes the logging setup for the Digital Marketplace application.

## Overview

The application uses Laravel's built-in logging system with multiple channels for different types of events. Logs are stored in the `storage/logs` directory.

## Log Channels

### 1. Default Channel (Stack)
- **Configuration**: `config/logging.php` - `stack` channel
- **Environment Variable**: `LOG_CHANNEL=stack` (default)
- **Purpose**: General application logging
- **Log File**: `storage/logs/laravel.log`
- **Retention**: 14 days (configurable via `LOG_DAILY_DAYS`)

### 2. Security Channel
- **Configuration**: `config/logging.php` - `security` channel
- **Purpose**: Security-related events
- **Log File**: `storage/logs/security.log`
- **Retention**: 90 days (recommended for compliance)
- **Events Logged**:
  - Unauthorized access attempts (unauthenticated users)
  - Banned user access attempts
  - Role-based access violations
  - Failed authentication attempts

**Example Log Entry**:
```json
{
  "level": "warning",
  "message": "Unauthorized role access attempt",
  "context": {
    "user_id": 123,
    "email": "user@example.com",
    "user_role": "customer",
    "required_roles": ["seller", "admin"],
    "ip": "192.168.1.1",
    "url": "https://example.com/seller/dashboard",
    "method": "GET",
    "timestamp": "2025-11-12T10:30:00+00:00"
  }
}
```

### 3. Payment Channel
- **Configuration**: `config/logging.php` - `payment` channel
- **Purpose**: Payment and transaction-related events
- **Log File**: `storage/logs/payment.log`
- **Retention**: 90 days (recommended for financial records)
- **Events Logged**:
  - Payment intent creation (success/failure)
  - Payment confirmation (success/failure)
  - Payment refunds (success/failure)
  - Webhook processing (success/failure)
  - Checkout process errors
  - Payment gateway errors (Stripe, Paytm)

**Example Log Entry**:
```json
{
  "level": "error",
  "message": "Stripe payment intent creation failed",
  "context": {
    "error": "Invalid API key provided",
    "error_code": "invalid_api_key",
    "amount": 99.99,
    "metadata": {
      "transaction_id": 456,
      "user_id": 123
    },
    "timestamp": "2025-11-12T10:30:00+00:00"
  }
}
```

## Logged Events by Component

### Authentication & Authorization
**Component**: `app/Http/Middleware/RoleMiddleware.php`
- Unauthenticated access attempts → `security` channel (warning)
- Banned user access attempts → `security` channel (warning)
- Role-based access violations → `security` channel (warning)

### Payment Processing
**Components**: 
- `app/Services/Payment/StripePaymentGateway.php`
- `app/Services/Payment/PaytmPaymentGateway.php`
- `app/Http/Controllers/PaytmCallbackController.php`
- `app/Livewire/Checkout/CheckoutForm.php`

**Events**:
- Payment intent creation failures → `payment` channel (error)
- Payment confirmation failures → `payment` channel (error)
- Refund failures → `payment` channel (error)
- Webhook verification failures → `payment` channel (error)
- Checkout process failures → `payment` channel (error)
- Payment failures → `payment` channel (warning)

### File Operations
**Components**:
- `app/Services/StorageService.php`
- `app/Http/Controllers/DownloadController.php`
- `app/Livewire/Seller/ProductUpload.php`
- `app/Livewire/Seller/ProductEdit.php`

**Events**:
- File upload success → default channel (info)
- File upload failures → default channel (error)
- File deletion failures → default channel (error)
- Temporary URL generation failures → default channel (error)
- Download link generation failures → default channel (error)
- Product upload success → default channel (info)
- Product upload failures → default channel (error)
- Product update success → default channel (info)
- Product update failures → default channel (error)

## Log Levels

The application uses standard PSR-3 log levels:

1. **emergency**: System is unusable
2. **alert**: Action must be taken immediately
3. **critical**: Critical conditions
4. **error**: Error conditions (e.g., payment failures, file upload errors)
5. **warning**: Warning conditions (e.g., unauthorized access, payment failures)
6. **notice**: Normal but significant conditions
7. **info**: Informational messages (e.g., successful operations)
8. **debug**: Debug-level messages

## Environment Configuration

### Development
```env
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug
LOG_DAILY_DAYS=14
```

### Production
```env
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=info
LOG_DAILY_DAYS=14
```

### Production with Error Tracking (Sentry)
```env
LOG_CHANNEL=stack
LOG_STACK=daily,sentry
LOG_LEVEL=info
SENTRY_LARAVEL_DSN=your_sentry_dsn_here
```

## Production Error Tracking (Optional)

### Sentry Integration

Sentry provides real-time error tracking and monitoring for production environments.

**Installation**:
```bash
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn=YOUR_SENTRY_DSN
```

**Configuration**:
1. Sign up for Sentry at https://sentry.io
2. Create a new Laravel project in Sentry
3. Copy your DSN from the project settings
4. Add to `.env`:
   ```env
   SENTRY_LARAVEL_DSN=https://your-dsn@sentry.io/project-id
   ```
5. Update `LOG_STACK` in `.env`:
   ```env
   LOG_STACK=daily,sentry
   ```
6. Uncomment the Sentry channel in `config/logging.php`

**Features**:
- Real-time error notifications
- Error grouping and deduplication
- Stack trace analysis
- User context tracking
- Release tracking
- Performance monitoring

### Bugsnag Integration

Bugsnag is an alternative error tracking service.

**Installation**:
```bash
composer require bugsnag/bugsnag-laravel
php artisan vendor:publish --provider="Bugsnag\BugsnagLaravel\BugsnagServiceProvider"
```

**Configuration**:
1. Sign up for Bugsnag at https://www.bugsnag.com
2. Create a new project
3. Copy your API key
4. Add to `.env`:
   ```env
   BUGSNAG_API_KEY=your_api_key_here
   ```
5. Update `LOG_STACK` in `.env`:
   ```env
   LOG_STACK=daily,bugsnag
   ```

## Log Rotation

Logs are automatically rotated based on the channel configuration:

- **Daily logs**: New file created each day, old files deleted after retention period
- **Single logs**: Single file that grows indefinitely (not recommended for production)

## Monitoring and Alerts

### Recommended Monitoring

1. **Disk Space**: Monitor `storage/logs` directory size
2. **Error Rate**: Track error-level logs per hour/day
3. **Security Events**: Alert on multiple failed access attempts
4. **Payment Failures**: Alert on payment gateway errors

### Log Analysis Tools

- **Laravel Telescope**: Development debugging (already installed)
- **Logstash**: Log aggregation and analysis
- **Elasticsearch + Kibana**: Log search and visualization
- **Papertrail**: Cloud-based log management
- **Loggly**: Cloud-based log analysis

## Best Practices

1. **Never log sensitive data**: Passwords, credit card numbers, API keys
2. **Use appropriate log levels**: Don't log everything as `error`
3. **Include context**: User ID, IP address, timestamps
4. **Monitor log file sizes**: Implement rotation and cleanup
5. **Review logs regularly**: Look for patterns and anomalies
6. **Set up alerts**: Critical errors should trigger notifications
7. **Comply with regulations**: Retain logs as required by law (GDPR, PCI-DSS)

## Accessing Logs

### Via Command Line
```bash
# View latest logs
tail -f storage/logs/laravel.log

# View security logs
tail -f storage/logs/security.log

# View payment logs
tail -f storage/logs/payment.log

# Search for specific errors
grep "error" storage/logs/laravel.log

# View last 100 lines
tail -n 100 storage/logs/laravel.log
```

### Via Laravel Telescope
Access Telescope at `/telescope` (development only) to view:
- Requests
- Exceptions
- Logs
- Queries
- Jobs
- Events

## Troubleshooting

### Logs not being written
1. Check file permissions: `storage/logs` should be writable
2. Check disk space: Ensure sufficient space available
3. Check log level: Ensure `LOG_LEVEL` is appropriate
4. Check channel configuration: Verify `config/logging.php`

### Log files too large
1. Implement log rotation: Use `daily` driver instead of `single`
2. Reduce retention period: Lower `LOG_DAILY_DAYS`
3. Increase log level: Use `warning` or `error` in production
4. Archive old logs: Move to long-term storage

### Missing context in logs
1. Ensure all log calls include context array
2. Add user ID, IP address, timestamps to context
3. Use structured logging (JSON format)

## Security Considerations

1. **Protect log files**: Ensure logs are not publicly accessible
2. **Sanitize sensitive data**: Never log passwords, tokens, or PII
3. **Encrypt logs**: Consider encrypting logs at rest
4. **Access control**: Limit who can access log files
5. **Audit log access**: Track who accesses logs and when
6. **Compliance**: Follow GDPR, HIPAA, PCI-DSS requirements

## Related Documentation

- [Laravel Logging Documentation](https://laravel.com/docs/11.x/logging)
- [Monolog Documentation](https://github.com/Seldaek/monolog)
- [PSR-3 Logger Interface](https://www.php-fig.org/psr/psr-3/)
- [Sentry Laravel Documentation](https://docs.sentry.io/platforms/php/guides/laravel/)
- [Bugsnag Laravel Documentation](https://docs.bugsnag.com/platforms/php/laravel/)
