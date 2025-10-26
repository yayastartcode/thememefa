# WordPress Widgets Guide

## Overview
The sidebar has been converted to use WordPress widgets system. This provides better flexibility and allows you to manage sidebar content through the WordPress admin panel.

## What Changed

### Files Created:
1. **sidebar.php** - Template file for the sidebar
2. **inc/widgets/class-popular-posts-widget.php** - Popular posts widget
3. **inc/widgets/class-categories-widget.php** - Categories list widget
4. **inc/widgets/class-advertisement-widget.php** - Advertisement widget

### Files Modified:
1. **functions.php** - Added widget registration and sidebar area
2. **index.php** - Replaced hardcoded sidebar with `get_sidebar()` call

## Available Widgets

### 1. Popular Posts Widget
Displays popular posts based on comment count.

**Settings:**
- Title: Widget title (default: "Popular Posts")
- Number of posts: How many posts to display (default: 5)

**Features:**
- Shows post thumbnail or gradient placeholder
- Displays post title (truncated to 8 words)
- Shows first category with red badge
- Numbered list (1-5)
- Hover effects

### 2. Categories List Widget
Displays categories with post counts and colored indicators.

**Settings:**
- Title: Widget title (default: "Categories")
- Number of categories: How many to display (default: 8)
- Order by: Post Count, Name, or Slug
- Order: Ascending or Descending

**Features:**
- Colored dots for each category
- Post count badges
- Hover effects
- Predefined colors for common categories (teknologi, ekonomi, politik, etc.)

### 3. Advertisement Widget
Displays advertisement banners with customizable sizes.

**Settings:**
- Title: Widget title (optional)
- Ad Type: Banner or Skyscraper
- Width: Banner width in pixels (default: 300)
- Height: Banner height in pixels (default: 250)
- Ad Code: HTML/JavaScript code for ads (Google AdSense, etc.)

**Features:**
- Shows placeholder when no ad code is provided
- Supports custom HTML/JavaScript ad codes
- Two visual styles: Banner (gray) and Skyscraper (blue)

## How to Use

### Adding Widgets to Sidebar:

1. Go to WordPress Admin Dashboard
2. Navigate to **Appearance > Widgets**
3. Find the **Sidebar** widget area
4. Drag and drop widgets from the left panel:
   - Popular Posts
   - Categories List
   - Advertisement
5. Configure each widget's settings
6. Click **Save**

### Recommended Setup:

For a layout similar to the original design, add widgets in this order:
1. **Popular Posts** (Title: "TERPOPULER", Number: 5)
2. **Advertisement** (Type: Banner, 300x250)
3. **Categories List** (Title: "KATEGORI", Number: 8)
4. **Advertisement** (Type: Skyscraper, 300x400)

## Customization

### Styling
All widgets use Tailwind CSS classes and match the existing design. The sidebar wrapper has the class `sidebar-right` and each widget is wrapped in a white card with rounded corners and shadow.

### Widget Wrapper Classes:
- `bg-white rounded-xl shadow-lg p-6 mb-6` - Applied to each widget
- Widget titles include an icon and blue underline border

### Modifying Widgets:
To customize widget behavior, edit the files in `inc/widgets/`:
- Modify the `widget()` method to change output
- Modify the `form()` method to add/remove settings
- Modify the `update()` method to handle new settings

## Benefits of Widget System

1. **Flexibility**: Easily add, remove, or reorder sidebar content
2. **No Code Changes**: Manage sidebar through WordPress admin
3. **Reusability**: Use the same widgets in multiple sidebars
4. **User-Friendly**: Non-developers can manage sidebar content
5. **Extensibility**: Easy to add new custom widgets

## Troubleshooting

### Widgets Not Showing:
1. Make sure you've added widgets to the "Sidebar" area in Appearance > Widgets
2. Check that `sidebar.php` exists in the theme root
3. Verify `get_sidebar()` is called in `index.php`

### Styling Issues:
- Ensure Tailwind CSS is properly loaded
- Check that `custom-utilities.css` is enqueued
- Verify widget wrapper classes match the design

### Widget Not Appearing in Admin:
- Check that widget files exist in `inc/widgets/`
- Verify `beritanih_register_widgets()` is called in `functions.php`
- Make sure widget classes are properly named

## Next Steps

You can now:
1. Add more custom widgets for specific needs
2. Create additional sidebar areas for other pages
3. Customize widget designs and functionality
4. Add widget-specific JavaScript if needed
