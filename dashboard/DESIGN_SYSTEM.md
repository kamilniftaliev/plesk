# Design System Documentation

## Overview

This design system provides a consistent, modern UI for all forms and components across the application. It features:

- **Dark Mode Support**: Automatic dark mode based on user preference
- **Consistent Styling**: All form elements follow the same design language
- **Icon Integration**: Automatic icon mapping based on field context
- **Accessibility**: Focus states, proper labels, and keyboard navigation
- **Bootstrap Compatible**: Works alongside existing Bootstrap code

## Quick Start

### 1. Already Integrated

The design system is already integrated into all dashboard pages through:
- `/dashboard/assets/css/design-system.css` (loaded in admin_header.php)
- `/dashboard/assets/js/design-system.js` (loaded in footer.php)

### 2. Automatic Application

The JavaScript automatically applies design system classes to:
- All text inputs
- All select dropdowns
- All textareas
- All buttons
- All input-group elements with appropriate icons

### 3. Manual Usage

You can also manually add design system classes to your forms.

## Components

### Input Fields

#### Basic Input
```html
<input type="text" class="form-control ds-input" placeholder="Enter text">
```

#### With Icon (Input Group)
```html
<div class="input-group ds-input-group">
    <span class="input-group-addon">
        <i class="fa fa-user"></i>
    </span>
    <input type="text" class="form-control ds-input" placeholder="Username">
</div>
```

#### With Label
```html
<div class="form-group ds-form-group">
    <label for="email" class="ds-form-label">Email Address</label>
    <div class="input-group ds-input-group">
        <span class="input-group-addon">
            <i class="fa fa-envelope"></i>
        </span>
        <input type="email" id="email" class="form-control ds-input" placeholder="Enter email">
    </div>
    <small class="ds-form-helper">We'll never share your email</small>
</div>
```

#### Required Field
```html
<label for="name" class="ds-form-label required">Full Name</label>
```

The `.required` class automatically adds a red asterisk (*).

#### Error State
```html
<input type="text" class="form-control ds-input is-invalid">
<span class="ds-form-error">This field is required</span>
```

### Select Dropdowns

#### Basic Select
```html
<select class="form-control ds-select">
    <option>Select an option</option>
    <option>Option 1</option>
    <option>Option 2</option>
</select>
```

#### With Icon
```html
<div class="input-group ds-input-group">
    <span class="input-group-addon">
        <i class="fa fa-globe"></i>
    </span>
    <select class="form-control ds-select">
        <option>Select country</option>
        <option>USA</option>
        <option>UK</option>
    </select>
</div>
```

### Textarea

```html
<textarea class="form-control ds-textarea" rows="4" placeholder="Enter description"></textarea>
```

### Buttons

#### Button Variants
```html
<!-- Primary Button -->
<button class="btn btn-primary ds-btn ds-btn-primary">
    <i class="fa fa-save"></i>
    Save
</button>

<!-- Success Button -->
<button class="btn btn-success ds-btn ds-btn-success">
    <i class="fa fa-check"></i>
    Submit
</button>

<!-- Danger Button -->
<button class="btn btn-danger ds-btn ds-btn-danger">
    <i class="fa fa-trash"></i>
    Delete
</button>

<!-- Warning Button -->
<button class="btn btn-warning ds-btn ds-btn-warning">
    <i class="fa fa-exclamation"></i>
    Warning
</button>

<!-- Info Button -->
<button class="btn btn-info ds-btn ds-btn-info">
    <i class="fa fa-info-circle"></i>
    Info
</button>

<!-- Default Button -->
<button class="btn btn-default ds-btn ds-btn-default">
    <i class="fa fa-times"></i>
    Cancel
</button>
```

#### Button Sizes
```html
<button class="btn ds-btn ds-btn-primary ds-btn-sm">Small</button>
<button class="btn ds-btn ds-btn-primary">Default</button>
<button class="btn ds-btn ds-btn-primary ds-btn-lg">Large</button>
```

#### Block Button
```html
<button class="btn ds-btn ds-btn-primary ds-btn-block">Full Width Button</button>
```

### Radio Buttons

