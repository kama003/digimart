# Implementation Plan

## Current Status
The project is a fresh Laravel 11 installation with Livewire 3 (using Flux UI components), Laravel Fortify with two-factor authentication, and basic authentication scaffolding. The database is configured for MySQL, and basic user authentication (login, registration, password reset, email verification, two-factor auth) is already functional. The following tasks represent the complete implementation needed to build the digital marketplace.

- [x] 1. Set up database schema and models







- [x] 1.1 Extend User model and create migrations



  - Create app/Enums directory and UserRole enum (Customer, Seller, Admin)
  - Add migration to extend users table with role (enum: customer/seller/admin, default 'customer'), balance (decimal 10,2, default 0), is_banned (boolean, default false) columns
  - Update User model fillable array to include role, balance, is_banned
  - Add role and balance casts to User model (role => UserRole::class, balance => 'decimal:2', is_banned => 'boolean')
  - Add role helper methods to User model: isAdmin(), isSeller(), isCustomer()
  - _Requirements: 1.4, 16.1_
-

- [x] 1.2 Create product-related migrations and models


  - Create categories migration with fields: name (string), slug (string unique), description (text nullable), icon (string nullable), order (integer default 0), timestamps
  - Create products migration with fields: user_id (foreign key), title (string), slug (string unique), description (text), short_description (text nullable), category_id (foreign key), product_type (enum: audio/video/3d/template/graphic), price (decimal 10,2), license_type (string), thumbnail_path (string), file_path (string), file_size (bigInteger), is_approved (boolean default false), is_active (boolean default true), rejection_reason (text nullable), downloads_count (integer default 0), timestamps, softDeletes
  - Add indexes: user_id, category_id, slug, composite (is_approved, is_active), created_at
  - Create Category model in app/Models with fillable, relationships (hasMany products), and slug generation
  - Create Product model in app/Models with fillable, casts (price => 'decimal:2', is_approved => 'boolean', is_active => 'boolean'), relationships (belongsTo user, belongsTo category), slug generation, and soft deletes
  - _Requirements: 2.1, 6.1, 9.1_

- [x] 1.3 Create transaction and cart migrations and models



  - Create transactions migration with fields: user_id (foreign key), payment_gateway (string: stripe/paytm), payment_id (string unique), amount (decimal 10,2), commission (decimal 10,2), seller_amount (decimal 10,2), status (enum: pending/completed/failed/refunded), metadata (json nullable), timestamps
  - Add indexes to transactions: user_id, payment_id, status, created_at
  - Create transaction_items migration with fields: transaction_id (foreign key), product_id (foreign key), seller_id (foreign key to users), price (decimal 10,2), commission (decimal 10,2), seller_amount (decimal 10,2), timestamps
  - Add indexes to transaction_items: transaction_id, product_id, seller_id
  - Create carts migration with fields: user_id (foreign key nullable), session_id (string nullable), product_id (foreign key), quantity (integer default 1), timestamps
  - Add indexes to carts: user_id, session_id, composite (user_id, product_id), composite (session_id, product_id)
  - Create Transaction model in app/Models with fillable, casts (amount/commission/seller_amount => 'decimal:2', metadata => 'array'), relationships (belongsTo user, hasMany transactionItems)
  - Create TransactionItem model in app/Models with fillable, casts (price/commission/seller_amount => 'decimal:2'), relationships (belongsTo transaction, belongsTo product, belongsTo seller)
  - Create Cart model in app/Models with fillable, relationships (belongsTo user, belongsTo product)
  - _Requirements: 3.1, 4.1, 10.1_
-

- [x] 1.4 Create download and withdrawal migrations and models


  - Create downloads migration with fields: user_id (foreign key), product_id (foreign key), transaction_id (foreign key), download_url (text), expires_at (timestamp), downloaded_at (timestamp nullable), timestamps
  - Add indexes to downloads: user_id, product_id, expires_at
  - Create withdrawals migration with fields: user_id (foreign key), amount (decimal 10,2), method (enum: bank_transfer/paypal/other), account_details (text), status (enum: pending/approved/completed/rejected), admin_notes (text nullable), processed_at (timestamp nullable), timestamps
  - Add indexes to withdrawals: user_id, status
  - Create Download model in app/Models with fillable, casts (expires_at => 'datetime', downloaded_at => 'datetime'), relationships (belongsTo user, belongsTo product, belongsTo transaction)
  - Create Withdrawal model in app/Models with fillable, casts (amount => 'decimal:2', account_details => 'encrypted', processed_at => 'datetime'), relationships (belongsTo user)
  - _Requirements: 5.1, 7.4, 12.2_


- [x] 2. Implement authentication and authorization system






- [x] 2.1 Configure Laravel Fortify for marketplace authentication


  - Update FortifyServiceProvider to set default role to 'customer' on registration
  - Verify email verification is enabled in config/fortify.php (already enabled)
  - Verify password reset functionality is configured (already enabled)
  - Update User model to implement MustVerifyEmail contract if not already
  - _Requirements: 1.1, 1.2, 1.3, 13.4_
