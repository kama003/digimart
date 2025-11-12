# Security Implementation

This document outlines the security measures implemented in the Digital Marketplace application.

## Authentication Security

### Password Hashing
- **BCRYPT_ROUNDS=12**: Configured in `.env` for strong password hashing
- All passwords are hashed using Laravel's bcrypt implementation

### Session Security
- **SESSION_HTTP_ONLY=true**: Prevents JavaScript access to session cookies
- **SESSION_SECURE_COOKIE**: Set to `true` in production to ensure cookies are only sent over HTTPS
- **SESSION_SAME_SITE**: Set to `strict` in production to prevent CSRF attacks
- Session driver: Database (more secure than file-based sessions)

### Rate Limiting
- **Login attempts**: Limited to 5 attempts per minute per email/IP combination
- **Two-factor authentication**: Limited to 5 attempts per minute per session
- **Download requests**: Limited to 10 requests per hour per user
- Configured in `app/Providers/FortifyServiceProvider.php`

### Email Verification
- Email verification is available via Laravel Fortify
- Can be enabled in `config/fortify.php` by uncommenting `Features::emailVerification()`

## File Security

### Storage Configuration
- **Private visibility**: All digital assets stored with private ACL in S3/Spaces
- **Signed URLs**: All download links use time-limited, cryptographically signed URLs
- **Default expiration**: 24 hours (configurable via `DOWNLOAD_LINK_EXPIRY_HOURS`)

### File Upload Validation
- **MIME type validation**: Server-side validation of file types based on product type
- **File size limits**: 
  - Thumbnails: 5MB max
  - Product files: 500MB max
- **Allowed file types**:
  - Thumbnails: JPEG, PNG, WebP
  - Audio: MP3, WAV, OGG
  - Video: MP4, MPEG, QuickTime, WebM
  - 3D: GLTF, GLB, ZIP
  - Templates: ZIP
  - Graphics: PNG, JPEG, SVG, ZIP, PostScript

### File Access Control
- No direct file URLs exposed in views or API responses
- All downloads go through authenticated controller with authorization checks
- Rate limiting applied to download endpoints

## Payment Security

### PCI Compliance
- **No card storage**: Application never stores credit card details
- **Gateway tokens**: Uses Stripe Elements and Paytm tokens for payment processing
- **Secure credentials**: All API keys stored in environment variables

### Webhook Security
- **Signature verification**: 
  - Stripe: Verifies webhook signatures using `STRIPE_WEBHOOK_SECRET`
  - Paytm: Verifies checksums using merchant key
- **Idempotency**: Prevents duplicate payment processing using `payment_id` checks
- **CSRF exemption**: Webhook routes excluded from CSRF protection
- **Logging**: Failed webhook verifications are logged for security monitoring

### Payment Flow Security
1. Transaction created with `pending` status
2. Payment intent created with gateway
3. Payment processed by gateway (client-side)
4. Webhook confirms payment (server-side)
5. Transaction updated to `completed` status
6. Purchase processing triggered only after webhook confirmation

## Data Protection

### Encryption
- **Sensitive data**: Withdrawal account details encrypted using Laravel's `encrypted` cast
- **Application key**: Strong encryption key generated and stored in `APP_KEY`
- **Database encryption**: Automatic encryption/decryption for sensitive fields

### SQL Injection Prevention
- **Eloquent ORM**: All database queries use Eloquent ORM with parameter binding
- **No raw queries**: Application avoids raw SQL queries without proper bindings
- **Input validation**: All user inputs validated using Form Requests or Livewire validation

### XSS Prevention
- **Blade escaping**: All output uses `{{ }}` syntax for automatic escaping
- **No unescaped output**: Application avoids `{!! !!}` syntax unless absolutely necessary
- **Content Security Policy**: Consider implementing CSP headers in production

### CSRF Protection
- **Enabled by default**: Laravel's CSRF protection enabled for all POST/PUT/DELETE requests
- **Token validation**: All forms include CSRF tokens
- **Webhook exemption**: Only webhook routes excluded from CSRF protection

### HTTPS Enforcement
- **Production setting**: Set `APP_FORCE_HTTPS=true` in production `.env`
- **Secure cookies**: Session cookies marked as secure in production
- **Proxy trust**: TrustProxies middleware configured for load balancers

## Authorization

### Role-Based Access Control
- **Three roles**: Customer, Seller, Admin
- **Middleware protection**: `RoleMiddleware` checks user role and ban status
- **Policy-based**: Laravel policies used for resource authorization
  - `UserPolicy`: User management operations
  - `ProductPolicy`: Product CRUD operations
  - `TransactionPolicy`: Transaction viewing
  - `WithdrawalPolicy`: Withdrawal management

