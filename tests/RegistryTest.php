<?php
/**
 * Tests that the built-in Sections elements and Archive Meta items register
 * themselves, in picker order, with callbacks that exist.
 *
 * @since 1.0
 */

class RegistryTest extends MD_InheritanceTestCase {

	public function test_elements_register_in_picker_order() {
		$elements = md_section_title_element( md_taxonomy_filter_element( md_query_loop_element( array() ) ) );

		$this->assertSame( array( 'query_loop', 'taxonomy_filter', 'section_title' ), array_keys( $elements ) );

		foreach ( $elements as $id => $element ) {
			$this->assertIsCallable( $element['callback'], "$id callback" );
			$this->assertIsCallable( $element['admin_callback'], "$id admin callback" );
			$this->assertSame( 'col-full', $element['classes'], "$id is full width" );
		}
	}

	public function test_items_register_in_picker_order() {
		$items = md_archive_meta_text_item( md_archive_meta_last_added_item( md_archive_meta_comment_count_item( md_archive_meta_post_count_item( array() ) ) ) );

		$this->assertSame( array( 'post_count', 'comment_count', 'last_added', 'text' ), array_keys( $items ) );

		foreach ( $items as $id => $item ) {
			$this->assertIsCallable( $item['callback'], "$id callback" );
			$this->assertIsCallable( $item['admin_callback'], "$id admin callback" );
		}
	}

	public function test_items_keep_other_registered_items() {
		$items = md_archive_meta_post_count_item( array( 'share_like_count' => array( 'title' => 'Total Likes' ) ) );

		$this->assertSame( array( 'share_like_count', 'post_count' ), array_keys( $items ) );
	}

	public function test_text_item_is_its_label() {
		$this->assertSame( 'Founded 2019', md_archive_meta_text( array( 'name' => 'Founded 2019' ) ) );
	}

	public function test_item_source_is_another_existing_post_type_only() {
		md_test_set_post_type_object( 'docs', 'Docs' );

		$this->assertSame( 'docs', md_archive_meta_source( array( 'source' => 'docs' ), 'post' ) );
		$this->assertSame( '', md_archive_meta_source( array( 'source' => 'post' ), 'post' ), 'The archive\'s own type is the default' );
		$this->assertSame( '', md_archive_meta_source( array(), 'post' ) );
	}
}