-


- [x] 2.2 Create role-based access control




  - Create app/Http/Middleware/RoleMiddleware.php to check user role and is_banned status
  - Register RoleMiddleware in bootstrap/app.php middleware aliases
  - Create app/Policies/UserPolicy.php with methods: viewAny, view, update, delete, ban, changeRole
  - Create app/Policies/ProductPolicy.php with methods: viewAny, view, create, update, delete, approve, reject
  - Create app/Policies/TransactionPolicy.php with methods: viewAny, view
  - Create app/Policies/WithdrawalPolicy.php with methods: viewAny, view, create, approve, reject
- [x] 3. Build product browsing and search functionality







  - Register policies in app/Providers/AppServiceProvider.php boot method using Gate::policy()
  - _Requirements: 1.4, 1.5, 8.5, 16.6_


- [ ] 3. Build product browsing and search functionality


- [x] 3.1 Create homepage and product listing





  - Update resources/views/welcome.blade.php with hero section, featured categories grid, and top-selling products section
  - Create app/Livewire/Product/ProductList.php component with pagination (load more button or infinite scroll)
  - Create resources/views/livewire/product/product-list.blade.php view
  - Create app/Livewire/Product/ProductCard.php component for reusable product display with thumbnail, title, price, category
  - Create resources/views/livewire/product/product-card.blade.php view with lazy loading for images (loading="lazy")
  - Update routes/web.php to ensure homepage route displays approved and active products
  - _Requirements: 2.1, 2.3_
-

- [x] 3.2 Implement real-time product search





  - Create app/Livewire/Product/ProductSearch.php component with public properties: keyword, category_id, min_price, max_price, product_type
  - Implement search method with query builder filtering by keyword (title/description), category, price range, product type
  - Use wire:model.live.debounce.300ms for real-time search input
  - Add eager loading for category and user relationships
  - Create resources/views/livewire/product/product-search.blade.php with filter form and results grid
  - Implement pagination or infinite scroll for search results
  - Add route for search page in routes/web.php
  - _Requirements: 2.2, 2.3_

- [x] 3.3 Create product detail page






  - Create app/Livewire/Product/ProductDetail.php component that accepts product slug as parameter
  - Load product with eager loading for category and user (seller) relationships
  - Create resources/views/livewire/product/product-detail.blade.php displaying: thumbnail, title, description, short_description, type, price, license, seller info, category
  - Add HTML5 audio/video preview player for audio/video product types (if preview URL available)
  - Add "Add to Cart" button that calls addToCart() Livewire action
  - Add route in routes/web.php: Route::get('/product/{slug}', ProductDetail::class)->name('product.show')
  - Implement 404 handling for non-existent or unapproved products
  - _Requirements: 2.4, 2.5_

- [ ] 4. Implement shopping cart system





- [x] 4.1 Create cart service and components



  - Create app/Services directory and app/Services/CartService.php class
  - Implement add($productId, $quantity = 1) method - use session for guests, database for authenticated users
  - Implement remove($productId) method
  - Implement update($productId, $quantity) method
  - Implement clear() method
  - Implement getItems() method returning Collection with product details
  - Implement getTotal() method calculating total price
  - Implement getCount() method returning total items
  - Implement mergeSessionCart() method to merge session cart into database on login
  - Register CartService as singleton in app/Providers/AppServiceProvider.php
  - _Requirements: 3.5_

- [x] 4.2 Build cart UI components



  - Create app/Livewire/Cart/CartIcon.php component with computed property for item count using CartService
  - Create resources/views/livewire/cart/cart-icon.blade.php with cart icon and badge
  - Add CartIcon component to main layout header
  - Create app/Livewire/Cart/CartPage.php component with methods: removeItem($productId), updateQuantity($productId, $quantity), clearCart()
  - Create resources/views/livewire/cart/cart-page.blade.php displaying cart items, total, and checkout button
  - Create app/Livewire/Cart/CartItem.php component for individual cart item display with remove and quantity update
  - Create resources/views/livewire/cart/cart-item.blade.php
  - Add cart route in routes/web.php: Route::get('/cart', CartPage::class)->name('cart.index')
  - Emit events to refresh CartIcon when cart changes
  - _Requirements: 3.1, 3.2, 3.3, 3.4_

- [x] 5. Implement payment processing system