### Ban System
- **Account banning**: Admins can ban user accounts
- **Automatic logout**: Banned users are automatically logged out
- **Access prevention**: Banned users cannot access protected routes

## Security Logging

### Event Logging
- **Authentication events**: Login attempts, failed logins, logouts
- **Payment events**: Payment intents, confirmations, failures, refunds
- **File operations**: Uploads, downloads, deletions
- **Security events**: Unauthorized access attempts, webhook verification failures
- **Admin actions**: User bans, role changes, product approvals/rejections

### Log Channels
- **Default channel**: Stack (configured in `config/logging.php`)
- **Payment channel**: Separate channel for payment-related logs
- **Security channel**: Consider adding dedicated security log channel

### Log Storage
- **Location**: `storage/logs/`
- **Rotation**: Configure log rotation in production
- **Monitoring**: Consider integrating with Sentry or Bugsnag for production error tracking

## Production Checklist

### Environment Configuration
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_FORCE_HTTPS=true`
- [ ] Set `SESSION_SECURE_COOKIE=true`
- [ ] Set `SESSION_SAME_SITE=strict`
- [ ] Generate strong `APP_KEY` (if not already done)
- [ ] Configure proper `APP_URL`

### Payment Gateway Configuration
- [ ] Set production Stripe keys (`STRIPE_KEY`, `STRIPE_SECRET`)
- [ ] Set Stripe webhook secret (`STRIPE_WEBHOOK_SECRET`)
- [ ] Set production Paytm credentials
- [ ] Configure webhook endpoints in gateway dashboards
- [ ] Test webhook delivery

### File Storage Configuration
- [ ] Configure S3 or DigitalOcean Spaces credentials
- [ ] Set `FILESYSTEM_DISK=s3`
- [ ] Verify bucket permissions (private access)
- [ ] Test file upload and download functionality
- [ ] Configure CDN for public assets (optional)

### Database Security
- [ ] Use strong database password
- [ ] Restrict database access to application server only
- [ ] Enable SSL for database connections (if supported)
- [ ] Regular database backups
- [ ] Encrypt database backups

### Server Security
- [ ] Keep server software updated
- [ ] Configure firewall rules
- [ ] Enable fail2ban or similar intrusion prevention
- [ ] Use SSH keys instead of passwords
- [ ] Disable root login
- [ ] Configure SSL/TLS certificates (Let's Encrypt recommended)
- [ ] Enable HTTP/2
- [ ] Configure security headers (HSTS, X-Frame-Options, etc.)

### Application Security
- [ ] Run `composer audit` to check for vulnerable dependencies
- [ ] Run `npm audit` to check for vulnerable npm packages
- [ ] Configure rate limiting for API endpoints
- [ ] Set up monitoring and alerting
- [ ] Configure log rotation
- [ ] Set up automated backups
- [ ] Test disaster recovery procedures

### Compliance
- [ ] Review GDPR compliance requirements
- [ ] Implement data export functionality (if required)
- [ ] Implement data deletion functionality (if required)
- [ ] Create privacy policy
- [ ] Create terms of service
- [ ] Implement cookie consent (if required)

## Security Best Practices

### Regular Maintenance
1. **Update dependencies**: Run `composer update` and `npm update` regularly
2. **Security patches**: Apply Laravel security patches promptly
3. **Audit logs**: Review security logs regularly for suspicious activity
4. **Backup verification**: Test backup restoration regularly
5. **Penetration testing**: Consider periodic security audits

### Monitoring
1. **Failed login attempts**: Monitor for brute force attacks
2. **Webhook failures**: Alert on repeated webhook verification failures
3. **File upload patterns**: Monitor for unusual upload activity
4. **Payment anomalies**: Alert on unusual payment patterns
5. **Error rates**: Monitor application error rates

### Incident Response
1. **Security incident plan**: Document response procedures
2. **Contact information**: Maintain list of security contacts
3. **Backup access**: Ensure backup admin access methods
4. **Communication plan**: Prepare user notification templates
5. **Recovery procedures**: Document system recovery steps

## Reporting Security Issues

If you discover a security vulnerability, please email security@example.com. Do not create public GitHub issues for security vulnerabilities.

## Additional Resources

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Stripe Security Best Practices](https://stripe.com/docs/security)
- [AWS S3 Security Best Practices](https://docs.aws.amazon.com/AmazonS3/latest/userguide/security-best-practices.html)
