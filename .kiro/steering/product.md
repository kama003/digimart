# Product Overview

Digital Marketplace is a production-ready platform for buying and selling digital products built with Laravel 11 and Livewire 3.

## Core Features

- **Multi-role system**: Customer, Seller, and Admin roles with distinct capabilities
- **Product management**: Upload, browse, search, and purchase digital assets (audio, video, 3D models, templates, graphics)
- **Payment processing**: Integrated Stripe and Paytm gateways with webhook verification
- **Digital delivery**: Secure, time-limited download links (24-hour expiry by default)
- **Seller tools**: Dashboard, analytics, product management, and withdrawal requests
- **Admin moderation**: Product approval, user management, transaction oversight, review moderation
- **Review system**: Star ratings, verified purchase badges, and moderation
- **Notification system**: Database and email notifications for key events
- **API**: REST API with Sanctum authentication and comprehensive documentation

## Business Model

- Platform commission: 10% (configurable via `PLATFORM_COMMISSION_PERCENT`)
- Sellers can request withdrawals of their earnings
- Admins approve/reject withdrawal requests

## Key Differentiators

- Fully responsive with dark mode support
- WCAG 2.1 AA accessibility compliance
- Queue-based background processing for performance
- Comprehensive security measures (PCI compliance, signed URLs, rate limiting)
- API documentation with interactive "Try It Out" feature
