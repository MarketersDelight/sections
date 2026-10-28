<?php
/**
 * Run the Sections tests on the theme's inheritance test bootstrap, which
 * stubs WordPress and loads the theme code Sections builds on. Set
 * MD_THEME_DIR when the theme isn't beside md-dropins in wp-content.
 *
 * @since 1.0
 */

define( 'MD_TEST_THEME_DIR', getenv( 'MD_THEME_DIR' ) ?: dirname( __DIR__, 3 ) . '/themes/marketers-delight' );

require_once MD_TEST_THEME_DIR . '/tests/inheritance/bootstrap.php';
// WordPress functions the theme's stubs don't cover

if ( ! function_exists( 'post_type_exists' ) ) {
	function post_type_exists( $post_type ) {
		return isset( $GLOBALS['__test_post_types'][$post_type] );
	}
}

if ( ! function_exists( 'get_taxonomies' ) ) {
	function get_taxonomies() {
		return array( 'category' => 'category', 'post_tag' => 'post_tag' );
	}
}

require_once dirname( __DIR__ ) . '/archive-meta/functions.php';
require_once dirname( __DIR__ ) . '/archive-meta/items/post-count.php';
require_once dirname( __DIR__ ) . '/archive-meta/items/comment-count.php';
require_once dirname( __DIR__ ) . '/archive-meta/items/last-added.php';
require_once dirname( __DIR__ ) . '/archive-meta/items/text.php';
require_once dirname( __DIR__ ) . '/elements/query-loop.php';
require_once dirname( __DIR__ ) . '/elements/taxonomy-filter.php';
require_once dirname( __DIR__ ) . '/elements/section-title.php';
require_once dirname( __DIR__ ) . '/archive-meta/archive-meta.php';
