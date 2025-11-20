# Static Pages Documentation

This document describes the static informational pages available in the digital marketplace.

## Available Pages

### 1. About Page
- **Route:** `/about`
- **Route Name:** `about`
- **Component:** `App\Livewire\Pages\AboutPage`
- **Description:** Displays information about the marketplace, mission, values, and features
- **Features:**
  - Mission statement
  - Platform features (Secure Transactions, Instant Delivery, Community Driven)
  - Statistics showcase
  - Core values section

### 2. Contact Page
- **Route:** `/contact`
- **Route Name:** `contact`
- **Component:** `App\Livewire\Pages\ContactPage`
- **Description:** Contact form for users to reach out to support
- **Features:**
  - Contact form with validation (name, email, subject, message)
  - Contact information display (email, business hours, location)
  - Form submission sends email notification to admin
  - Success message after submission
  - Help center link

### 3. Terms of Service Page
- **Route:** `/terms`
- **Route Name:** `terms`
- **Component:** `App\Livewire\Pages\TermsPage`
- **Description:** Legal terms and conditions for using the platform
- **Sections:**
  1. Agreement to Terms
  2. User Accounts
  3. Seller Terms
  4. Buyer Terms
  5. Intellectual Property
  6. Prohibited Activities
  7. Payment Terms
  8. Refunds and Disputes
  9. Limitation of Liability
  10. Termination
  11. Changes to Terms
  12. Contact Information

### 4. Privacy Policy Page
- **Route:** `/privacy`
- **Route Name:** `privacy`
- **Component:** `App\Livewire\Pages\PrivacyPage`
- **Description:** Privacy policy explaining data collection and usage
- **Sections:**
  1. Introduction
  2. Information We Collect
  3. How We Use Your Information
  4. Information Sharing
  5. Data Security
  6. Data Retention
  7. Your Rights
  8. Cookies and Tracking
  9. Third-Party Links
  10. Children's Privacy
  11. International Data Transfers
  12. Changes to Policy
  13. Contact Information

## Footer Links

All pages are linked in the footer sections of:
- Welcome page (`resources/views/welcome.blade.php`)
- Guest layout (`resources/views/components/layouts/guest.blade.php`)

## Contact Form Notification

When a user submits the contact form, an email notification is sent to the admin email address configured in `config/mail.php` under `from.address`.

**Notification Class:** `App\Notifications\ContactFormSubmitted`

## Customization

To customize the content of these pages:

1. **About Page:** Edit `resources/views/livewire/pages/about-page.blade.php`
2. **Contact Page:** Edit `resources/views/livewire/pages/contact-page.blade.php`
3. **Terms Page:** Edit `resources/views/livewire/pages/terms-page.blade.php`
4. **Privacy Page:** Edit `resources/views/livewire/pages/privacy-page.blade.php`

## Accessibility

All pages include:
- Proper semantic HTML structure
- ARIA labels where appropriate
- Keyboard navigation support
- Dark mode support
- Responsive design for mobile devices

## Testing

To test the pages:

```bash
# Visit the pages in your browser
http://your-domain.com/about
http://your-domain.com/contact
http://your-domain.com/terms
http://your-domain.com/privacy

# Test contact form submission
# Fill out the form and verify email is sent to admin
```

## Support Pages

### 5. Help Center Page
- **Route:** `/help`
- **Route Name:** `help-center`
- **Component:** `App\Livewire\Pages\HelpCenterPage`
- **Description:** Central hub for all support resources
- **Features:**
  - Search functionality (UI only)
  - Quick links to FAQ, Seller Guide, and Contact
  - Popular topics organized by category (Getting Started, Buying, Selling, Account & Security)
  - Call-to-action for contacting support

### 6. FAQ Page
- **Route:** `/faq`
- **Route Name:** `faq`
- **Component:** `App\Livewire\Pages\FaqPage`
- **Description:** Frequently asked questions with expandable answers
- **Features:**
  - Interactive accordion-style Q&A sections
  - Categories: General, Buying & Downloads, Selling, Account & Security
  - Livewire-powered toggle functionality
  - Links to Help Center and Contact pages

### 7. Seller Guide Page
- **Route:** `/seller-guide`
- **Route Name:** `seller-guide`
- **Component:** `App\Livewire\Pages\SellerGuidePage`
- **Description:** Comprehensive guide for sellers
- **Features:**
  - Step-by-step getting started guide
  - Product upload requirements and best practices
  - Commission structure and earnings calculator
  - Withdrawal process explanation
  - Do's and Don'ts for sellers
  - Dynamic content based on authentication status

## Notes

- All email addresses and contact information in the pages are placeholders (e.g., `support@example.com`)
- Update these with your actual contact information before going live
- The statistics on the About page (10,000+ products, etc.) are example numbers and should be updated with real data
- Commission rate (15%) and minimum withdrawal ($50) in Seller Guide should match your actual business model
