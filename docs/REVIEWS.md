# Product Reviews System

This document describes the product review and rating system implemented in the digital marketplace.

## Overview

The review system allows customers to rate and review products they have purchased. Reviews help build trust and provide valuable feedback to sellers.

## Database Schema

### Reviews Table

```sql
- id: Primary key
- product_id: Foreign key to products table
- user_id: Foreign key to users table
- transaction_id: Foreign key to transactions table (nullable)
- rating: Integer (1-5 stars)
- title: String (optional, max 255 characters)
- comment: Text (required, min 10 characters, max 1000 characters)
- is_verified_purchase: Boolean (true if user purchased the product)
- is_approved: Boolean (default true)
- approved_at: Timestamp (nullable)
- created_at: Timestamp
- updated_at: Timestamp

Unique constraint: (product_id, user_id) - One review per user per product
```

## Features

### For Customers

1. **Write Reviews**
   - Rate products from 1 to 5 stars
   - Add optional review title
   - Write detailed review comment (10-1000 characters)
   - Verified purchase badge for actual buyers
   - One review per product per user

2. **View Reviews**
   - See all approved reviews for a product
   - View average rating and rating distribution
   - Filter reviews by star rating
   - Sort by: Most Recent, Highest Rating, Lowest Rating
   - Pagination for large review lists

### For Sellers

1. **Notifications**
   - Email notification when receiving a new review
   - Database notification for in-app alerts
   - Review details included in notification

2. **Review Insights**
   - Average rating displayed on product pages
   - Total review count
   - Rating distribution (5-star breakdown)

### For Administrators

1. **Review Management Dashboard**
   - View all reviews across the platform
   - Statistics: Total, Approved, Pending, Average Rating
   - Search reviews by content, product, or user
   - Filter by rating (1-5 stars)
   - Filter by status (Approved/Pending)
   - Sort options: Recent, Oldest, Rating High/Low

2. **Moderation Actions**
   - Approve pending reviews
   - Reject approved reviews
   - Delete reviews permanently
   - Confirmation prompts for all actions

## Components

### Livewire Components

1. **ReviewForm** (`App\Livewire\Product\ReviewForm`)
   - Handles review submission
   - Validates user authentication
   - Checks for existing reviews
   - Detects verified purchases
   - Sends notifications to sellers

2. **ProductReviews** (`App\Livewire\Product\ProductReviews`)
   - Displays all reviews for a product
   - Shows rating summary and distribution
   - Handles filtering and sorting
   - Pagination support
   - Real-time updates via Livewire events

3. **ReviewManagement** (`App\Livewire\Admin\ReviewManagement`)
   - Admin dashboard for review moderation
   - Search and filter functionality
   - Bulk actions support
   - Statistics overview

## Models

### Review Model

**Relationships:**
- `belongsTo(Product::class)`
- `belongsTo(User::class)`
- `belongsTo(Transaction::class)`

**Scopes:**
- `approved()` - Only approved reviews
- `verifiedPurchase()` - Only verified purchase reviews
- `byRating($rating)` - Filter by specific rating

### Product Model Extensions

**New Methods:**
- `reviews()` - HasMany relationship
- `approvedReviews()` - Only approved reviews
- `averageRating()` - Calculate average rating
- `reviewsCount()` - Total approved reviews
- `ratingDistribution()` - Array of ratings 1-5 with counts

### User Model Extensions

**New Methods:**
- `reviews()` - HasMany relationship
- `hasPurchased(Product $product)` - Check if user purchased product
- `hasReviewed(Product $product)` - Check if user reviewed product

## Notifications

### NewReviewReceived

**Channels:** Database, Mail

**Triggered When:** A customer submits a review

**Recipients:** Product seller

**Data Included:**
- Review ID
- Product title and slug
- Rating (1-5 stars)
- Reviewer name
- Review title and comment

## Routes

```php
// Public - Product detail page includes reviews
GET /product/{slug}

// Admin - Review management
GET /admin/reviews
```

## Validation Rules

### Review Submission

- **Rating:** Required, integer, 1-5
- **Title:** Optional, string, max 255 characters
- **Comment:** Required, string, min 10 characters, max 1000 characters

## Business Rules

1. **One Review Per Product**
   - Users can only submit one review per product
   - Attempting to submit multiple reviews shows error message

2. **Verified Purchase Badge**
   - Automatically applied if user has completed transaction for the product
   - Helps build trust and credibility

