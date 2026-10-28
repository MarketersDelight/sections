<?php
/**
 * Tests md_get_archive_meta() builder areas: the archive title's items and
 * Category Entry items stay apart, Category Entry items measure the term
 * they're given, and the page title gets its items by position unless
 * Meta is set to Hide in Page Title.
 *
 * @since 1.0
 */

class ArchiveMetaTest extends MD_InheritanceTestCase {

	protected function setUp(): void {
		parent::setUp();

		md_test_set_post_type_object( 'post', 'Posts' );
		md_test_set_object_taxonomies( 'post', array( 'category' ) );
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archive_meta' => array( 'builder' => array(
				'title-count' => array( 'builder_type' => 'test_term', 'builder_area' => 'archives' ),
				'entry-count' => array( 'builder_type' => 'test_term', 'builder_area' => 'category_entry', 'position' => 'inside_title' )
			) )
		) ) );
		md_test_set_filter( 'md_archive_meta_items', array(
			'test_term' => array( 'callback' => 'md_test_archive_meta_term' )
		) );
	}

	public function test_hide_in_page_title_turns_off_archive_meta() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archives_title' => 'Blog',
			'archive_meta' => array(
				'builder' => array( 'count' => array( 'builder_type' => 'test_posts', 'builder_area' => 'archives' ) ),
				'display' => array( 'hide_page_title' => true )
			)
		) ) );
		md_test_set_filter( 'md_archive_meta_items', array(
			'test_posts' => array( 'icon' => '', 'callback' => 'md_test_archive_meta_posts' )
		) );

		md_test_set_filter( 'md_title_archive_meta', array( new md_archive_meta, 'title_meta' ) );

		$data = md_title_data( 'page' );

		$this->assertSame( array(), $data['archive_meta'] );
	}

	public function test_archive_meta_resolves_by_position() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archives_title' => 'Blog',
			'archive_meta' => array( 'builder' => array(
				'count' => array( 'builder_type' => 'test_posts', 'builder_area' => 'archives' ),
				'aside' => array( 'builder_type' => 'test_posts', 'builder_area' => 'archives', 'position' => 'byline_aside' ),
				'before' => array( 'builder_type' => 'test_posts', 'builder_area' => 'archives', 'position' => 'before_title' )
			) )
		) ) );
		md_test_set_filter( 'md_archive_meta_items', array(
			'test_posts' => array( 'icon' => '', 'callback' => 'md_test_archive_meta_posts' )
		) );

		md_test_set_filter( 'md_title_archive_meta', array( new md_archive_meta, 'title_meta' ) );

		$meta = md_title_data( 'page' )['archive_meta'];

		$this->assertSame( array( 'after_title', 'byline_aside', 'before_title' ), array_keys( $meta ), 'Items without a position are After Title' );
		$this->assertStringStartsWith( '<div class="byline after-title archive-meta">', $meta['after_title'] );
		$this->assertStringContainsString( '<strong>12</strong> posts', $meta['byline_aside'] );
	}

	public function test_areas_keep_their_own_items() {
		$title = md_get_archive_meta();
		$entry = md_get_archive_meta( 'category_entry', new WP_Term( array( 'term_id' => 3, 'taxonomy' => 'category', 'name' => 'Grief' ) ), 'post' );

		$this->assertSame( array( 'after_title' ), array_keys( $title ) );
		$this->assertSame( array( 'inside_title' ), array_keys( $entry ) );
	}

	public function test_category_entry_items_measure_the_given_term() {
		$entry = md_get_archive_meta( 'category_entry', new WP_Term( array( 'term_id' => 3, 'taxonomy' => 'category', 'name' => 'Grief' ) ), 'post' );

		$this->assertStringContainsString( 'Grief', $entry['inside_title'] );
	}

	public function test_category_entry_items_show_outside_archive_pages() {
		md_test_set_query( array( 'is_home' => false, 'is_singular' => true, 'post_type' => 'post', 'queried_object_id' => 1 ) );

		$entry = md_get_archive_meta( 'category_entry', new WP_Term( array( 'term_id' => 3, 'taxonomy' => 'category', 'name' => 'Grief' ) ), 'post' );

		$this->assertSame( array(), md_get_archive_meta(), 'The archive title area only runs on archives' );
		$this->assertArrayHasKey( 'inside_title', $entry );
	}

	public function test_plain_values_get_their_numbers_emphasized() {
		$this->assertSame( '<strong>1,204</strong> books', md_archive_meta_value( '1,204 books' ) );
	}

	public function test_values_with_markup_keep_their_own_emphasis() {
		$this->assertSame( 'last added <strong>2 weeks ago</strong>', md_archive_meta_value( 'last added <strong>2 weeks ago</strong>' ) );
		$this->assertSame( '<a href="https://example.test/"><strong>3</strong> posts</a>', md_archive_meta_value( '<a href="https://example.test/"><strong>3</strong> posts</a>' ) );
	}

	public function test_meta_links_to_another_page() {
		md_test_set_query( array( 'current_url' => 'https://example.test/' ) );

		$this->assertSame( '<a href="https://example.test/bookshelf/"><strong>446</strong> books</a>', md_archive_meta_link( '446 books', 'https://example.test/bookshelf/' ) );
	}

	public function test_meta_never_links_to_the_page_being_viewed() {
		md_test_set_query( array( 'current_url' => 'https://example.test/bookshelf/' ) );

		$this->assertSame( '446 books', md_archive_meta_link( '446 books', 'https://example.test/bookshelf/' ) );
		$this->assertSame( '446 books', md_archive_meta_link( '446 books', '' ) );
	}

}


function md_test_archive_meta_term( $fields, $post_type, $term = null ) {
	return $term ? 'posts in ' . $term->name : '12 posts';
}

function md_test_archive_meta_posts() {
	return '12 posts';
}
