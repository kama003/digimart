# Digital Marketplace API Documentation

## Overview

The Digital Marketplace provides a RESTful API for accessing products, categories, and managing user purchases. The API is built using Laravel 11 and follows REST principles.

## Base URL

```
https://your-domain.com/api/v1
```

## Authentication

The API uses **Laravel Sanctum** for authentication with Bearer tokens.

### Getting an API Token

1. Log in to your marketplace account
2. Navigate to **Settings > API Tokens**
3. Click **Create New Token**
4. Give your token a descriptive name
5. Copy the generated token (shown only once)

### Using the Token

Include the token in the `Authorization` header of your requests:

```
Authorization: Bearer YOUR_API_TOKEN_HERE
```

### Example with cURL

```bash
curl -H "Authorization: Bearer YOUR_API_TOKEN_HERE" \
     https://your-domain.com/api/v1/user/purchases
```

## Rate Limiting

The API implements rate limiting to ensure fair usage:

| Endpoint Type | Limit | Window |
|--------------|-------|--------|
| Public endpoints | 60 requests | per minute per IP |
| Authenticated endpoints | 120 requests | per minute per user |
| Download generation | 10 requests | per hour per user |

When you exceed the rate limit, you'll receive a `429 Too Many Requests` response with a `Retry-After` header.

## Endpoints

### Public Endpoints (No Authentication Required)

#### List Products

```
GET /api/v1/products
```

Get a paginated list of approved and active products.

**Query Parameters:**
- `category` (string, optional) - Filter by category slug
- `type` (string, optional) - Filter by product type (audio, video, 3d, template, graphic)
- `min_price` (number, optional) - Minimum price filter
- `max_price` (number, optional) - Maximum price filter
- `search` (string, optional) - Search in title and description
- `page` (integer, optional) - Page number for pagination

**Example Request:**
```bash
curl "https://your-domain.com/api/v1/products?category=audio&min_price=10&max_price=100&page=1"
```

**Example Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Premium Audio Pack",
      "slug": "premium-audio-pack",
      "description": "High-quality audio files",
      "short_description": "Professional audio collection",
      "product_type": "audio",
      "price": "49.99",
      "license_type": "Commercial",
      "thumbnail_path": "products/thumbnails/audio-pack.jpg",
      "downloads_count": 150,
      "category": {
        "id": 1,
        "name": "Audio",
        "slug": "audio"
      },
      "seller": {
        "id": 2,
        "name": "John Doe"
      },
      "created_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost/api/v1/products?page=1",
    "last": "http://localhost/api/v1/products?page=5",
    "prev": null,
    "next": "http://localhost/api/v1/products?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 20,
    "to": 20,
    "total": 95
  }
}
```

#### Get Product Details

```
GET /api/v1/products/{slug}
```

Get detailed information about a specific product.

**URL Parameters:**
- `slug` (string, required) - The product slug

**Example Request:**
```bash
curl "https://your-domain.com/api/v1/products/premium-audio-pack"
```

**Example Response:**
```json
{
  "data": {
    "id": 1,
    "title": "Premium Audio Pack",
    "slug": "premium-audio-pack",
    "description": "High-quality audio files for your projects. Includes 50+ tracks.",
    "short_description": "Professional audio collection",
    "product_type": "audio",
    "price": "49.99",
    "license_type": "Commercial",
    "thumbnail_path": "products/thumbnails/audio-pack.jpg",
    "downloads_count": 150,
    "category": {
      "id": 1,
      "name": "Audio",
      "slug": "audio"
    },
    "seller": {
      "id": 2,
      "name": "John Doe"
    },
    "created_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

#### List Categories

```
GET /api/v1/categories
```

Get a list of all product categories.

**Example Request:**
```bash
curl "https://your-domain.com/api/v1/categories"
```

**Example Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Audio",
      "slug": "audio",
      "description": "Audio files, music tracks, and sound effects",
      "icon": "musical-note",
      "order": 1
    },
    {
      "id": 2,
      "name": "Video",
      "slug": "video",
      "description": "Video templates and stock footage",
      "icon": "film",
      "order": 2
    }
  ]
}
```

### Authenticated Endpoints (Requires API Token)

#### Get Purchased Products

```
GET /api/v1/user/purchases
```

Get a paginated list of products purchased by the authenticated user.

**Headers:**
```
Authorization: Bearer YOUR_API_TOKEN
```

**Query Parameters:**
- `page` (integer, optional) - Page number for pagination

**Example Request:**
```bash
curl -H "Authorization: Bearer YOUR_API_TOKEN" \
     "https://your-domain.com/api/v1/user/purchases?page=1"
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Premium Audio Pack",
      "slug": "premium-audio-pack",
      "description": "High-quality audio files",
      "short_description": "Professional audio collection",
      "product_type": "audio",
      "price": "49.99",
      "thumbnail_path": "products/thumbnails/audio-pack.jpg",
      "category": {
        "id": 1,
        "name": "Audio",
        "slug": "audio"
      },
      "seller": {
        "id": 2,
        "name": "John Doe"
      },
      "purchased_at": "2024-01-20T14:30:00+00:00",
      "transaction_id": 5
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 20,
    "total": 45
  }
}
```

#### Generate Download Link

```
POST /api/v1/user/downloads/{download}
```

Generate a new time-limited download link for a purchased product.

**Headers:**
```
Authorization: Bearer YOUR_API_TOKEN
```

**URL Parameters:**
- `download` (integer, required) - The download ID

**Example Request:**
```bash
curl -X POST \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     "https://your-domain.com/api/v1/user/downloads/123"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "download_url": "https://s3.amazonaws.com/bucket/products/file.zip?signature=...",
    "expires_at": "2024-01-21T14:30:00+00:00",
    "product": {
      "id": 1,
      "title": "Premium Audio Pack",
      "slug": "premium-audio-pack"
    }
  },
  "message": "Download link generated successfully."
}
```

## Error Responses

The API uses standard HTTP status codes:

| Code | Status | Description |
|------|--------|-------------|
| 200 | OK | Request successful |
| 401 | Unauthorized | Authentication required or failed |
| 403 | Forbidden | Access denied |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation failed |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server error |

### Error Response Format

```json
{
  "message": "Error message here"
}
```

For validation errors (422):
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "The field name is required."
    ]
  }
}
```

