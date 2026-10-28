# Sections

A [Marketers Delight](https://marketersdelight.com/) drop-in that builds your archive and single pages from reusable sections. Drag Query Loops, Taxonomy Filters and Section Titles before or after the content and sidebar, and show Archive Meta stats like post counts in archive and category titles.

## Features

- **Sections** in four areas: Before Content, After Content, Before Sidebar and After Sidebar
  - **Archives**: sections around a post type's archive, its categories and the author archive
  - **Single**: sections around every post of a post type, and a **Sections** tab on each post to replace them
  - Neighboring sections share a row, which scrolls sideways on phones
  - Hide a section on category pages, or on page 2 and beyond
- **Query Loop**: list posts from any post type, with their own count, order, categories, terms, layout and pagination
- **Taxonomy Filter**: links to a taxonomy's terms, with post counts, for filtering an archive
- **Section Title**: a title, text, image, cover and call to action, built like MD's Page Title
- **Archive Meta**: Post Count, Comment Count, Last Added and Text items, in the archive title or in each category's title when the loop lists posts by category
  - Each item takes a title position: Before Title, Inside Title, After Title or Title Aside
  - Post Count, Last Added and Text can also be added to bylines on single posts
- Styles are added to MD's compiled stylesheet

Works with other drop-ins when they're active:
- **Bookshelf**: adds Highlight Spotlight and Most Highlights sections, and a Total Highlights Archive Meta item
- **Share**: adds a Total Likes Archive Meta item

## Settings

**On each post type's MD settings page**, in the **Sections** box:

- **Meta**: the Archive Meta items for the archive title and category entries, and **Hide in Page Title** to show them only in a Section Title
- **Archives**: the sections around the archive
- **Single**: the sections around each post

**On each category or term**: its own **Sections** box, which replaces the post type's sections and Meta.

**On each post**: the **Sections** tab in MD's page settings, which replaces the post type's Single sections in the areas you fill.

## Developers

- `md_archive_sections_builder_elements`: add section types. Arguments: `$elements`. Each element takes `title`, `icon`, `color`, `fields`, `callback` and `admin_callback`; set `classes` to `col-full` for a full-width element.
- `md_archive_meta_items`: add Archive Meta items. Arguments: `$items`, `$post_type`. Set `singles` to `true` to offer the item in bylines.
- The built-in sections (`elements/`) and Archive Meta items (`archive-meta/items/`) are added through these same filters, one file each, so they're working examples.
- Sections fills the theme's `md_title_archive_meta` filter, which adds Archive Meta to titles.
- Taxonomy Filter links use the `filter` query variable.

## Requirements

Sections, Query Loops and Archive Meta shipped inside the MD theme from MD 6.0 until they moved to this drop-in in MD 6.1. Saved sections carry over as they are.

- Marketers Delight 6.1 or later
- WordPress 6.2 or later
- PHP 7.4 or later

## Install

1. Download the latest `sections-x.y.z.zip` from the [Releases page](https://github.com/MarketersDelight/sections/releases).
2. In WordPress, go to MD's Drop-ins screen, click **Add new**, and upload the zip.
3. Activate Sections, then open any post type's MD settings and find the **Sections** box.

Don't use **Code → Download ZIP**. That zip includes development files and a folder name that MD won't recognize.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