```html
<div class="form-group ds-form-group">
    <label class="ds-form-label">Select Region</label>
    <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
        <label class="ds-radio">
            <input type="radio" name="region" value="global" checked>
            <span class="ds-radio-mark"></span>
            Global
        </label>
        <label class="ds-radio">
            <input type="radio" name="region" value="in">
            <span class="ds-radio-mark"></span>
            India
        </label>
        <label class="ds-radio">
            <input type="radio" name="region" value="cn">
            <span class="ds-radio-mark"></span>
            China
        </label>
    </div>
</div>
```

### Checkboxes

```html
<label class="ds-checkbox">
    <input type="checkbox" name="terms" value="1">
    <span class="ds-checkbox-mark"></span>
    I agree to terms and conditions
</label>
```

### Toggle Switch

```html
<div class="ds-switch-wrapper">
    <label class="ds-switch">
        <input type="checkbox" name="notifications">
        <span class="ds-switch-slider"></span>
    </label>
    <span class="ds-switch-label">Enable notifications</span>
</div>
```

## Icon Mapping

The design system automatically maps icons to input fields based on their label text or name attribute. Here are the main mappings:

| Field Type | Icon | Keywords |
|-----------|------|----------|
| Username | fa-user | username, user, name |
| Email | fa-envelope | email, e-mail, mail |
| Password | fa-lock | password, pass |
| Phone | fa-phone | phone, telephone, mobile, contact |
| Address | fa-map-marker | address, location |
| City | fa-building | city |
| Country | fa-globe | country, region |
| Company | fa-building | company, organization |
| ID | fa-id-card | id, license |
| Price | fa-dollar | price, cost, amount, balance |
| Server | fa-server | server, host, hostname |
| Date | fa-calendar | date, datetime |
| Status | fa-info-circle | status |
| Type | fa-tag | type, category |
| Description | fa-align-left | description, note, comment |

### Custom Icon Mapping

You can add custom mappings in `/dashboard/assets/js/design-system.js`:

```javascript
const iconMapping = {
  'custom field': 'fa-custom-icon',
  // ... add more mappings
};
```

Or manually specify icons in HTML:

```html
<span class="input-group-addon">
    <i class="fa fa-custom-icon"></i>
</span>
```

## Complete Form Example

```html
<form method="POST" action="">
    <fieldset>
        <!-- Username Field -->
        <div class="form-group ds-form-group">
            <label for="username" class="ds-form-label required">Username</label>
            <div class="input-group ds-input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                <input type="text" id="username" name="username"
                       class="form-control ds-input"
                       placeholder="Enter username" required>
            </div>
        </div>

        <!-- Email Field -->
        <div class="form-group ds-form-group">
            <label for="email" class="ds-form-label required">Email</label>
            <div class="input-group ds-input-group">
                <span class="input-group-addon">
                    <i class="fa fa-envelope"></i>
                </span>
                <input type="email" id="email" name="email"
                       class="form-control ds-input"
                       placeholder="Enter email" required>
            </div>
            <small class="ds-form-helper">We'll never share your email</small>
        </div>

        <!-- Password Field -->
        <div class="form-group ds-form-group">
            <label for="password" class="ds-form-label required">Password</label>
            <div class="input-group ds-input-group">
                <span class="input-group-addon">
                    <i class="fa fa-lock"></i>
                </span>
                <input type="password" id="password" name="password"
                       class="form-control ds-input"
                       placeholder="Enter password" required>
            </div>
        </div>

        <!-- Select Field -->
        <div class="form-group ds-form-group">
            <label for="country" class="ds-form-label">Country</label>
            <div class="input-group ds-input-group">
                <span class="input-group-addon">
                    <i class="fa fa-globe"></i>
                </span>
                <select id="country" name="country" class="form-control ds-select">
                    <option value="">Select country</option>
                    <option value="us">United States</option>
                    <option value="uk">United Kingdom</option>
                    <option value="in">India</option>
                </select>
            </div>
        </div>

        <!-- Textarea Field -->
        <div class="form-group ds-form-group">
            <label for="notes" class="ds-form-label">Notes</label>
            <textarea id="notes" name="notes"
                      class="form-control ds-textarea"
                      rows="4"
                      placeholder="Enter any additional notes"></textarea>
        </div>

        <!-- Radio Buttons -->
        <div class="form-group ds-form-group">
            <label class="ds-form-label">Account Type</label>
            <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                <label class="ds-radio">
                    <input type="radio" name="account_type" value="personal" checked>
                    <span class="ds-radio-mark"></span>
                    Personal
                </label>
                <label class="ds-radio">
                    <input type="radio" name="account_type" value="business">
                    <span class="ds-radio-mark"></span>
                    Business
                </label>
            </div>
        </div>

        <!-- Checkbox -->
        <div class="form-group ds-form-group">
            <label class="ds-checkbox">
                <input type="checkbox" name="terms" value="1" required>
                <span class="ds-checkbox-mark"></span>
                I agree to the terms and conditions
            </label>
        </div>

        <!-- Submit Button -->
        <div class="form-group text-center ds-form-group">
            <button type="submit" class="btn btn-primary ds-btn ds-btn-primary">
                <i class="fa fa-save"></i>
                Save Changes
            </button>
            <button type="button" class="btn btn-default ds-btn ds-btn-default">
                <i class="fa fa-times"></i>
                Cancel
            </button>
        </div>
    </fieldset>
</form>
```