- [x] 5.1 Set up payment gateway integrations
  - Run: composer require stripe/stripe-php
  - Run: composer require paytm/paytmchecksum
  - Create app/Contracts directory and app/Contracts/PaymentGatewayInterface.php with methods: createPaymentIntent(float $amount, array $metadata): array, confirmPayment(string $paymentId): bool, refund(string $paymentId, float $amount): bool
  - Create app/Services/Payment directory
  - Create app/Services/Payment/StripePaymentGateway.php implementing PaymentGatewayInterface using Stripe SDK
  - Create app/Services/Payment/PaytmPaymentGateway.php implementing PaymentGatewayInterface using Paytm SDK
  - Add to .env: STRIPE_KEY, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET, PAYTM_MERCHANT_ID, PAYTM_MERCHANT_KEY, PAYTM_WEBSITE, PAYTM_ENVIRONMENT
  - Create config/payment.php for payment gateway configuration
  - _Requirements: 4.2, 4.3, 16.5_

- [x] 5.2 Build checkout flow
  - Create app/Livewire/Checkout/CheckoutForm.php component with properties: payment_gateway (stripe/paytm)
  - Implement processCheckout() method that creates Transaction with status 'pending', calculates commission, and initiates payment
  - Create resources/views/livewire/checkout/checkout-form.blade.php with payment gateway selection, order summary, and payment button
  - Add validation rules for payment gateway selection
  - Integrate Stripe Elements or Paytm payment form in view
  - Handle payment success: redirect to success page with transaction ID
  - Handle payment failure: display error message and keep user on checkout page
  - Add checkout route in routes/web.php: Route::get('/checkout', CheckoutForm::class)->middleware('auth')->name('checkout')
  - _Requirements: 4.1, 4.4_

- [x] 5.3 Implement webhook handlers and post-purchase processing
  - Create app/Http/Controllers/WebhookController.php with methods: handleStripeWebhook(), handlePaytmWebhook()
  - Add webhook routes in routes/web.php: POST /webhook/stripe, POST /webhook/paytm (exclude from CSRF)
  - Implement webhook signature verification for both gateways
  - Update Transaction status to 'completed' on successful payment webhook
  - Create TransactionItem records for each product in the transaction with commission calculation
  - Create app/Events/PurchaseCompleted.php event
  - Create app/Listeners/ProcessPurchase.php listener that: generates download links, sends confirmation email, clears cart, updates seller balances
  - Register event and listener in app/Providers/AppServiceProvider.php
  - Implement idempotency check using payment_id to prevent duplicate processing
  - Calculate commission using PLATFORM_COMMISSION_PERCENT from .env (default 10%)
  - _Requirements: 4.5, 4.6, 10.5_




- [x] 6. Build digital asset delivery system



- [x] 6.1 Implement storage service


  - Create app/Services/StorageService.php class
  - Implement uploadFile(UploadedFile $file, string $path): string method using Storage facade
  - Implement deleteFile(string $path): bool method
  - Implement generateTemporaryUrl(string $path, int $hours = null): string method using Storage::temporaryUrl()
  - Implement getFileSize(string $path): int method
  - Use DOWNLOAD_LINK_EXPIRY_HOURS from .env (default 24) for URL expiration
  - Configure S3 disk in config/filesystems.php with visibility => 'private'
  - Add to .env: FILESYSTEM_DISK=s3, AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION, AWS_BUCKET, DOWNLOAD_LINK_EXPIRY_HOURS=24
  - Register StorageService as singleton in AppServiceProvider
  - _Requirements: 5.1, 5.2, 5.5, 16.4, 16.5_



- [x] 6.2 Create download management system


  - Create app/Http/Controllers/DownloadController.php with download($downloadId) method
  - Verify user owns the download (check Download record user_id matches auth user)
  - Check download link expiration (expires_at > now())
  - Generate temporary URL using StorageService
  - Update downloaded_at timestamp in Download record
  - Return redirect to temporary URL
  - Add download route in routes/web.php: Route::get('/download/{download}', [DownloadController::class, 'download'])->middleware(['auth', 'throttle:10,60'])->name('download')
  - Display error message for expired or unauthorized downloads
  - Create resources/views/errors/download-expired.blade.php
  - _Requirements: 5.3, 5.4, 5.6_

- [x] 7. Implement seller product management






- [x] 7.1 Create product upload functionality


  - Create app/Livewire/Seller/ProductUpload.php component with properties: title, description, short_description, category_id, product_type, price, license_type, thumbnail, file
  - Implement validation rules for all fields including file size limits
  - Use wire:model for form fields and Livewire file upload for thumbnail and file
  - Display real-time upload progress using wire:loading and wire:target
  - Implement save() method that uploads files using StorageService, creates Product with is_approved=false, user_id=auth()->id()
  - Generate slug from title using Str::slug()
  - Store file_size from uploaded file
  - Create resources/views/livewire/seller/product-upload.blade.php with form
  - Add route in routes/web.php: Route::get('/seller/products/create', ProductUpload::class)->middleware(['auth', 'role:seller,admin'])->name('seller.products.create')
  - _Requirements: 6.1, 6.2, 6.3, 6.7_