## Code Examples

### JavaScript (Fetch API)

```javascript
// Get products
const response = await fetch('https://your-domain.com/api/v1/products?category=audio');
const data = await response.json();
console.log(data);

// Get purchases (authenticated)
const token = 'YOUR_API_TOKEN';
const purchasesResponse = await fetch('https://your-domain.com/api/v1/user/purchases', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  }
});
const purchases = await purchasesResponse.json();
console.log(purchases);
```

### PHP (Guzzle)

```php
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'https://your-domain.com/api/v1/',
]);

// Get products
$response = $client->get('products', [
    'query' => ['category' => 'audio']
]);
$products = json_decode($response->getBody(), true);

// Get purchases (authenticated)
$token = 'YOUR_API_TOKEN';
$response = $client->get('user/purchases', [
    'headers' => [
        'Authorization' => "Bearer {$token}",
        'Accept' => 'application/json',
    ]
]);
$purchases = json_decode($response->getBody(), true);
```

### Python (Requests)

```python
import requests

# Get products
response = requests.get('https://your-domain.com/api/v1/products', params={'category': 'audio'})
products = response.json()

# Get purchases (authenticated)
token = 'YOUR_API_TOKEN'
headers = {
    'Authorization': f'Bearer {token}',
    'Accept': 'application/json'
}
response = requests.get('https://your-domain.com/api/v1/user/purchases', headers=headers)
purchases = response.json()
```

## Best Practices

1. **Cache responses**: Cache product and category data to reduce API calls
2. **Handle rate limits**: Implement exponential backoff when receiving 429 responses
3. **Secure your tokens**: Never expose API tokens in client-side code or public repositories
4. **Use pagination**: Don't try to fetch all results at once
5. **Handle errors gracefully**: Always check HTTP status codes and handle errors appropriately
6. **Validate data**: Validate data on your end before making API requests

## Interactive Documentation

For interactive API documentation with a "Try It Out" feature, visit:

```
https://your-domain.com/docs
```

This provides a web interface where you can test all API endpoints directly from your browser.

## Support

For API support or questions, please contact our support team or visit the marketplace help center.
