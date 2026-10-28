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

	if ( $source )
		$count = (int) wp_count_posts( $source )->publish;
	elseif ( $term )
		$count = md_term_post_count( $term, $post_type );
	else
		$count = (int) wp_count_posts( $post_type )->publish;

	if ( $source )
		$url = get_post_type_archive_link( $source );
	elseif ( $term )
		$url = get_term_link( $term );
	else
		$url = get_post_type_archive_link( $post_type );

	return md_archive_meta_link( md_count_text( $count, $fields, __( '{count} post', 'md-sections' ), __( '{count} posts', 'md-sections' ) ), $url );
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