- [x] 7.2 Build product management interface





  - Create app/Livewire/Seller/SellerProducts.php component listing auth user's products with pagination
  - Add methods: toggleActive($productId), deleteProduct($productId) with soft delete
  - Create resources/views/livewire/seller/seller-products.blade.php with product table showing title, status, price, downloads, actions
  - Create app/Livewire/Seller/ProductEdit.php component for editing products
  - Implement update() method with authorization check (user owns product)
  - Regenerate slug if title changes using Str::slug()
  - Create resources/views/livewire/seller/product-edit.blade.php
  - Add routes: Route::get('/seller/products', SellerProducts::class), Route::get('/seller/products/{product}/edit', ProductEdit::class) with middleware(['auth', 'role:seller,admin'])
  - _Requirements: 6.4, 6.5, 6.6_

- [x] 8. Build seller dashboard and analytics





- [x] 8.1 Create seller dashboard



  - Create app/Livewire/Seller/SellerDashboard.php component
  - Calculate total revenue from TransactionItem where seller_id = auth()->id(), sum(seller_amount)
  - Calculate total downloads from Download where product.user_id = auth()->id()
  - Query top-performing products by revenue or downloads
  - Display pending balance (auth()->user()->balance)
  - Show recent completed withdrawals
  - Create resources/views/livewire/seller/seller-dashboard.blade.php with stats cards and charts
  - Add route: Route::get('/seller/dashboard', SellerDashboard::class)->middleware(['auth', 'role:seller,admin'])->name('seller.dashboard')
  - _Requirements: 7.1, 7.2, 7.3_


- [x] 8.2 Implement seller analytics



  - Create app/Livewire/Seller/SellerAnalytics.php component with properties: period (daily/weekly/monthly)
  - Query TransactionItem grouped by date for revenue trends
  - Calculate sales metrics: total sales, average order value, conversion rate
  - Prepare data for charts in format suitable for Chart.js or Alpine.js charts
  - Create resources/views/livewire/seller/seller-analytics.blade.php with revenue trend charts and metrics
  - Add Chart.js via CDN or npm for data visualization
  - Add route: Route::get('/seller/analytics', SellerAnalytics::class)->middleware(['auth', 'role:seller,admin'])->name('seller.analytics')
  - _Requirements: 7.1, 7.2, 7.3_



- [x] 8.3 Build withdrawal request system


  - Create app/Livewire/Seller/WithdrawalRequest.php component with properties: amount, method, account_details
  - Implement validation: amount <= auth()->user()->balance, amount > 0, method required, account_details required
  - Implement submit() method that creates Withdrawal with status='pending', encrypted account_details

  - Create resources/views/livewire/seller/withdrawal-request.blade.php with form
  - Create app/Livewire/Seller/WithdrawalHistory.php component to display seller's withdrawal requests
  - Create resources/views/livewire/seller/withdrawal-history.blade.php
  - Add routes: Route::get('/seller/withdrawals/create', WithdrawalRequest::class), Route::get('/seller/withdrawals', WithdrawalHistory::class) with middleware(['auth', 'role:seller,admin'])
  - _Requirements: 7.4, 7.5_

- [x] 9. Implement admin user management






- [x] 9.1 Create user management interface


  - Create app/Livewire/Admin/UserManagement.php component with properties: search, role_filter
  - Query users with filtering by role and search (name/email)
  - Implement pagination
  - Create resources/views/livewire/admin/user-management.blade.php with user table showing name, email, role, status, actions
  - Add route: Route::get('/admin/users', UserManagement::class)->middleware([
'auth', 'role:admin'])->name('admin.users')
  - _Requirements: 8.1_


- [x] 9.2 Build seller role approval workflow


  - Create seller_role_requests migration with fields: user_id (foreign key), status (enum: pending/approved/rejected), admin_notes (text nullable), processed_at (timestamp nullable), timestamps
  - Create app/Models/SellerRoleRequest.php model with relationships and casts
  - Create app/Livewire/Profile/RequestSellerRole.php component for customers to request seller role
  - Create resources/views/livewire/profile/request-seller-role.blade.php

  - Add route for seller role request in profile se
ttings

  - Create app/Livewire/Admin/SellerRoleRequests.php component to list pending requests
  - Implement approve($requestId) and reject($reque
stId, $reason) methods
  - Update user role to 'seller' on approval
  - Send notification to user on approval/rejection

  - Create resources/views/livewire/admin/seller-role-requests.blade.php

  - Add route: Route::get('/admin/seller-requests', SellerRoleRequests::class)->middleware(['auth', 'role:admin'])
  - _Requirements: 8.2_

- [x] 9.3 Implement user account management



  - Add toggleBan($userId) method to UserManagement component
  - Add changeRole($userId, $newRole) method to UserManagement component
  - Implement authorization checks using UserPolicy

  - Update RoleMiddleware to check is_banned and logout banned users
  - Add action buttons in user management table for ban/unban and role change
  - Add confirmation modals for destructive actions
  - Send notification to user when banned or role changed
  --_Requirements: 8.3, 8.4, 8.5_



- [x] 10. Build admin product moderation







