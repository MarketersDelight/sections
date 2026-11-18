<?php
/**
 * Query Loop context, filters, sorting, and independent pagination.
 */

if ( ! class_exists( 'WP_Query' ) ) {
	class WP_Query {
		public $query;

		public function __construct( $query = array() ) {
			$this->query = $query;
		}
	}
}

if ( ! class_exists( 'md_fields_data' ) ) {
	class md_fields_data {
		public $values = array( 'featured_image' => array() );
	}
}

if ( ! function_exists( 'wp_unslash' ) ) {
	function wp_unslash( $value ) {
		return $value;
	}
}

if ( ! function_exists( 'sanitize_title' ) ) {
	function sanitize_title( $value ) {
		return strtolower( trim( $value ) );
	}
}

require_once MD_TEST_THEME_DIR . '/features/loop/category-loop.php';

class QueryLoopTest extends MD_InheritanceTestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['__test_post_types']['post'] = (object) array(
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'labels' => (object) array( 'name' => 'Blog' )
		);
		$GLOBALS['__test_object_taxonomies']['post'] = array( 'category' );
	}

	public function test_current_author_and_latest_are_distinct_contexts() {
		md_test_set_query( array( 'is_author' => true, 'queried_object_id' => 7 ) );

		$this->assertSame( array( 'author' => 7 ), md_query_loop_context( 'current' ) );
		$this->assertSame( array(), md_query_loop_context( 'latest' ) );
		$this->assertNull( md_query_loop_context( 'search' ) );
		$this->assertSame( 7, md_query_loop_query( array( 'source' => 'post' ), 'author' )->query['author'] );
	}

	public function test_query_loop_display_mode_includes_month_and_category_modes() {
		$this->assertSame( array( 'month', 'category', 'category_posts', 'post_type' ), md_query_loop_fields()['loop_type']['options'] );
		$this->assertSame( array( 'show_empty', 'hide_description' ), md_query_loop_fields()['category']['options'] );
	}

	public function test_category_query_limits_and_order_do_not_inherit_source_category_settings() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array(
				'posts_per_page' => 12,
				'posts_per_category' => 40,
				'category_per_page' => 25,
				'orderby' => 'title',
				'order' => 'ASC'
			)
		) ) );

		$query = md_query_loop_query( array(
			'source' => 'post',
			'loop_type' => 'category_posts',
			'category_per_page' => 3,
			'posts_per_category' => 4
		), 'author' );

		$this->assertSame( 4, $query->query['posts_per_page'] );
		$this->assertSame( 'date', $query->query['orderby'] );
		$this->assertSame( 'DESC', $query->query['order'] );
	}

	public function test_post_listing_uses_query_loop_defaults_not_source_archive_order_or_count() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'posts_per_page' => 30, 'orderby' => 'title', 'order' => 'ASC' )
		) ) );

		$query = md_query_loop_query( array( 'source' => 'post' ), 'independent' );

		$this->assertSame( 10, $query->query['posts_per_page'] );
		$this->assertSame( 'date', $query->query['orderby'] );
		$this->assertSame( 'DESC', $query->query['order'] );
	}

	private function add_stream() {
		$GLOBALS['__test_post_types']['stream'] = (object) array(
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'labels' => (object) array( 'name' => 'Stream' )
		);
		md_test_set_filter( 'md_post_type_meta', array( 'post', 'page', 'stream' ) );
	}

	public function test_post_type_filter_registers_beside_the_other_elements() {
		$elements = md_post_type_filter_element( array() );

		$this->assertSame( 'md_post_type_filter', $elements['post_type_filter']['callback'] );
	}

	public function test_post_type_filter_needs_a_term_page() {
		md_test_set_query( array( 'queried_object' => null ) );

		$this->assertNull( md_post_type_filter( array(), 'post', 'filter', null ) );
	}

	public function test_sources_read_checked_post_types_and_the_old_single_source() {
		$this->add_stream();

		$this->assertSame( array( 'post' ), md_query_loop_sources( array( 'source' => 'post' ) ) );
		$this->assertSame( array( 'post', 'stream' ), md_query_loop_sources( array( 'source' => array( 'post' => true, 'stream' => true ) ) ) );
		$this->assertSame( array( 'stream' ), md_query_loop_sources( array( 'source' => array( 'post' => 0, 'stream' => true, 'missing' => true ) ) ) );
		$this->assertSame( array(), md_query_loop_sources( array() ) );
	}

	public function test_several_sources_query_every_checked_post_type() {
		$this->add_stream();

		$query = md_query_loop_query( array( 'source' => array( 'post' => true, 'stream' => true ) ), 'mixed' );

		$this->assertSame( array( 'post', 'stream' ), $query->query['post_type'] );
		$this->assertNull( md_query_loop_query( array( 'source' => array() ), 'mixed' ) );
	}

	public function test_a_taxonomy_filter_keeps_only_sources_that_use_it() {
		$this->add_stream();

		$query = md_query_loop_query( array( 'source' => array( 'post' => true, 'stream' => true ), 'taxonomy' => 'category' ), 'mixed' );

		$this->assertSame( array( 'post' ), $query->query['post_type'] );

		$GLOBALS['__test_object_taxonomies']['stream'] = array( 'category' );
		$query = md_query_loop_query( array( 'source' => array( 'post' => true, 'stream' => true ), 'taxonomy' => 'category' ), 'mixed' );

		$this->assertSame( array( 'post', 'stream' ), $query->query['post_type'] );
	}

	public function test_taxonomy_without_terms_filters_to_assigned_posts() {
		$query = md_query_loop_query( array( 'source' => 'post', 'taxonomy' => 'category' ), 'blog' );

		$this->assertSame( array( 'taxonomy' => 'category', 'operator' => 'EXISTS' ), $query->query['tax_query'][0] );
	}

	public function test_term_slugs_filter_the_selected_taxonomy() {
		$query = md_query_loop_query( array(
			'source' => 'post',
			'taxonomy' => 'category',
			'terms' => 'news, updates'
		), 'blog' );

		$this->assertSame( array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( 'news', 'updates' ) ), $query->query['tax_query'][0] );
		$this->assertNull( md_query_loop_query( array( 'source' => 'post', 'taxonomy' => 'unrelated' ), 'blog' ) );
	}

	public function test_search_uses_relevance_until_sort_is_selected() {
		md_test_set_query( array( 'is_search' => true, 'query_var' => array( 's' => 'hello' ) ) );
		$query = md_query_loop_query( array( 'source' => 'post' ), 'blog' );
		$this->assertArrayNotHasKey( 'orderby', $query->query );

		$query = md_query_loop_query( array( 'source' => 'post', 'orderby' => 'title' ), 'blog' );
		$this->assertSame( 'title', $query->query['orderby'] );
	}

	public function test_random_query_order_falls_back_to_date_when_paginated() {
		$query = md_query_loop_query( array( 'source' => 'post', 'orderby' => 'rand', 'pagination' => 'page_numbers' ), 'blog' );

		$this->assertSame( 'date', $query->query['orderby'] );
	}

	public function test_month_grouping_is_opt_in_for_query_loop() {
		$query = md_query_loop_query( array( 'source' => 'post' ), 'blog' );
		$this->assertSame( 'date', $query->query['orderby'] );

		$query = md_query_loop_query( array( 'source' => 'post', 'loop_type' => 'month' ), 'blog' );
		$this->assertSame( 'date', $query->query['orderby'] );
	}

	public function test_query_loop_reads_its_own_page_number() {
		$previous_page = $_GET['md_loop_blog'] ?? null;
		$_GET['md_loop_blog'] = 2;

		$query = md_query_loop_query( array(
			'source' => 'post',
			'pagination' => 'page_numbers'
		), 'blog' );

		if ( $previous_page === null )
			unset( $_GET['md_loop_blog'] );
		else
			$_GET['md_loop_blog'] = $previous_page;

		$this->assertSame( 2, $query->query['paged'] );
	}
}
