# Template Usage Documentation - SRTdash Admin Dashboard

## Template Information

- **Template Name**: SRTdash Admin Dashboard
- **Source**: https://github.com/puikinsh/srtdash-admin-dashboard
- **Author**: Colorlib / DashboardPack.com
- **License**: MIT License
- **Tech Stack**: Bootstrap 5.3.8, Vanilla JavaScript, Font Awesome 7.1, MetisMenuJS

## Where Template Files Are Stored

The original, unmodified SRTdash template has been preserved in:

```
public/templates/srtdash/
├── srtdash/
│   ├── assets/
│   │   ├── css/          # Bootstrap 5.3.8, Font Awesome, template styles
│   │   ├── js/           # Vanilla JS scripts, chart libraries
│   │   ├── fonts/        # Icon fonts
│   │   └── images/       # Template images
│   ├── index.html        # Main dashboard (ICO/Crypto variant)
│   ├── starter.html      # Blank page template
│   └── *.html            # All 56 template pages
├── documentation/        # Template documentation
└── README.md             # Template README
```

## What Was Used From The Template

### 1. Layout Architecture
- **Sidebar + Main Content structure**: The fundamental layout pattern of sidebar navigation on the left and main content area on the right was inspired by SRTdash's `.page-container`, `.sidebar-menu`, and `.main-content` structure.
- **Responsive behavior**: The sidebar collapse/expand pattern on desktop and slide-out drawer on mobile follows the template's responsive approach.

### 2. Component Patterns
- **Stat cards / Dashboard widgets**: The concept of icon + value + label stat cards was adapted from SRTdash's dashboard widgets.
- **Table styling**: The idea of action buttons in table rows and styled table headers was inspired by the template's datatable and basic table pages.
- **Breadcrumb navigation**: The breadcrumb pattern in admin pages follows the template's `.breadcrumbs-area` structure.

### 3. Technical Foundation
- **Bootstrap 5**: Both the template and our custom admin use Bootstrap 5 as the CSS framework foundation.
- **Font Awesome icons**: The icon system uses Font Awesome (template uses v7.1, we use v6 from CDN).
- **Vanilla JavaScript**: Like the template, we avoid jQuery and use plain JavaScript for interactions.

## Customizations Applied

### Visual Design
- **Complete color retheme**: Replaced template's default blue/purple brand colors with CGV Cinema's signature red (#E71A0F), dark (#1A1A2E), and gold (#D4A843).
- **Custom sidebar**: Rebuilt with light theme, CGV-red active states, and custom hover effects.
- **Custom stat cards**: Designed with left border accent colors and tinted icon backgrounds specific to each metric type.
- **Custom table headers**: White background with CGV-red bottom border instead of template's dark header.

### Layout Changes
- **Simplified header**: Removed template's notification dropdown, message inbox, and settings panel. Replaced with a clean header showing only page title, home link, and user profile.
- **Removed preloader**: The template's loading animation was removed for faster perceived performance.
- **Removed offset panel**: The right-side settings panel was removed as unnecessary for this project.
- **Removed footer**: The template's copyright footer was replaced with a minimal attribution line.

### Component Redesigns
- **Search bar**: Custom design with icon inside input and CGV-red focus ring.
- **Filter/sort bar**: Custom pill-style switcher for content type filtering.
- **Action buttons**: Redesigned with distinct colors for each action (edit=blue, lock=orange, delete=red).
- **Form styling**: All forms use Bootstrap 5 with CGV brand color overrides.

### Technical Enhancements
- **PHP MVC Integration**: Converted static HTML template into dynamic PHP views with layout/content separation.
- **Security**: Added `htmlspecialchars()` on all output, prepared statements for database queries.
- **Accessibility**: Maintained semantic HTML and ARIA labels from template, added Vietnamese language support.

## File Mapping

| Template File | Our Custom File | Notes |
|--------------|-----------------|-------|
| `srtdash/assets/css/styles.css` | `public/css/admin.css` | Complete rewrite with CGV branding |
| `srtdash/assets/css/responsive.css` | `public/css/admin.css` (bottom section) | Custom responsive breakpoints |
| `srtdash/assets/css/default-css.css` | `public/css/admin-components.css` | Custom utility classes |
| `srtdash/index.html` | `app/Views/layouts/admin.php` | Converted to PHP layout with dynamic content |
| `srtdash/starter.html` | `app/Views/admin/dashboard.php` | Dashboard welcome page |
| `srtdash/assets/js/scripts.js` | `public/js/admin.js` | Custom JS for sidebar toggle and interactions |

## How To Verify Template Usage

1. **Check file structure**: `public/templates/srtdash/` contains the complete original template.
2. **View source**: Open any admin page and check the HTML comment at the top of `<head>`.
3. **Check CSS comments**: Both `public/css/admin.css` and `public/css/admin-components.css` have attribution headers.
4. **See attribution footer**: Every admin page shows the template credit at the bottom.

## License Compliance

SRTdash is licensed under the MIT License, which permits:
- Use, copy, modify, merge, publish, distribute, sublicense, and/or sell
- Requirement: Must credit Colorlib as the original author

Our attribution is visible in:
- HTML source comments
- CSS file headers
- Footer on every admin page
- This documentation file