y
- [x] 10.1 Create product moderation interface


  --Create app/Livewire/Admin/ProductModeratio
n.php component with properties: search, status_filter (pending/approved/rejected)
  - Query products with filtering and eager loading for user and category
  - Implement pagination
  - Create resources/views/livewire/admin/product-moderation.blade.php with product table showing thumbnail, title, seller, category, status, actions

  - Add route: Route::get('/admin/products', ProductModeration::class)->middleware(['auth', 'role:admin'])->name('admin.products')
  - _Requirements: 9.1_


- [x] 10.2 Implement product approval workflow


  - Add approve($productId) method to ProductModeration component
  - Add reject($productId, $reason) method with validation for required reason
  - Update product is_approved=true on approval, store rejection_reason on rejection
  - Create app/Notifications/ProductApproved.php notification
  - Create app/Notifications/ProductRejected.php notification
  - Send notifications to product owner (seller) on approval/rejection
  - Add approval/rejection action buttons with modal for rejection reason
  - _Requirements: 9.2, 9.3, 9.4_


- [x] 10.3 Add admin product management


  - Create app/Livewire/Admin/ProductEdit.php component for admins to edit any product
  - Implement update() method with authorization check (admin only)
  - Add deleteProduct($productId) method with soft delete
  - Create resources/views/livewire/admin/product-edit.blade.php
  - Add route: Route::get('/admin/products/{product}/edit', ProductEdit::class)->middleware(['auth', 'role:admin'])
  - Optionally create product_approval_history table to track approval/rejection history
  - _Requirements: 9.5, 9.6_

- [x] 11. Implement admin financial management






- [x] 11.1 Create transaction management interface



  - Create app/Livewire/Admin/TransactionList.php component with properties: search, status_filter, date_from, date_to
  - Query transactions with eager loading for user and transactionItems.product
  - Display transaction details: ID, customer, products, amount, commission, seller_amount, payment_gateway, status, date
  - Calculate total platform revenue (sum of commission) and total seller payouts (sum of seller_amount)
  - Implement filtering by status, date range, and search by customer name/email
  - Create resources/views/livewire/admin/transaction-list.blade.php with transaction table
  - Add route: Route::get('/admin/transactions', TransactionList::class)->middleware(['auth', 'role:admin'])->name('admin.transactions')
  - _Requirements: 10.1, 10.5, 10.6_

- [x] 11.2 Build withdrawal management system



  - Create app/Livewire/Admin/WithdrawalManagement.php component with properties: status_filter
  - Query withdrawals with eager loading for user, filter by status
  - Implement approve($withdrawalId) method: update status='approved', processed_at=now(), deduct amount from user balance
  - Implement reject($withdrawalId, $reason) method: update status='rejected', store admin_notes
  - Create app/Notifications/WithdrawalApproved.php notification
  - Create app/Notifications/WithdrawalRejected.php notification
  - Send notifications to seller on approval/rejection
  - Create resources/views/livewire/admin/withdrawal-management.blade.php with withdrawal table
  - Add route: Route::get('/admin/withdrawals', WithdrawalManagement::class)->middleware(['auth', 'role:admin'])->name('admin.withdrawals')
  - _Requirements: 10.2, 10.3, 10.4_

- [x] 12. Create admin analytics dashboard






  - Create app/Livewire/Admin/AdminDashboard.php component with properties: period (daily/weekly/monthly/all)
  - Calculate total platform revenue: sum(transactions.commission) filtered by period
  - Calculate total transactions count filtered by period
  - Query top-selling products: group by product_id, order by sum(transaction_items.price) desc, limit 10
  - Query top-performing sellers: group by seller_id, order by sum(transaction_items.seller_amount) desc, limit 10
  - Calculate total downloads count
  - Count active users by role
  - Prepare revenue trend data grouped by date for charts
  - Create resources/views/livewire/admin/admin-dashboard.blade.php with stats cards and charts
  - Add Chart.js for data visualization
  - Add route: Route::get('/admin/dashboard', AdminDashboard::class)->middleware(['auth', 'role:admin'])->name('admin.dashboard')
  - _Requirements: 11.1, 11.2, 11.3, 11.4, 11.5_


- [x] 13. Build customer dashboard





- [x] 13.1 Create purchase history interface



  - Create app/Livewire/Customer/PurchaseHistory.php component
  - Query transactions for auth user with eager loading for transactionItems.product
  - Display purchased products with thumbnail, title, purchase date, price
  - Implement generateDownloadLink($productId, $transactionId) method that creates new Download record with expires_at
  - Create resources/views/livewire/customer/purchase-history.blade.php
  - Add route: Route::get('/customer/purchases', PurchaseHistory::class)->middleware('auth')->name('customer.purchases')
  - _Requirements: 12.1, 12.2, 12.3_


