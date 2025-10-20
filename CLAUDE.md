# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**beritanih** is a custom WordPress news/blog theme (v0.1.0) built on Underscores with Tailwind CSS v4.1.13. It features a modern, responsive design with interactive components like hero sliders, breaking news tickers, and dark mode support.

**Requirements:** WordPress 6.8+, PHP 7.4+

## Build & Development Commands

### Setup
The theme uses **esbuild** for JavaScript bundling. Source files are located in a `javascript` directory (referenced in `/js/readme.txt`). Minified output goes to `script.min.js` and `block-editor.min.js`.

```bash
# Build production bundle (creates zip with versioning)
npm run bundle

# Development workflow
npm run build      # Compile assets
npm run watch      # Watch mode during development
```

### Asset Compilation
- **CSS:** Tailwind CSS v4.1.13 compiled to `css/style.css` (64 KB)
- **JavaScript:** esbuild minification to `css/script.min.js` and `css/block-editor.min.js`
- **Custom utilities:** `css/custom-utilities.css` loaded after main styles to prevent Tailwind overwrites

### Browser Testing
The theme supports:
- Modern browsers via Tailwind CSS v4
- Dark mode via CSS custom properties and localStorage
- Mobile browsers (iOS Safari, Chrome Mobile)

## Theme Architecture

### Directory Structure

**Root Templates** - WordPress template hierarchy
- `index.php` - Homepage with hero slider (43 KB)
- `single.php`, `page.php`, `archive.php`, `search.php`, `404.php` - Standard WordPress templates
- `header.php`, `footer.php` - Global wrapper templates
- `comments.php` - Comment display/form

**Template Parts** - Modular, reusable components
- `template-parts/layout/` - Header/footer layouts
- `template-parts/content/` - Content templates (single post, excerpt, page, none-state)

**Core Functions**
- `functions.php` - Theme setup, enqueue assets, register menus, WordPress features
- `inc/template-tags.php` - Reusable template tag functions (post date, author, metadata)
- `inc/template-functions.php` - Enhanced display logic (archive titles, comment forms, thumbnails)

**Styling**
- `css/style.css` - Main Tailwind + theme styles
- `css/style-editor.css` - Block Editor styling
- `css/custom-utilities.css` - Custom utility classes

**JavaScript**
- `js/script.min.js` - Frontend interactions (theme toggle, navigation, sliders)
- `js/block-editor.min.js` - WordPress Block Editor enhancements

### Key Theme Configuration

**functions.php Constants**
- `BERITANIH_VERSION` - Current version (0.1.0)
- `BERITANIH_TYPOGRAPHY_CLASSES` - Prose typography utilities (applied to post content)

**theme.json (Block Editor Config)**
- Color palette: Background, Foreground, Primary (red), Secondary (green), Tertiary (blue)
- Content sizes: 40rem (standard), 60rem (wide)
- Font: Inter (9 weights: 100-900 from Google Fonts)

**Navigation Menus**
- `menu-1` - Primary navigation
- `menu-2` - Footer menu
- `menu-pages` - Pages menu

### Feature Breakdown

**Header Component** (`template-parts/layout/header-content.php` - 390+ lines)
- Responsive navigation with mobile hamburger menu
- Search overlay (mobile-optimized)
- Breaking news ticker (animated, rotates every 4 seconds)
- Theme toggle (dark/light mode via localStorage)
- Trending tags display

**Hero Slider** (index.php)
- Displays 4 latest posts in carousel format
- Partial overflow effect for visual interest

**Post Display**
- Template tags handle consistent date/author/category/tag formatting
- Comment count and avatar display utilities
- Custom continue reading links

**Mobile Responsive**
- Tailwind CSS breakpoints for all screen sizes
- Mobile-first approach
- Touch-friendly navigation and interactive elements

## Code Patterns & Best Practices

### WordPress Integration Points
- **Block Editor:** Custom typography classes via `theme.json`, editor-specific styles in `style-editor.css`
- **Classic Editor:** TinyMCE customization via `inc/template-functions.php`
- **Widget Areas:** Footer sidebar widget support
- **Menus:** Register via `register_nav_menus()`
- **i18n:** Text domain `beritanih` for translations

### Styling Approach
- **Utility-first:** Tailwind CSS classes for layouts
- **Component classes:** `.two-column-layout`, `.content-left`, `.sidebar-right` for complex structures
- **CSS variables:** Color palette in OKLCH color space
- **Responsive:** Mobile-first with Tailwind breakpoints

### JavaScript Conventions
- Vanilla JavaScript (no jQuery)
- Event delegation for dynamic content
- Modular file structure split between frontend and editor scripts
- LocalStorage for persistent user preferences

### Template Tag Functions
All post/comment display functions follow naming convention `beritanih_*()`:
- `beritanih_posted_on()` - Date/time display
- `beritanih_posted_by()` - Author information
- `beritanih_entry_meta()` - Post metadata
- `beritanih_comment_count()` - Comments counter

## Development Workflow

1. **Making CSS changes:** Edit Tailwind classes in template files. Rebuild with `npm run bundle` for production.
2. **Adding PHP functionality:** Add template tags to `inc/template-tags.php` or functions to `inc/template-functions.php` for reusability.
3. **Modifying layouts:** Update template parts in `template-parts/` for component-level changes, or root templates for page-level changes.
4. **JavaScript enhancements:** Update source files (in `javascript/` directory), rebuild with esbuild.
5. **Block Editor customization:** Modify `theme.json` for design system, `inc/template-functions.php` for form customization, `css/style-editor.css` for styling.

## Asset Versioning

- Theme version defined in `functions.php` as `BERITANIH_VERSION = '0.1.0'`
- Used for cache busting: `wp_enqueue_style('beritanih-style', ..., [], BERITANIH_VERSION)`
- Production build converts version to base36 timestamp

## Important Notes

- **Minified files are generated:** Do not edit `script.min.js` or `block-editor.min.js` directly; modify source files and rebuild.
- **Tailwind custom utilities:** Loaded separately in `custom-utilities.css` after main styles to prevent build output from overwriting custom rules.
- **Font loading:** Inter is loaded from Google Fonts (9 weights) in header
- **Post excerpt display:** Uses template-parts system for consistent styling across archive/search/related posts
- **Color system:** Uses OKLCH for modern color space support; five-color palette defined in `theme.json`
