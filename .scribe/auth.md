# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_API_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

    ## Authentication

    This API uses **Laravel Sanctum** for authentication. To access authenticated endpoints, you need to:

    1. Log in to your account at the marketplace
    2. Navigate to **Settings > API Tokens**
    3. Click **Create New Token** and give it a name
    4. Copy the generated token (it will only be shown once)
    5. Include the token in the `Authorization` header of your requests as a Bearer token

    ### Example Request Header
    ```
    Authorization: Bearer YOUR_API_TOKEN_HERE
    ```

    ### Token Management
    - Tokens do not expire automatically but can be revoked at any time from your settings
    - You can create multiple tokens for different applications
    - Keep your tokens secure and never share them publicly