- [x] 13.2 Implement billing history



  - Run: composer require barryvdh/laravel-dompdf
  - Create app/Livewire/Customer/BillingHistory.php component
  - Query transactions for auth user with pagination
  - Display transaction ID, date, amount, payment method, status
  - Create app/Http/Controllers/InvoiceController.php with download($transactionId) method
  - Generate PDF invoice using dompdf with transaction details
  - Create resources/views/invoices/template.blade.php for PDF layout
  - Create resources/views/livewire/customer/billing-history.blade.php
  - Add routes: Route::get('/customer/billing', BillingHistory::class), Route::get('/invoice/{transaction}', [InvoiceController::class, 'download'])->middleware('auth')
  - _Requirements: 12.4, 12.5_


- [x] 13.3 Build profile management


  - Verify existing profile settings components in resources/views/livewire/settings/
  - Ensure name update functionality exists and works
  - Ensure email update with verification exists and works
  - Ensure password change with current password verification exists and works
  - If missing, create necessary Volt components or Livewire components
  - Profile routes already exist at /settings/profile, /settings/password
  - _Requirements: 13.1, 13.2, 13.3, 13.5_

- [x] 14. Implement notification system





- [x] 14.1 Set up notification infrastructure




  - Run: php artisan make:notification PurchaseConfirmation
  - Run: php artisan make:notification ProductApproved
  - Run: php artisan make:notification ProductRejected
  - Run: php artisan make:notification WithdrawalApproved
  - Run: php artisan make:notification Withdra
walRejected

  - Run: php artisan make:notification NewSale
  - Run: php artisan make:notification SellerRoleRequested

  - Implement toMail() and toDatabase() methods in each notification class
  - Create app/Services/NotificationService.php for centralized notification sending
  - Verify notifications table exists (run migration if needed: php artisan notifications:table && php artisan migrate)

  - _Requirements: 14.1, 14.2, 14.3, 14.4, 14.5_

- [x] 14.2 Build notification UI components




  - Create app/Livewire/Notifications/NotificationBell.php component with computed property for unread count
  - Create resources/views/livewire/notifications/notification-bell.blade.php with bell icon and badge
  - Add NotificationBell to main layout header
  - Create app/Livewire/Notifications/NotificationDropdown.php component showing recent 5 notifications
  - Create resources/views/livewire/notifications/notification-dropdown.blade.php

  - Create app/Livewire/Notifications/NotificationCenter.php component for full notification history with pagination
  - Implement markAsRead($notificationId) and markAllAsRead() methods
  - Create resources/views/livewire/notifications/notification-center.blade.php
  - Add Livewire polling (wire:poll.30s) for real-time updates
  - Add route: Route::get('/notifications', NotificationCenter::class)->middleware('auth')->name('notifications')
  - _Requirements: 14.5_

- [x] 14.3 Implement notification triggers
  - Update ProcessPurchase listener to send PurchaseConfirmation notification to customer with download links
  - Update ProcessPurchase listener to send NewSale notification to each seller
  - Update ProductModeration approve() method to send ProductApproved notification to seller
  - Update ProductModeration reject() method to send ProductRejected notification to seller with reason
  - Update WithdrawalManagement approve() method to send WithdrawalApproved notification to seller
  - Update WithdrawalManagement reject() method to send WithdrawalRejected notification to seller with reason
  - Update RequestSellerRole component to send SellerRoleRequested notification to all admins
  - _Requirements: 14.1, 14.2, 14.3, 14.4_

- [ ] 15. Build REST API

- [x] 15.1 Create public API endpoints
  - Create app/Http/Controllers/Api/ProductController.php with index() and show($slug) methods
  - Create app/Http/Controllers/Api/CategoryController.php with index() method
  - Create app/Http/Resources/ProductResource.php for consistent product JSON response
  - Create app/Http/Resources/CategoryResource.php for consistent category JSON response
  - Implement GET /api/v1/products with query parameters: category, type, min_price, max_price, search
  - Implement GET /api/v1/products/{slug} returning single product
  - Implement GET /api/v1/categories returning all categories
  - Add routes in routes/api.php
  - Return paginated responses with meta data
  - _Requirements: 15.1, 15.2_


- [x] 15.2 Create authenticated API endpoints





  - Run: php artisan install:api (installs Sanctum)
  - Add HasApiTokens trait to User model
  - Create app/Http/Controllers/Api/UserController.php with purchases() and generateDownload($downloadId) methods
  - Implement GET /api/v1/user/purchases returning user's purchased products with transaction details
  - Implement POST /api/v1/user/downloads/{download} generating new download link for purchased products
  - Add routes in routes/api.php with auth:sanctum middleware
  - Create app/Livewire/Settings/ApiTokens.php component for token management
  - Create resources/views/livewire/settings/api-tokens.blade.php with create/revoke token functionality
  - Add route for API token management in settings
  - _Requirements: 15.4_

