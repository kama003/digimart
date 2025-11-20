# Blog System Documentation

## Overview

The blog system allows sellers to create blog posts with product links, requires admin approval, and enables customer engagement through likes and comments.

## Features

### For Sellers
- Create blog posts with rich content
- Link products to blog posts
- Upload featured images
- Manage their blog posts (edit, delete, view stats)
- View approval status and rejection reasons

### For Customers
- Browse and read approved blog posts
- Like blog posts
- Comment on blog posts
- Reply to comments
- View author profiles and linked products

### For Admins
- Review and approve/reject blog posts
- Moderate comments
- View blog analytics
- Manage all blog content

## Database Schema

### blog_posts
- id
- user_id (author)
- title
- slug (auto-generated, unique)
- excerpt (short description)
- content (full blog content)
- featured_image (optional)
- product_id (optional linked product)
- is_approved (default: false)
- is_published (default: true)
- published_at
- rejection_reason
- views_count
- likes_count
- comments_count
- timestamps
- soft_deletes

### blog_comments
- id
- blog_post_id
- user_id
- parent_id (for nested replies)
- content
- is_approved (default: true)
- timestamps
- soft_deletes

### blog_likes
- id
- blog_post_id
- user_id
- timestamps
- unique(blog_post_id, user_id)

## Routes

### Public Routes
- GET /blog - List all approved blog posts
- GET /blog/{slug} - View single blog post

### Seller Routes (auth + seller/admin role)
- GET /seller/blog - Manage blog posts
- GET /seller/blog/create - Create new blog post
- GET /seller/blog/{id}/edit - Edit blog post

### Admin Routes (auth + admin role)
- GET /admin/blog - Blog moderation dashboard

## Components

### Public Components
1. **BlogList** - Display grid of blog posts with filters
2. **BlogDetail** - Single blog post view with comments and likes
3. **BlogCard** - Reusable blog post card component

### Seller Components
1. **BlogManagement** - List seller's blog posts with stats
2. **BlogCreate** - Create/edit blog post form
3. **BlogEdit** - Edit existing blog post

### Admin Components
1. **BlogModeration** - Review pending blog posts
2. **BlogAnalytics** - Blog system statistics

## Workflow

### Blog Post Creation
1. Seller creates blog post with optional product link
2. Post status: `is_approved = false`
3. Admin receives notification
4. Admin reviews and approves/rejects
5. If approved: Post becomes visible to public
6. If rejected: Seller sees rejection reason

### Comment System
1. Any authenticated user can comment
2. Comments are auto-approved by default
3. Nested replies supported (one level)
4. Admin can moderate/delete comments

### Like System
1. Authenticated users can like posts
2. One like per user per post
3. Real-time like count updates
4. Toggle like/unlike

## Security

- Only sellers and admins can create blog posts
- Only post authors can edit their posts
- Admins can edit/delete any post
- XSS protection on content (sanitized HTML)
- Rate limiting on comments and likes
- Soft deletes for audit trail

## SEO Features

- Unique slugs for URLs
- Meta descriptions (excerpt)
- Open Graph tags
- Sitemap integration
- Canonical URLs

## Performance Optimizations

- Eager loading relationships
- Counter caches (likes_count, comments_count, views_count)
- Pagination on lists
- Image optimization for featured images
- Query optimization with indexes

## Future Enhancements

1. **Rich Text Editor** - WYSIWYG editor for content
2. **Tags/Categories** - Organize blog posts
3. **Search** - Full-text search on blog posts
4. **RSS Feed** - Subscribe to blog updates
5. **Social Sharing** - Share buttons
6. **Reading Time** - Estimated reading time
7. **Related Posts** - Show similar blog posts
8. **Bookmarks** - Save posts for later
9. **Email Notifications** - Notify followers of new posts
10. **Analytics** - Detailed view/engagement metrics

## API Endpoints

```
GET /api/v1/blog - List blog posts
GET /api/v1/blog/{slug} - Get single post
POST /api/v1/blog/{id}/like - Toggle like
POST /api/v1/blog/{id}/comments - Add comment
```

## Usage Examples

### Creating a Blog Post

```php
$post = BlogPost::create([
    'user_id' => auth()->id(),
    'title' => 'How to Use Audio in Your Projects',
    'content' => '...',
    'product_id' => 123, // Optional
    'is_approved' => false,
]);
```

### Liking a Post

```php
$post->likes()->create([
    'user_id' => auth()->id(),
]);
$post->increment('likes_count');
```

### Adding a Comment

```php
$comment = $post->comments()->create([
    'user_id' => auth()->id(),
    'content' => 'Great post!',
    'is_approved' => true,
]);
$post->increment('comments_count');
```

## Notifications

- **NewBlogPostSubmitted** - Notify admins when seller creates post
- **BlogPostApproved** - Notify seller when post is approved
- **BlogPostRejected** - Notify seller with rejection reason
- **NewCommentReceived** - Notify post author of new comments

## Permissions

| Action | Customer | Seller | Admin |
|--------|----------|--------|-------|
| View approved posts | ✓ | ✓ | ✓ |
| Create post | ✗ | ✓ | ✓ |
| Edit own post | ✗ | ✓ | ✓ |
| Delete own post | ✗ | ✓ | ✓ |
| Approve posts | ✗ | ✗ | ✓ |
| Like posts | ✓ | ✓ | ✓ |
| Comment | ✓ | ✓ | ✓ |
| Moderate comments | ✗ | ✗ | ✓ |

## Best Practices

1. **Content Quality** - Encourage high-quality, valuable content
2. **Product Relevance** - Linked products should be relevant to content
3. **Image Guidelines** - Provide image size/format guidelines
4. **Moderation Speed** - Review posts within 24-48 hours
5. **Engagement** - Respond to comments to build community
6. **SEO** - Use descriptive titles and excerpts
7. **Mobile-First** - Ensure responsive design
8. **Accessibility** - Follow WCAG guidelines

## Conclusion

The blog system adds a content marketing dimension to the marketplace, allowing sellers to showcase expertise, drive traffic to products, and build community engagement.