3. **Auto-Approval**
   - Reviews are automatically approved by default
   - Admins can change this behavior if needed

4. **Review Editing**
   - Currently not supported
   - Users must contact support to modify reviews

5. **Review Deletion**
   - Only admins can delete reviews
   - Deletion is permanent (no soft deletes)

## UI/UX Features

1. **Star Rating Input**
   - Interactive star selection
   - Hover effects for better UX
   - Visual feedback on selection

2. **Rating Distribution Chart**
   - Visual bar chart showing rating breakdown
   - Clickable to filter reviews by rating
   - Percentage-based width calculation

3. **Verified Purchase Badge**
   - Green badge with checkmark icon
   - Displayed next to reviewer name
   - Builds trust and credibility

4. **Responsive Design**
   - Mobile-friendly layout
   - Touch-optimized star rating
   - Collapsible review sections

5. **Dark Mode Support**
   - All components support dark mode
   - Proper contrast ratios
   - Consistent styling

## Performance Considerations

1. **Indexes**
   - product_id, user_id, rating, is_approved
   - Optimizes filtering and sorting queries

2. **Eager Loading**
   - Reviews loaded with user relationship
   - Prevents N+1 query problems

3. **Pagination**
   - 10 reviews per page on product pages
   - 20 reviews per page in admin dashboard

4. **Caching Opportunities**
   - Average rating can be cached
   - Rating distribution can be cached
   - Consider implementing cache invalidation on new reviews

## Future Enhancements

1. **Review Editing**
   - Allow users to edit their reviews within a time window
   - Track edit history

2. **Helpful Votes**
   - Allow users to mark reviews as helpful
   - Sort by most helpful

3. **Review Responses**
   - Allow sellers to respond to reviews
   - Display responses below reviews

4. **Review Images**
   - Allow users to upload images with reviews
   - Display image gallery

5. **Review Moderation Queue**
   - Require admin approval before publishing
   - Automated spam detection

6. **Review Analytics**
   - Sentiment analysis
   - Trending keywords
   - Review velocity tracking

## Testing

### Manual Testing Checklist

- [ ] Submit review as authenticated user
- [ ] Verify verified purchase badge appears
- [ ] Try submitting duplicate review (should fail)
- [ ] Filter reviews by rating
- [ ] Sort reviews by different criteria
- [ ] Admin approve/reject reviews
- [ ] Admin delete reviews
- [ ] Verify seller receives notification
- [ ] Test pagination
- [ ] Test responsive design
- [ ] Test dark mode

### Test Data

Use the following command to create test reviews:

```bash
php artisan tinker

# Create a test review
$product = Product::first();
$user = User::where('role', 'customer')->first();

Review::create([
    'product_id' => $product->id,
    'user_id' => $user->id,
    'rating' => 5,
    'title' => 'Excellent product!',
    'comment' => 'This is a great product. Highly recommended for anyone looking for quality digital assets.',
    'is_verified_purchase' => true,
    'is_approved' => true,
    'approved_at' => now(),
]);
```

## Troubleshooting

### Reviews Not Showing

1. Check if reviews are approved (`is_approved = true`)
2. Verify product has approved reviews
3. Check pagination settings

### Notification Not Sent

1. Verify queue is running (`php artisan queue:work`)
2. Check mail configuration
3. Verify seller has valid email address

### Duplicate Review Error

1. Check unique constraint on (product_id, user_id)
2. Verify user hasn't already reviewed the product
3. Clear any orphaned review records

## Security Considerations

1. **Authorization**
   - Only authenticated users can submit reviews
   - Users can only review each product once
   - Only admins can moderate reviews

2. **Input Validation**
   - All inputs are validated and sanitized
   - XSS protection via Blade escaping
   - SQL injection protection via Eloquent

3. **Rate Limiting**
   - Consider implementing rate limiting for review submissions
   - Prevent spam and abuse

## Maintenance

### Database Cleanup

```bash
# Remove reviews for deleted products (if not using cascade)
DELETE FROM reviews WHERE product_id NOT IN (SELECT id FROM products);

# Remove reviews from deleted users (if not using cascade)
DELETE FROM reviews WHERE user_id NOT IN (SELECT id FROM users);
```

### Performance Monitoring

Monitor these queries for performance:
- Average rating calculation
- Rating distribution queries
- Review list with filters and sorting

Consider adding database indexes or caching if queries become slow.