- [x] 15.3 Implement API rate limiting







  - Define rate limiters in bootstrap/app.php using RateLimiter facade
  - Create 'api-public' limiter: 60 requests per minute per IP
  - Create 'api-authenticated' limiter: 120 requests per minute per user
  - Create 'api-downloads' limiter: 10 requests per hour per user
  - Apply throttle:api-public middleware to public API routes
  - Apply throttle:api-authenticated middleware to authenticated API routes
  - Apply throttle:api-downloads middleware to download generation endpoint
  - Return appropriate 429 Too Many Requests responses with Retry-After header
  - _Requirements: 15.3_

- [x] 15.4 Create API documentation





  - Install Scribe: composer require --dev knuckleswtf/scribe
  - Run: php artisan scribe:generate
  - Document all available API endpoints with @group and @response annotations
  - Include request/response examples for each endpoint
  - Add authentication instructions for Sanctum token usage
  - Document rate limiting rules
  - Document error response formats
  - Publish documentation to /docs/api route
  - _Requirements: 15.5_

- [ ] 16. Implement responsive design and accessibility


- [x] 16.1 Build responsive layouts





  - Review and update main layout to use Tailwind responsive utilities (sm:, md:, lg:, xl:, 2xl:)
  - Implement responsive navigation in layout header using Alpine.js for mobile menu toggle
  - Use Tailwind grid classes for product grids: grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4
  - Ensure all forms and components are responsive
  - Test layouts on various screen sizes (320px, 768px, 1024px, 1920px)
  - Ensure touch targets are minimum 44x44px (use p-3 or larger for buttons)
  - _Requirements: 17.1, 17.2_

- [x] 16.2 Implement dark mode






  - Verify Tailwind dark mode is configured with class strategy in tailwind.config.js
  - Check if dark mode toggle exists in settings/appearance (already exists in starter kit)
  - Ensure all new components use dark: prefix for dark mode styles
  - Test dark mode across all pages and components
  - Verify dark mode preference persists in local storage
  - _Requirements: 17.3_


- [x] 16.3 Add accessibility features





  - Review all layouts and components to use semantic HTML: <nav>, <main>, <article>, <aside>, <footer>
  - Ensure proper heading hierarchy: single h1 per page, logical h2/h3 structure
  - Add aria-label to icon-only buttons and interactive elements
  - Add aria-live regions for dynamic content updates (notifications, cart updates)
  - Implement skip-to-main-content link at top of layout
  - Ensure all focusable elements have visible focus indicators (ring classes)
  - Add alt text to all product images and thumbnails
  - Test color contrast using browser dev tools or axe DevTools
  - Ensure all forms have properly associated labels
  - Test keyboard navigation (Tab, Enter, Escape) on all interactive components
  - _Requirements: 17.4, 17.5, 17.6_

- [x] 17. Implement error handling and user feedback






- [x] 17.1 Create custom error pages



  - Create resources/views/errors/404.blade.php with user-friendly "Page Not Found" message
  - Create resources/views/errors/500.blade.php with "Server Error" message
  - Create resources/views/errors/419.blade.php with "Session Expired" message and refresh button
  - Create resources/views/errors/403.blade.php with "Forbidden" message

  - Create resources/views/errors/503.blade.php with "Maintenance Mode" message
  - Use consistent layout and styling with main application

  - _Requirements: 18.1, 18.2, 18.3_



- [x] 17.2 Implement validation and feedback






  - Create Form Request classes for complex forms: ProductUploadRequest, CheckoutRequest, WithdrawalRequest
  - Ensure all Livewire components display validation errors using @error directive

  - Implement real-time validation in forms using wire:model.blur or wire:model.live
  - Use Livewire flash messages for success feedback: session()->flash('success', 'Message')
  - Create toast notification component using Alpine.js or Flux UI components

  - Add wire:loading indicators to all async actions (buttons, forms)
  - Implement confirmation modals for destructive actions (delete, ban) using Alpine.js or Flux modals


  - _Requirements: 16.3, 18.4, 18.5_

- [x] 17.3 Set up error logging






  - Verify Laravel logging is configured in config/logging.php (already configured)
  - Add custom logging for security events in RoleMiddleware 

(unauthorized access attempts)
  - Add logging in payment webhook handlers for failed payments
  - Add logging in file upload/download operations for errors
  - Consider adding Sentry or Bugsnag for production error tracking (optional)

  - _Requirements: 16.7, 18.6_



- [x] 18. Implement security measures





- [x] 18.1 Configure authentication security


  - Verify BCRYPT_ROUNDS=12 in
 .env (already configured)
  - Verify session configuration in config/session.php: http_only=true, secure=true (for production)
  - _RyquirSmeRts: 16.1,t16.2_
nabled (already enabled by default)
  - Verify Fortify rate limiting is configured in config/fortify.php (already configured)
  - Update .env for production: SESSION_SECURE_COOKIE=true, SESSION_SAME_SITE=strict
  - _Requirements: 16.1, 16.2_

