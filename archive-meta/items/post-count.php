<?php
/**
 * Post Count: published posts in an archive, a term (with its child
 * terms), or another post type.
 *
 * @since 1.0
 */

add_filter( 'md_archive_meta_items', 'md_archive_meta_post_count_item' );

/**
 * Add Post Count to the Archive Meta items.
 *
 * @since 1.0
 */

function md_archive_meta_post_count_item( $items ) {
	$items['post_count'] = array(
		'title' => __( 'Post Count', 'md-sections' ),
		'placeholder' => __( '{count} posts', 'md-sections' ),
		'icon' => 'admin-post',
		'meta_icon' => 'file',
		'color' => '#2271b1',
		'singles' => true,
		'fields' => array(
			'singular_name' => array( 'type' => 'text' ),
			'source' => array( 'type' => 'text' )
		),
		'callback' => 'md_archive_meta_post_count',
		'admin_callback' => 'md_archive_meta_post_count_fields'
	);

	return $items;
}

/**
 * Format the published post count for an archive or term.
 *
 * @since 1.0
 */

function md_archive_meta_post_count( $fields, $post_type, $term = null ) {
	$source = md_archive_meta_source( $fields, $post_type );
	$term = md_archive_meta_term( $term );

	$count_type = $term && ! $source ? md_archive_meta_term_post_type( $term, $post_type ) : '';
	$shared = $term && ! $source && function_exists( 'md_is_shared_taxonomy' ) && md_is_shared_taxonomy( $term->taxonomy );

	// A shared term without custom text counts each post type in its own words

	if ( $shared && empty( $fields['name'] ) && $count_type === '' )
		return md_archive_meta_type_counts( $term );

	if ( $source )
		$count = (int) wp_count_posts( $source )->publish;
	elseif ( $term )
		$count = md_term_post_count( $term, $count_type );
	else
		$count = (int) wp_count_posts( $post_type )->publish;

	if ( $source )
		$url = get_post_type_archive_link( $source );
	elseif ( $term )
		$url = get_term_link( $term );
	else
		$url = get_post_type_archive_link( $post_type );

	if ( $shared && empty( $fields['name'] ) )
		$text = md_archive_meta_type_count_text( $count, $count_type );
	else
		$text = md_count_text( $count, $fields, __( '{count} post', 'md-sections' ), __( '{count} posts', 'md-sections' ) );

	return md_archive_meta_link( $text, $url );
}

/**
 * Each post type's count on a shared term, ex: 104 articles · 49 videos.
 *
 * @since 1.2
 */

function md_archive_meta_type_counts( $term ) {
	$parts = array();

	foreach ( md_taxonomy_post_types( $term->taxonomy ) as $type ) {
		$count = md_term_post_count( $term, $type );

		if ( $count )
			$parts[] = md_archive_meta_type_count_text( $count, $type );
	}

	return join( ' &middot; ', $parts );
}

/**
 * A count in its post type's own label, ex: 1 video, 49 videos.
 *
 * @since 1.2
 */

function md_archive_meta_type_count_text( $count, $post_type ) {
	$labels = get_post_type_object( $post_type )->labels;

	return md_count_text( $count, array(), '{count} ' . strtolower( $labels->singular_name ), '{count} ' . strtolower( $labels->name ) );
}

/**
 * The post type a term's count covers. A term shared by several post types
 * counts all of them, or the one a link narrows its page to (?post_type=).
 *
 * @since 1.2
 */

function md_archive_meta_term_post_type( $term, $post_type ) {
	$requested = get_query_var( 'post_type' );

	if ( ! function_exists( 'md_is_shared_taxonomy' ) || ! md_is_shared_taxonomy( $term->taxonomy ) )
		$count_type = $post_type;
	elseif ( is_string( $requested ) && $requested && get_queried_object_id() === $term->term_id )
		$count_type = $requested;
	else
		$count_type = '';

	return $count_type;
}

/**
 * Render Post Count text controls.
 *
 * @since 1.0
 */

function md_archive_meta_post_count_fields( $group, $type, $fields ) {
	md_archive_meta_source_field( $group, $fields );

	$fields->field( array( 'builder', $group, 'singular_name' ), array(
		'type' => 'text',
		'label' => __( 'Singular Text', 'md-sections' ),
		'placeholder' => __( '{count} post', 'md-sections' )
	) );

	echo '<p class="description">' . wp_kses_post( __( 'Use <code>{count}</code> in the text above to insert the number. The singular text is used only when the count is one.', 'md-sections' ) ) . '</p>';
}
