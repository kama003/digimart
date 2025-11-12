# Error Responses

The API uses standard HTTP response codes to indicate the success or failure of requests.

## HTTP Status Codes

| Code | Status | Description |
|------|--------|-------------|
| 200 | OK | The request was successful |
| 201 | Created | A new resource was successfully created |
| 400 | Bad Request | The request was invalid or cannot be served |
| 401 | Unauthorized | Authentication is required and has failed or not been provided |
| 403 | Forbidden | The request is valid but the server is refusing action (e.g., accessing another user's download) |
| 404 | Not Found | The requested resource could not be found |
| 422 | Unprocessable Entity | The request was well-formed but contains semantic errors (validation failed) |
| 429 | Too Many Requests | Rate limit exceeded. Check the `Retry-After` header |
| 500 | Internal Server Error | An error occurred on the server |

## Error Response Format

All error responses follow a consistent JSON structure:

### Authentication Error (401)
```json
{
  "message": "Unauthenticated."
}
```

### Validation Error (422)
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

### Not Found Error (404)
```json
{
  "message": "No query results for model [App\\Models\\Product]."
}
```

### Rate Limit Error (429)
```json
{
  "message": "Too Many Requests"
}
```

The response will also include a `Retry-After` header indicating the number of seconds to wait before making another request.

### Forbidden Error (403)
```json
{
  "success": false,
  "message": "Unauthorized access to this download."
}
```

### Server Error (500)
```json
{
  "message": "Server Error"
}
```

## Rate Limiting Details

The API implements three different rate limit tiers:

### Public Endpoints
- **Limit**: 60 requests per minute per IP address
- **Applies to**: `/api/v1/products`, `/api/v1/products/{slug}`, `/api/v1/categories`

### Authenticated Endpoints
- **Limit**: 120 requests per minute per authenticated user
- **Applies to**: `/api/v1/user/purchases`

### Download Generation
- **Limit**: 10 requests per hour per authenticated user
- **Applies to**: `/api/v1/user/downloads/{download}`
- **Reason**: Prevents abuse of download link generation

When you hit a rate limit, the API returns a `429 Too Many Requests` status code along with these headers:
- `X-RateLimit-Limit`: The maximum number of requests allowed
- `X-RateLimit-Remaining`: The number of requests remaining in the current window
- `Retry-After`: The number of seconds to wait before retrying

## Best Practices

1. **Handle errors gracefully**: Always check the HTTP status code and handle errors appropriately
2. **Respect rate limits**: Implement exponential backoff when you receive 429 responses
3. **Cache responses**: Cache product and category data to reduce API calls
4. **Use pagination**: Don't try to fetch all results at once
5. **Validate before sending**: Validate data on your end before making API requests to avoid 422 errors