- [x] 18.2 Implement file security



  - Ensure S3 disk uses 'private' visibility in StorageService uploads
  - Verify StorageService uses temporaryUrl() for all download links
  - Add file validation in ProductUpload component: mimes, max file size, file type
  - Add MIME type validation using Storage::mimeType()
  - Ensure no direct file URLs are exposed in views or API responses

  - _Requirements: 16.4, 16.5_


- [x] 18.3 Add payment security


  - Implement webhook signature verification in WebhookController for Stripe and Paytm
  - Add idempotency check using payment_id before processing webhooks
  - Verify all payment credentials are in .env and not hardcoded
  - Ensure payment forms use gateway-provided tokens, never store card details
  - Add logging for failed webhook verifications
  - _Requirements: 16.5_

- [x] 18.4 Implement data protection



  - Verify Withdrawal model uses 'encrypted' cast for account_details
  - Verify all database queries use Eloquent ORM (no raw queries without bindings)
  - Verify Blade escaping is enabled ({{ }} syntax, not {!! !!} unless necessary)
  - Ensure all user inputs are validated using Form Requests or Livewire validation
  - Add APP_FORCE_HTTPS=true to production .env
  - Add TrustProxies middleware configuration for production
  - _Requirements: 16.3, 16.5_

- [x] 19. Set up performance optimization





- [x] 19.1 Implement caching strategy


  - Add Cache::remember() for categories query in homepage and search (cache for 1 hour)
  - Add Cache::remember() for featured products query (cache for 30 minutes)
  - Add Cache::remember() for top-selling products (cache for 1 hour)
  - Create deployment script that runs: php artisan config:cache, php artisan route:cache, php artisan view:cache
  - Add cache clearing to deployment script: php artisan cache:clear before caching
  - Document caching commands in README or deployment docs
  - _Requirements: Performance optimization from design_


- [x] 19.2 Optimize database queries


  - Review all Livewire components and controllers for N+1 queries
  - Add eager loading using with() for relationships: Product::with('category', 'user'), Transaction::with('user', 'transactionItems.product')
  - Use select() to limit columns where full model not needed
  - Consider cursor pagination for large lists: Product::query()->cursorPaginate(20)
  - Add database query logging in development to identify slow queries
  - _Requirements: Performance optimization from design_


- [x] 19.3 Set up queue system


  - Verify QUEUE_CONNECTION=database in .env for development
  - Update all notification classes to implement ShouldQueue interface
  - Create app/Jobs/ProcessWebhook.php job for async webhook processing
  - Create app/Jobs/GenerateInvoice.php job for PDF generation
  - Create app/Jobs/ProcessFileUpload.php job for thumbnail generation (optional)
  - Update ProcessPurchase listener to implement ShouldQueue
  - For production: set QUEUE_CONNECTION=redis and configure Redis connection
  - Document queue worker command: php artisan queue:work
  - _Requirements: Performance optimization from design_

- [x] 20. Configure deployment and environment
  - Create .env.example with all required variables documented
  - Add to .env.example: DOWNLOAD_LINK_EXPIRY_HOURS=24, PLATFORM_COMMISSION_PERCENT=10
  - Document required environment variables in README.md
  - Document deployment steps in README.md including:
    - Run migrations: php artisan migrate --force
    - Cache config: php artisan config:cache
    - Cache routes: php artisan route:cache
    - Cache views: php artisan view:cache
    - Optimize autoloader: composer install --optimize-autoloader --no-dev
    - Build assets: npm run build
    - Start queue workers: php artisan queue:work --daemon
    - Set up cron: * * * * * php artisan schedule:run
  - Document S3/Spaces configuration requirements
  - Document payment gateway setup instructions
  - Document email service configuration
  - Document Redis setup for production
  - Document queue system setup and management
  - _Requirements: Deployment from design_

## Summary

The digital marketplace implementation is nearly complete. The core functionality has been implemented including:

**Completed:**
- ✅ Database schema and models
- ✅ Authentication and authorization with role-based access control
- ✅ Product browsing, search, and detail pages
- ✅ Shopping cart system
- ✅ Payment processing (Stripe and Paytm)
- ✅ Digital asset delivery with secure downloads
- ✅ Seller product management and dashboard
- ✅ Admin user, product, and financial management
- ✅ Customer purchase history and billing
- ✅ Notification system (email and in-app)
- ✅ Public API endpoints for products and categories
- ✅ Responsive design and accessibility features
- ✅ Error handling and custom error pages
- ✅ Security measures and data protection
- ✅ Performance optimization (caching, query optimization, queue system)
- ✅ Deployment documentation

**Remaining Tasks:**
- ⏳ Authenticated API endpoints with Sanctum (Task 15.2)
- ⏳ API rate limiting configuration (Task 15.3)
- ⏳ API documentation with Scribe (Task 15.4)

These remaining tasks focus on completing the REST API functionality for third-party integrations. The core marketplace features are fully functional and ready for use.
