<?php
/**
 * Drop-in Name: Sections
 * Description: Build archives and singles from Sections: Query Loops, Taxonomy Filters, Section Titles and Archive Meta.
 * Author: Alex, Kolakube
 * Author URI: https://marketersdelight.com/
 * Drop-in URI: https://marketersdelight.com/dropins/sections/
 * Drop-in Slug: sections
 * Text Domain: md-sections
 * Version: 1.0
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * Requires MD: 6.1
 */

class md_sections extends md_api {

	/**
	 * Load the modules. Items and elements register themselves, in the
	 * order they're listed in the Archive Meta and Sections pickers, and
	 * before other Drop-ins add theirs.
	 *
	 * @since 1.0
	 */

	public function includes() {
		require_once __DIR__ . '/archive-meta/functions.php';
		require_once __DIR__ . '/archive-meta/items/post-count.php';
		require_once __DIR__ . '/archive-meta/items/comment-count.php';
		require_once __DIR__ . '/archive-meta/items/last-added.php';
		require_once __DIR__ . '/archive-meta/items/text.php';
		require_once __DIR__ . '/elements/query-loop.php';
		require_once __DIR__ . '/elements/taxonomy-filter.php';
		require_once __DIR__ . '/elements/section-title.php';

		if ( is_admin() )
			require_once __DIR__ . '/settings.php';

		require_once __DIR__ . '/archive-meta/archive-meta.php';
		require_once __DIR__ . '/builder/archive.php';
		require_once __DIR__ . '/builder/single.php';
	}

	/**
	 * Add Sections styles to MD's compiled stylesheet.
	 *
	 * @since 1.0
	 */

	public function css( $templates ) {
		$templates['sections'] = md_css( 'dropins', 'sections/css', true );

		return $templates;
	}
}

new md_sections;
