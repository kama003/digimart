# Introduction

REST API for accessing digital marketplace products, categories, and user purchases.

<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>

    Welcome to the Digital Marketplace API documentation. This API allows you to browse products, search by category and filters, and manage your purchases.

    ## Base URL
    All API requests should be made to: `{base_url}/api/v1`

    ## Rate Limiting
    The API implements rate limiting to ensure fair usage:
    - **Public endpoints**: 60 requests per minute per IP address
    - **Authenticated endpoints**: 120 requests per minute per user
    - **Download generation**: 10 requests per hour per user

    When you exceed the rate limit, you'll receive a `429 Too Many Requests` response with a `Retry-After` header indicating when you can retry.

    <aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
    You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>

