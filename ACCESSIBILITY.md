# Accessibility Features

This document outlines the accessibility features implemented in the Digital Marketplace application to ensure WCAG 2.1 AA compliance and provide an inclusive user experience.

## Overview

The Digital Marketplace has been designed with accessibility as a core principle, ensuring that all users, including those with disabilities, can effectively use the platform.

## Implemented Features

### 1. Skip to Main Content Link

- **Location**: All layouts (guest and authenticated)
- **Implementation**: Hidden by default, visible on keyboard focus
- **Purpose**: Allows keyboard users to bypass repetitive navigation and jump directly to main content
- **Keyboard Shortcut**: Tab key from page load

### 2. Semantic HTML Structure

All pages use proper semantic HTML5 elements:

- `<header>` for page headers and navigation
- `<nav>` for navigation menus with `aria-label` attributes
- `<main>` for primary page content with `id="main-content"`
- `<article>` for self-contained content (product cards, cart items)
- `<aside>` for complementary content (order summaries, sidebars)
- `<section>` for thematic groupings with `aria-labelledby` attributes
- `<footer>` for page footers

### 3. Proper Heading Hierarchy

- Single `<h1>` per page for main page title
- Logical `<h2>` and `<h3>` structure for subsections
- No skipped heading levels
- Headings properly associated with sections using `id` and `aria-labelledby`

### 4. ARIA Labels and Attributes

#### Interactive Elements
- All icon-only buttons have `aria-label` attributes
- Mobile menu toggle has `aria-expanded` and `aria-controls`
- Form inputs have associated labels (visible or `aria-label`)
- Decorative icons marked with `aria-hidden="true"`

#### Dynamic Content
- `aria-live="polite"` for non-critical updates (cart count, search results)
- `aria-live="assertive"` for critical alerts (error messages)
- `role="alert"` for important messages
- `role="status"` for status updates

#### Navigation
- Navigation landmarks with `aria-label` for distinction
- Breadcrumb navigation with `aria-label="Breadcrumb"`
- Search forms with `role="search"`

### 5. Keyboard Navigation

All interactive elements are keyboard accessible:

- **Tab**: Navigate forward through interactive elements
- **Shift+Tab**: Navigate backward
- **Enter/Space**: Activate buttons and links
- **Escape**: Close modals and dropdowns (where applicable)
- **Arrow Keys**: Navigate dropdown menus (where applicable)

### 6. Focus Indicators

Enhanced focus indicators for better visibility:

- 2px solid outline in green (#22c55e in light mode, #4ade80 in dark mode)
- 2px offset from element
- Rounded corners for better aesthetics
- Visible on all focusable elements
- Consistent across light and dark modes

**CSS Implementation**:
```css
*:focus-visible {
    outline: 2px solid var(--color-green-500);
    outline-offset: 2px;
}
```

### 7. Alt Text for Images

- All product thumbnails have descriptive `alt` attributes
- Placeholder images have `alt` text indicating no image available
- Decorative images marked with `aria-hidden="true"` and empty `alt=""`
- SVG icons marked as decorative with `aria-hidden="true"`

### 8. Form Accessibility

- All form inputs have associated `<label>` elements
- Labels use `for` attribute matching input `id`
- Required fields indicated both visually and programmatically
- Error messages associated with fields using `aria-describedby`
- Real-time validation feedback announced to screen readers

### 9. Color Contrast

All text meets WCAG AA standards:

- Normal text: 4.5:1 contrast ratio minimum
- Large text (18pt+): 3:1 contrast ratio minimum
- Interactive elements: 3:1 contrast ratio minimum
- Tested in both light and dark modes

### 10. Screen Reader Support

- Descriptive link text (no "click here" or "read more")
- Hidden text for context using `.sr-only` class
- Proper table headers with `scope` attributes
- List structures for navigation and content groups
- Meaningful page titles

## Testing

### Manual Testing Checklist

- [ ] Navigate entire site using only keyboard
- [ ] Test with screen reader (NVDA, JAWS, or VoiceOver)
- [ ] Verify skip link functionality
- [ ] Check focus indicators on all interactive elements
- [ ] Test form submission and error handling
- [ ] Verify all images have appropriate alt text
- [ ] Test in high contrast mode
- [ ] Verify color contrast ratios

### Automated Testing Tools

Recommended tools for accessibility testing:

1. **axe DevTools** (Browser Extension)
   - Comprehensive accessibility scanning
   - WCAG 2.1 compliance checking

2. **Lighthouse** (Chrome DevTools)
   - Accessibility audit
   - Performance and best practices

3. **WAVE** (Browser Extension)
   - Visual feedback on accessibility issues
   - Color contrast checking

4. **Keyboard Navigation**
   - Tab through all interactive elements
   - Verify logical tab order
   - Test modal and dropdown interactions

## Browser Support

Accessibility features tested and supported in:

- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Screen Reader Support

Tested with:

- NVDA (Windows)
- JAWS (Windows)
- VoiceOver (macOS/iOS)
- TalkBack (Android)

## Known Limitations

1. **Third-party Components**: Some Flux UI components may have limited accessibility customization
2. **Payment Forms**: Stripe and Paytm embedded forms follow their own accessibility standards
3. **Dynamic Content**: Some Livewire updates may require additional aria-live regions

## Future Improvements

- [ ] Add keyboard shortcuts documentation
- [ ] Implement focus management for modals
- [ ] Add high contrast mode toggle
- [ ] Implement reduced motion preferences
- [ ] Add text size adjustment controls
- [ ] Improve error recovery guidance
- [ ] Add accessibility statement page

## Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [MDN Accessibility](https://developer.mozilla.org/en-US/docs/Web/Accessibility)
- [WebAIM](https://webaim.org/)
- [A11y Project](https://www.a11yproject.com/)

## Reporting Issues

If you encounter accessibility issues, please report them with:

1. Description of the issue
2. Steps to reproduce
3. Browser and assistive technology used
4. Expected vs. actual behavior

## Compliance Statement

This application strives to meet WCAG 2.1 Level AA standards. We are committed to maintaining and improving accessibility for all users.

Last Updated: {{ date('Y-m-d') }}
