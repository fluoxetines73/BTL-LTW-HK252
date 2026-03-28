# UI/UX Guidelines - Teammate-Work Project

## Table of Contents
1. [Introduction](#introduction)
2. [Design Philosophy](#design-philosophy)
3. [Color Palette](#color-palette)
4. [Typography](#typography)
5. [Spacing & Layout](#spacing--layout)
6. [Component Guidelines](#component-guidelines)
7. [Interactive States](#interactive-states)
8. [Responsive Design](#responsive-design)
9. [Accessibility](#accessibility)
10. [Code Examples](#code-examples)
11. [DO's and DON'Ts](#dos-and-donts)
12. [Tools & Resources](#tools--resources)

---

## Introduction

This document establishes the UI/UX guidelines for the Teammate-Work project. These guidelines ensure visual and functional consistency across all pages and components, providing a cohesive user experience.

**Purpose:**
- Define design standards for developers and designers
- Maintain brand consistency (CGV Cinema branding)
- Improve code maintainability and teamwork
- Ensure accessibility compliance (WCAG AA)

**Audience:**
- Frontend developers
- UI/UX designers
- New team members
- Contributors

**How to Use:**
- Reference this document when building new pages or components
- Follow color, typography, and spacing standards exactly
- Use provided code examples as templates
- Ask for clarification if guidelines are unclear

---

## Design Philosophy

### Core Principles

1. **Simplicity & Clarity**
   - Minimize visual clutter
   - Use clear, readable typography
   - Maintain adequate whitespace
   - Avoid unnecessary decorations

2. **Consistency**
   - Use the same components across pages
   - Follow established patterns
   - Maintain visual hierarchy
   - Keep interactions predictable

3. **Accessibility-First**
   - Ensure color contrast meets WCAG AA standards
   - Provide semantic HTML
   - Test keyboard navigation
   - Include alt text for all images

4. **Performance-Conscious Design**
   - Optimize images and assets
   - Minimize CSS complexity
   - Use CSS classes over inline styles
   - Prioritize load time

5. **Mobile-First Approach**
   - Design for mobile first
   - Enhance for larger screens
   - Test on real devices
   - Ensure touch targets are 44px minimum

---

## Color Palette

### Brand Colors

| Purpose | Color | Hex Code | Usage |
|---------|-------|----------|-------|
| Primary Accent | Red | `#E71A0F` | CTAs, links on hover, highlights, timeline markers |
| Primary Background | Navy | `#1A1A2E` | Hero backgrounds, main text, sidebars |
| White | White | `#FFFFFF` | Text on dark, card backgrounds, clean spaces |
| Secondary Gray | Gray | `#2D2D3F` | Subtle backgrounds, secondary text |
| Accent/Premium | Gold | `#D4A843` | VIP badges, premium features, special elements |
| Special Cases | Pink | `#FF69B4` | SweetBox product category (cinema-specific) |

### Status Colors

| Status | Color | Hex Code | Usage |
|--------|-------|----------|-------|
| Success | Green | `#4CAF50` | Success messages, checkmarks, confirmations |
| Error | Red | `#E71A0F` | Error messages, invalid inputs, alerts |
| Warning | Orange | `#FF9800` | Warning messages, attention-needed states |
| Info | Blue | `#2196F3` | Information messages, helpful hints |

### Accessibility Notes

- **Contrast Ratios**: All text meets WCAG AA minimum (4.5:1 for body text, 3:1 for large text)
- **Red #E71A0F on White #FFFFFF**: Contrast ratio 5.2:1 ✅ (accessible)
- **Navy #1A1A2E on White #FFFFFF**: Contrast ratio 12.6:1 ✅ (highly accessible)
- **Avoid** relying on color alone to convey meaning; always use icons, text, or patterns

### CSS Variable Usage

```css
:root {
    /* Brand Colors */
    --cgv-red: #E71A0F;
    --cgv-dark: #1A1A2E;
    --cgv-white: #FFFFFF;
    --cgv-gray: #2D2D3F;
    --cgv-gold: #D4A843;
    
    /* Status Colors */
    --status-success: #4CAF50;
    --status-error: #E71A0F;
    --status-warning: #FF9800;
    --status-info: #2196F3;
}
```

---

## Typography

### Font Stack

```css
:root {
    --font-primary: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    --font-display: 'Arial Black', Arial, sans-serif;
}
```

**Rationale**: System fonts provide excellent performance and familiarity across devices.

### Type Scale

| Element | Size | Weight | Line Height | Usage |
|---------|------|--------|-------------|-------|
| `<h1>` | 2.5rem (desktop) / 1.75rem (mobile) | 700 | 1.2 | Page titles, hero headlines |
| `<h2>` | 2rem (desktop) / 1.5rem (mobile) | 700 | 1.3 | Section headings |
| `<h3>` | 1.5rem | 600 | 1.4 | Subsection headings |
| `<h4>` | 1.25rem | 600 | 1.4 | Component headings |
| `<p>` | 0.95rem | 400 | 1.6 | Body text, paragraphs |
| `<small>` | 0.85rem | 400 | 1.5 | Fine print, metadata, captions |
| Labels | 0.9rem | 600 | 1.4 | Form labels, tags |

### Font Weights

- **400 (Regular)**: Body text, regular paragraphs
- **500 (Medium)**: Secondary headings, strong emphasis
- **600 (Semibold)**: Component headings, form labels
- **700 (Bold)**: Main headings, call-to-action text

### Letter Spacing

- Body text: `0.3px` (improve readability)
- Headings: `0px` (default, natural)
- Buttons: `0px` (natural spacing)

### Line Height

- Headings: `1.2` (tight)
- Body: `1.6` (comfortable reading)
- Forms: `1.4` (form inputs)

### Tailwind Typography Classes

When using Tailwind CSS, maintain the type scale:
```html
<!-- Headings -->
<h1 class="text-4xl font-bold leading-tight">Main Heading</h1>
<h2 class="text-3xl font-bold leading-snug">Section Heading</h2>
<h3 class="text-2xl font-semibold leading-snug">Subsection</h3>

<!-- Body -->
<p class="text-base font-normal leading-relaxed">Body text...</p>
<small class="text-sm font-normal">Fine print</small>
```

---

## Spacing & Layout

### Spacing Scale

All spacing should follow the 8px base grid:

```css
:root {
    --spacing-xs: 4px;    /* 0.25rem */
    --spacing-sm: 8px;    /* 0.5rem */
    --spacing-md: 16px;   /* 1rem */
    --spacing-lg: 24px;   /* 1.5rem */
    --spacing-xl: 32px;   /* 2rem */
    --spacing-2xl: 48px;  /* 3rem */
}
```

**Usage**:
- `--spacing-xs` / `--spacing-sm`: Small gaps between inline elements
- `--spacing-md`: Default padding/margin between sections
- `--spacing-lg`: Major section separation
- `--spacing-xl` / `--spacing-2xl`: Hero sections, large whitespace

### Margin & Padding Conventions

| Element | Margin | Padding |
|---------|--------|---------|
| Component | `24px` (top/bottom), `16px` (left/right) | Internal: `16px` |
| Card | `0` (margin), `16px` (padding) | For content separation |
| Section | `40px` (top), `40px` (bottom) | - |
| Hero Banner | `80px` (top), `80px` (bottom) | Tailwind: `py-20` |

### Layout Patterns

**Container Max-Width:**
```css
.container {
    max-width: 1200px;  /* max-w-6xl in Tailwind */
    margin: 0 auto;
    padding: 0 16px;    /* px-4 in Tailwind */
}
```

**Grid Layout (Flexbox):**
```html
<!-- Equal columns with gap -->
<div class="flex gap-4 flex-wrap">
    <div class="flex-1 min-w-[300px]">Card 1</div>
    <div class="flex-1 min-w-[300px]">Card 2</div>
</div>

<!-- Responsive grid (3 cols desktop, 1 col mobile) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>Card 1</div>
    <div>Card 2</div>
    <div>Card 3</div>
</div>
```

---

## Component Guidelines

### Navbar

**Design Specification:**
- **Position**: Sticky top (stays visible while scrolling)
- **Height**: 70px (accommodates logo + links)
- **Background**: White (#FFFFFF)
- **Link Color**: Navy (#1A1A2E) by default
- **Link Hover**: Red (#E71A0F)
- **Border Bottom**: 1px solid #E5E5E5
- **Z-Index**: 1030 (above other content)

**HTML Structure:**
```html
<header class="sticky top-0 z-50 bg-white border-b border-gray-200">
    <nav id="main-nav" class="mx-auto flex w-full max-w-6xl px-4 py-4 items-center justify-between">
        <!-- Logo/Brand -->
        <a href="/" class="text-lg font-bold text-gray-900">ABC Company</a>
        
        <!-- Navigation Links -->
        <div class="nav-links flex gap-4 items-center">
            <a href="/" class="text-gray-900 hover:text-red-600 transition">Home</a>
            <a href="/about" class="text-gray-900 hover:text-red-600 transition">About</a>
            <a href="/contact" class="text-gray-900 hover:text-red-600 transition">Contact</a>
            <a href="/login" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Login</a>
        </div>
    </nav>
</header>
```

**CSS:**
```css
#main-nav .nav-links a {
    color: #1A1A2E !important;
    transition: color 0.3s ease;
}

#main-nav .nav-links a:hover {
    color: #E71A0F !important;
}
```

### Buttons

**Button Types:**

1. **Primary Button** (Red CTA)
   - Background: #E71A0F
   - Text Color: White
   - Border: None
   - Padding: 12px 24px (medium size)
   - Hover: Background darkens to #C41408

2. **Secondary Button** (Navy)
   - Background: Transparent
   - Text Color: #1A1A2E
   - Border: 1px solid #1A1A2E
   - Padding: 12px 24px
   - Hover: Background changes to #1A1A2E, text to white

3. **Gold Button** (Premium/Special)
   - Background: #D4A843
   - Text Color: White
   - Border: None
   - Padding: 12px 24px
   - Hover: Background darkens

**Button Sizes:**
- **Small (sm)**: `8px 16px` padding, `0.85rem` font
- **Medium (md)**: `12px 24px` padding, `0.95rem` font (default)
- **Large (lg)**: `16px 32px` padding, `1rem` font

**Tailwind Button Classes:**
```html
<!-- Primary -->
<button class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 transition">
    Click Me
</button>

<!-- Secondary -->
<button class="bg-transparent border border-gray-900 text-gray-900 px-6 py-3 rounded hover:bg-gray-900 hover:text-white transition">
    Cancel
</button>

<!-- Disabled State -->
<button disabled class="bg-gray-300 text-gray-500 px-6 py-3 rounded cursor-not-allowed opacity-50">
    Disabled
</button>
```

### Cards

**Design Specification:**
- **Background**: White (#FFFFFF)
- **Border**: None (subtle shadow instead)
- **Border Radius**: 8px
- **Shadow**: `0 2px 8px rgba(0, 0, 0, 0.08)`
- **Padding**: 16px
- **Hover Effect**: Elevation (shadow increases, slight translateY -4px)

**HTML Structure:**
```html
<div class="bg-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300 p-4">
    <img src="image.jpg" alt="Card Image" class="w-full h-48 object-cover rounded-md mb-4" />
    <h3 class="text-lg font-semibold text-gray-900 mb-2">Card Title</h3>
    <p class="text-gray-600 text-sm mb-4">Card description...</p>
    <a href="#" class="text-red-600 font-medium hover:text-red-700 transition">Learn More →</a>
</div>
```

### Forms

**Input Fields:**
- **Border**: 1px solid #E5E5E5
- **Border Radius**: 8px
- **Padding**: 12px 16px
- **Focus**: Border changes to #E71A0F, blue outline ring
- **Font Size**: 0.95rem

**Label Styling:**
- **Font Weight**: 600 (semibold)
- **Font Size**: 0.9rem
- **Margin Bottom**: 8px
- **Color**: #1A1A2E

**Validation States:**
- **Valid**: Green border (#4CAF50), checkmark icon
- **Invalid**: Red border (#E71A0F), error message below
- **Disabled**: Gray background, cursor not-allowed

**HTML Form Example:**
```html
<form class="space-y-6">
    <div class="form-group">
        <label for="email" class="block font-semibold text-gray-900 mb-2">
            Email Address
        </label>
        <input 
            type="email" 
            id="email" 
            name="email"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent"
            placeholder="you@example.com"
            required
        />
        <small class="text-gray-500 mt-1 block">We'll never share your email.</small>
    </div>
    
    <div class="form-group">
        <label for="message" class="block font-semibold text-gray-900 mb-2">
            Message
        </label>
        <textarea 
            id="message" 
            name="message"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent"
            rows="4"
            required
        ></textarea>
    </div>
    
    <button type="submit" class="w-full bg-red-600 text-white font-semibold py-3 rounded-lg hover:bg-red-700 transition">
        Send Message
    </button>
</form>
```

### Links

**Default Link Styling:**
- **Color**: #E71A0F (red)
- **Text Decoration**: None (underline optional)
- **Hover**: Color darkens, slight underline appears
- **Visited**: Color remains red (no purple for visited)

```css
a {
    color: #E71A0F;
    text-decoration: none;
    transition: all 0.3s ease;
}

a:hover {
    color: #C41408;
    text-decoration: underline;
}

a:focus {
    outline: 2px solid #E71A0F;
    outline-offset: 2px;
}
```

### Timeline Component

**Usage**: For company history, milestones, or process flows

**Design:**
- **Line Color**: Red (#E71A0F)
- **Marker Size**: 16px diameter
- **Marker Border**: 3px red (#E71A0F)
- **Marker Background**: White
- **Layout**: Vertical (desktop), single column (mobile)

**HTML Structure:**
```html
<div class="timeline relative">
    <div class="timeline-item left">
        <h4 class="font-semibold text-gray-900 mb-2">2020</h4>
        <p class="text-gray-600">Company founded with a vision...</p>
    </div>
    
    <div class="timeline-item right">
        <h4 class="font-semibold text-gray-900 mb-2">2021</h4>
        <p class="text-gray-600">Expanded to 5 locations...</p>
    </div>
    
    <div class="timeline-item left">
        <h4 class="font-semibold text-gray-900 mb-2">2023</h4>
        <p class="text-gray-600">Reached 1 million customers...</p>
    </div>
</div>
```

### Accordion/Collapsible Sections

**Design:**
- **Header Background**: Light gray (#F9FAFB)
- **Header Padding**: 16px
- **Border**: 1px solid #E5E5E5
- **Border Radius**: 8px (top only when closed)
- **Icon**: Chevron pointing down (rotates on expand)
- **Animation**: Smooth height transition (0.3s)

**HTML Example:**
```html
<div class="accordion space-y-3">
    <details class="group border border-gray-300 rounded-lg overflow-hidden">
        <summary class="flex items-center justify-between px-4 py-4 bg-gray-50 cursor-pointer hover:bg-gray-100 font-semibold text-gray-900 group-open:bg-red-50">
            What is your return policy?
            <span class="transform group-open:rotate-180 transition-transform">▼</span>
        </summary>
        <p class="px-4 py-4 text-gray-600 bg-white">
            We offer 30 days money-back guarantee on all products...
        </p>
    </details>
    
    <details class="group border border-gray-300 rounded-lg overflow-hidden">
        <summary class="flex items-center justify-between px-4 py-4 bg-gray-50 cursor-pointer hover:bg-gray-100 font-semibold text-gray-900 group-open:bg-red-50">
            Do you offer shipping?
            <span class="transform group-open:rotate-180 transition-transform">▼</span>
        </summary>
        <p class="px-4 py-4 text-gray-600 bg-white">
            Yes, we ship worldwide with tracking...
        </p>
    </details>
</div>
```

---

## Interactive States

### Hover State

- **Visual Indicator**: Color change, slight shadow increase, or elevation
- **Duration**: 0.3s transition (smooth, not instant)
- **Guidelines**:
  - Links: Color to #E71A0F
  - Buttons: Background darkens or shadow increases
  - Cards: Shadow increases, slight translateY -4px
  - Images: Optional scale (1.05) or brightness adjustment

```css
a:hover {
    color: #E71A0F;
    transition: color 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    transform: translateY(-4px);
    transition: all 0.3s ease;
}
```

### Active/Current State

- **Navbar Link Active**: Red color (#E71A0F), bold font (600 weight)
- **Button Active**: Pressed-down appearance (darker background)
- **Accordion Active**: Section expanded, header background changes to red-tint

### Disabled State

- **Styling**:
  - Background: Gray (#D1D5DB)
  - Text Color: Gray (#6B7280)
  - Cursor: `not-allowed`
  - Opacity: 50% (optional)
  - No hover effects

```html
<button disabled class="bg-gray-300 text-gray-500 cursor-not-allowed opacity-50">
    Disabled Button
</button>
```

### Focus State (Keyboard Navigation)

- **Indicator**: 2px outline ring in red (#E71A0F)
- **Offset**: 2px outward from element
- **Applies To**: Buttons, links, form inputs
- **Required**: For WCAG accessibility compliance

```css
a:focus,
button:focus,
input:focus {
    outline: 2px solid #E71A0F;
    outline-offset: 2px;
}
```

### Loading State

- **Spinner Animation**: Rotating circle, red color
- **Duration**: 1 second per rotation
- **Position**: Inline next to text or center of button

```html
<button disabled class="flex items-center gap-2">
    <svg class="animate-spin h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    Loading...
</button>
```

---

## Responsive Design

### Mobile-First Approach

Design starts for mobile (320px width), then enhances for larger screens.

### Breakpoints

| Device | Width | Tailwind Prefix | Usage |
|--------|-------|-----------------|-------|
| Mobile | 320px - 767px | None (base) | Small phones, tablets in portrait |
| Tablet | 768px - 1023px | `md:` | Large phones, tablets |
| Desktop | 1024px+ | `lg:` | Desktop screens and above |

### Responsive Examples

**Responsive Typography:**
```html
<h1 class="text-2xl md:text-4xl font-bold">
    Responsive Heading
</h1>
```

**Responsive Grid:**
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="card">Product 1</div>
    <div class="card">Product 2</div>
    <div class="card">Product 3</div>
    <div class="card">Product 4</div>
</div>
```

**Responsive Navigation:**
```html
<nav class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <a href="/" class="text-lg font-bold">Logo</a>
    <div class="nav-links flex flex-col md:flex-row gap-4">
        <a href="/about">About</a>
        <a href="/contact">Contact</a>
    </div>
</nav>
```

### Touch Targets

- **Minimum Size**: 44px × 44px (for mobile users)
- **Padding**: Use adequate padding around clickable elements
- **Spacing**: Minimum 8px gap between touch targets

```html
<!-- Good: 44px height touch target -->
<button class="h-11 px-6 py-3 rounded">Touch Button</button>

<!-- Poor: Too small (26px) -->
<button class="h-6 px-2 text-xs rounded">Avoid This</button>
```

### Image Responsiveness

```html
<img 
    src="image.jpg" 
    alt="Descriptive alt text"
    class="w-full h-auto object-cover rounded-lg"
    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
/>
```

---

## Accessibility

### Color Contrast

All text must meet WCAG AA minimum contrast ratios:
- **Body Text**: 4.5:1 contrast ratio
- **Large Text** (18pt+): 3:1 contrast ratio

**Examples of Compliant Combinations:**
- Navy (#1A1A2E) on White: 12.6:1 ✅
- Red (#E71A0F) on White: 5.2:1 ✅
- Navy on Light Gray: 9.1:1 ✅

**Tool**: Use WebAIM Contrast Checker to verify: https://webaim.org/resources/contrastchecker/

### Semantic HTML

Always use semantic elements:
```html
<!-- Good: Semantic -->
<header>Navigation</header>
<nav>Links</nav>
<main>Content</main>
<article>News Item</article>
<section>Group of Content</section>
<aside>Sidebar</aside>
<footer>Footer</footer>

<!-- Poor: Non-semantic divs -->
<div class="header">Navigation</div>
<div class="content">Article</div>
```

### Alt Text

All images must have descriptive alt text:
```html
<!-- Good: Descriptive -->
<img src="team.jpg" alt="Leadership team of 5 people in office" />

<!-- Poor: Missing or vague -->
<img src="team.jpg" alt="team" />
<img src="hero.jpg" alt="image" />

<!-- Decorative: Use empty alt -->
<img src="decorative-line.png" alt="" aria-hidden="true" />
```

### ARIA Labels

Use ARIA for non-semantic interactions:
```html
<!-- Alert region -->
<div role="alert" class="bg-red-100 text-red-800 p-4 rounded">
    An error occurred while saving.
</div>

<!-- Button with icon only -->
<button aria-label="Close menu" onclick="closeMenu()">✕</button>

<!-- Landmark regions -->
<nav aria-label="Main navigation">Links</nav>
<nav aria-label="Breadcrumb">Home > Products > Item</nav>
```

### Focus Management

Ensure keyboard users can navigate:
```css
/* Always provide visible focus indicators */
button:focus,
a:focus,
input:focus {
    outline: 2px solid #E71A0F;
    outline-offset: 2px;
}

/* Don't remove outlines! */
*:focus {
    outline: none; /* ❌ DON'T DO THIS */
}
```

### Keyboard Navigation

All interactive elements must be keyboard accessible:
- **Tab**: Move to next element
- **Shift+Tab**: Move to previous element
- **Enter/Space**: Activate button
- **Escape**: Close modal/menu
- **Arrow Keys**: Navigate within custom components

```html
<!-- Good: Keyboard accessible -->
<button onclick="openMenu()">Menu</button>
<a href="/about">About</a>
<input type="text" placeholder="Search" />

<!-- Poor: Not keyboard accessible -->
<div onclick="openMenu()" role="button">Menu</div>
<span onclick="navigate('/about')">About</span>
```

### Text Sizing

- Allow users to zoom (don't set `user-scalable=no`)
- Use relative units (rem) not fixed pixels
- Ensure text remains readable at 200% zoom

```html
<!-- Good: Zoomable -->
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Poor: Not zoomable -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
```

---

## Code Examples

### Complete Navbar Component

```html
<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <nav id="main-nav" class="mx-auto flex w-full max-w-6xl px-4 py-4 items-center justify-between md:px-6 lg:px-8">
        <!-- Logo -->
        <a href="/" class="text-lg font-bold text-gray-900 hover:text-red-600 transition">
            ABC Company
        </a>
        
        <!-- Mobile Menu Button -->
        <button id="menu-toggle" class="md:hidden p-2" aria-label="Toggle menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Navigation Links -->
        <div class="nav-links hidden md:flex gap-4 items-center">
            <a href="/" class="text-gray-900 hover:text-red-600 transition font-medium">Home</a>
            <a href="/about" class="text-gray-900 hover:text-red-600 transition font-medium">About</a>
            <a href="/product" class="text-gray-900 hover:text-red-600 transition font-medium">Products</a>
            <a href="/contact" class="text-gray-900 hover:text-red-600 transition font-medium">Contact</a>
            <a href="/login" class="bg-red-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-700 transition">
                Login
            </a>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200">
        <a href="/" class="block px-4 py-3 text-gray-900 hover:bg-gray-100">Home</a>
        <a href="/about" class="block px-4 py-3 text-gray-900 hover:bg-gray-100">About</a>
        <a href="/product" class="block px-4 py-3 text-gray-900 hover:bg-gray-100">Products</a>
        <a href="/contact" class="block px-4 py-3 text-gray-900 hover:bg-gray-100">Contact</a>
        <a href="/login" class="block px-4 py-3 bg-red-600 text-white font-medium hover:bg-red-700">Login</a>
    </div>
</header>

<script>
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    
    toggle.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
```

### Complete Card Component

```html
<div class="bg-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
    <!-- Image -->
    <div class="overflow-hidden h-48">
        <img 
            src="product.jpg" 
            alt="Product Name"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
    </div>
    
    <!-- Content -->
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-red-600 transition">
            Product Name
        </h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
            Short description of the product...
        </p>
        
        <!-- Price -->
        <div class="flex items-center justify-between mb-4">
            <span class="text-2xl font-bold text-gray-900">$99.99</span>
            <span class="text-sm text-red-600 font-medium">Limited Offer</span>
        </div>
        
        <!-- CTA -->
        <button class="w-full bg-red-600 text-white font-semibold py-2 rounded-lg hover:bg-red-700 transition">
            Add to Cart
        </button>
    </div>
</div>
```

### Complete Contact Form

```html
<form class="bg-white rounded-lg shadow-md p-8 max-w-lg mx-auto space-y-6">
    <!-- Name Field -->
    <div>
        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
            Full Name
        </label>
        <input 
            type="text" 
            id="name" 
            name="name"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition"
            placeholder="John Doe"
        />
    </div>
    
    <!-- Email Field -->
    <div>
        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
            Email Address
        </label>
        <input 
            type="email" 
            id="email" 
            name="email"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition"
            placeholder="john@example.com"
        />
    </div>
    
    <!-- Message Field -->
    <div>
        <label for="message" class="block text-sm font-semibold text-gray-900 mb-2">
            Message
        </label>
        <textarea 
            id="message" 
            name="message"
            rows="5"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition resize-none"
            placeholder="Your message here..."
        ></textarea>
    </div>
    
    <!-- Checkbox -->
    <div class="flex items-center gap-2">
        <input type="checkbox" id="consent" name="consent" required class="w-4 h-4" />
        <label for="consent" class="text-sm text-gray-600">
            I agree to be contacted about my inquiry
        </label>
    </div>
    
    <!-- Submit Button -->
    <button type="submit" class="w-full bg-red-600 text-white font-semibold py-3 rounded-lg hover:bg-red-700 transition active:scale-95">
        Send Message
    </button>
</form>
```

---

## DO's and DON'Ts

### ✅ DO

- **Use the defined color palette** for all brand colors
- **Follow the spacing scale** (4px, 8px, 16px, 24px, etc.)
- **Test on mobile devices** before shipping
- **Include alt text** on every image
- **Provide focus indicators** for keyboard navigation
- **Use semantic HTML** (nav, main, article, section, etc.)
- **Maintain 4.5:1 contrast ratio** for body text
- **Design mobile-first**, then enhance for larger screens
- **Use CSS variables** for consistent styling
- **Optimize images** for web (compress, format, sizes)
- **Test with real users** including those with disabilities
- **Keep transitions smooth** (0.3s ease is standard)

### ❌ DON'T

- **Introduce new colors** without team consensus
- **Use fixed pixel widths** instead of relative units
- **Remove focus outlines** (accessibility violation)
- **Rely solely on color** to convey meaning (use icons, text)
- **Forget alt text** on images
- **Use disabled state styling** on elements that aren't disabled
- **Add animations** that can cause motion sickness (avoid flashing >3x/sec)
- **Set `user-scalable=no`** in viewport meta tag
- **Mix serif and sans-serif fonts** randomly
- **Ignore responsive breakpoints** — test at 768px and 1024px
- **Use generic image names** like "image1.jpg" (use descriptive names)
- **Add unnecessary decoration** that clutters the interface
- **Forget to test links** and buttons before deploying
- **Use inline styles** when CSS variables exist
- **Create multiple color names** for the same color (#E71A0F should always be `--cgv-red`)

---

## Tools & Resources

### Essential Tools

- **Tailwind CSS Docs**: https://tailwindcss.com/docs
- **WebAIM Contrast Checker**: https://webaim.org/resources/contrastchecker/
- **WAVE Accessibility Tool**: https://wave.webaim.org/
- **Color Hex Converter**: https://www.colorhexa.com/
- **Responsive Design Tester**: Chrome DevTools (F12)

### Recommended Browser Extensions

- **WCAG Color Contrast Checker**: Check color contrast on any website
- **Axe DevTools**: Automated accessibility testing
- **Lighthouse**: Built into Chrome DevTools (Ctrl+Shift+I)

### Testing Checklist

Before launching any page:
- [ ] Navbar styling is consistent across all pages
- [ ] All buttons have hover states
- [ ] All links are underlined or clearly clickable
- [ ] Color contrast meets WCAG AA (4.5:1 minimum)
- [ ] Images have descriptive alt text
- [ ] Form inputs have clear labels
- [ ] Can navigate entire page with Tab key
- [ ] Focus indicators are visible
- [ ] Mobile view is responsive at 768px
- [ ] No console errors or warnings
- [ ] Images are optimized (<100KB each)
- [ ] Text is readable (minimum 14px on mobile)
- [ ] Touch targets are 44px minimum
- [ ] No broken links
- [ ] Page loads in <3 seconds

### Contribution Guidelines

To update or expand these guidelines:
1. Make changes in a feature branch
2. Review with team lead
3. Update version number and date
4. Commit with message: "docs: update UI/UX guidelines"
5. Merge to main after approval

**Current Version**: 1.0.0
**Last Updated**: March 2026

---

## Questions?

For questions about these guidelines, please:
1. Check if the answer is in this document
2. Ask a team lead or senior developer
3. Open an issue in the project repository

Remember: **Consistency builds trust. Quality requires standards. Accessibility is non-negotiable.**