## Dark Mode

Dark mode is automatically applied based on:
1. User's system preference (`prefers-color-scheme: dark`)
2. Manual class toggle by adding `.dark-mode` to any parent element

All components automatically adapt their colors in dark mode.

## CSS Variables

You can customize the design system by overriding CSS variables:

```css
:root {
  /* Primary Colors */
  --ds-primary: #1977cc;
  --ds-primary-hover: #1560a8;

  /* Background */
  --ds-bg-primary: #ffffff;
  --ds-bg-secondary: #f9fafb;

  /* Text */
  --ds-text-primary: #111827;
  --ds-text-secondary: #6b7280;

  /* Borders */
  --ds-border-color: #e5e7eb;
  --ds-border-focus: #1977cc;

  /* And many more... see design-system.css */
}
```

## Utility Classes

```html
<!-- Text Colors -->
<span class="ds-text-muted">Muted text</span>
<span class="ds-text-danger">Danger text</span>
<span class="ds-text-success">Success text</span>
<span class="ds-text-warning">Warning text</span>
<span class="ds-text-info">Info text</span>

<!-- Margins -->
<div class="ds-mb-0">No bottom margin</div>
<div class="ds-mb-1">Small bottom margin</div>
<div class="ds-mb-2">Medium bottom margin</div>
<div class="ds-mb-3">Large bottom margin</div>
```

## Migration Guide

### From Old Bootstrap Forms

**Before:**
```html
<div class="form-group">
    <label>Username</label>
    <input type="text" class="form-control" name="username">
</div>
```

**After:**
```html
<div class="form-group ds-form-group">
    <label class="ds-form-label">Username</label>
    <div class="input-group ds-input-group">
        <span class="input-group-addon">
            <i class="fa fa-user"></i>
        </span>
        <input type="text" class="form-control ds-input" name="username">
    </div>
</div>
```

### Automatic Migration

The JavaScript will automatically add `ds-input`, `ds-select`, `ds-textarea`, and `ds-btn` classes to existing form elements. However, for best results, manually add the design system classes and icon groups.

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- iOS Safari (latest)
- Chrome Android (latest)

## File Structure

```
/dashboard/assets/
├── css/
│   └── design-system.css     # All design system styles
├── js/
│   └── design-system.js      # Auto-application & icon mapping
└── DESIGN_SYSTEM.md          # This documentation
```

## Best Practices

1. **Always use labels**: Every input should have a associated label for accessibility
2. **Use required class**: Add `.required` to labels for required fields
3. **Provide helper text**: Use `.ds-form-helper` for additional context
4. **Show error states**: Use `.is-invalid` and `.ds-form-error` for validation
5. **Group related fields**: Use fieldsets for logical grouping
6. **Use appropriate icons**: Match icons to field context (auto-mapped by JS)
7. **Test in dark mode**: Always check how forms look in both light and dark modes
8. **Keep it simple**: Don't over-complicate form layouts

## Examples in Codebase

See these files for working examples:
- `/dashboard/forms/customer_form.php` - Simple form with radio buttons
- `/dashboard/forms/transfer_credit_form.php` - Modern styled form
- `/dashboard/imei_checker.php` - Form with modal and dynamic elements

## Support

For issues or questions about the design system:
1. Check this documentation
2. Review the CSS file for available classes
3. Check the JavaScript file for icon mappings
4. Look at example form files

## Changelog

### Version 1.0.0
- Initial release
- Complete form component library
- Dark mode support
- Automatic class application
- Icon mapping system
- Bootstrap 3 compatibility
