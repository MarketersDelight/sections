<?php
/**
 * Comment Count: approved comments across an archive or term.
 *
 * @since 1.0
 */

add_filter( 'md_archive_meta_items', 'md_archive_meta_comment_count_item' );

/**
 * Add Comment Count to the Archive Meta items.
 *
 * @since 1.0
 */

function md_archive_meta_comment_count_item( $items ) {
	$items['comment_count'] = array(
		'title' => __( 'Comment Count', 'md-sections' ),
		'placeholder' => __( '{count} comments', 'md-sections' ),
		'icon' => 'admin-comments',
		'meta_icon' => 'chat',
		'color' => '#ff6000',
		'fields' => array(
			'singular_name' => array( 'type' => 'text' )
		),
		'callback' => 'md_archive_meta_comment_count',
		'admin_callback' => 'md_archive_meta_comment_count_fields'
	);

	return $items;
}

/**
 * Format the approved comment count for an archive or term.
 *
 * @since 1.0
 */

function md_archive_meta_comment_count( $fields, $post_type, $term = null ) {
	return md_count_text( md_archive_comment_total( $post_type, md_archive_meta_term( $term ) ), $fields, __( '{count} comment', 'md-sections' ), __( '{count} comments', 'md-sections' ) );
}

/**
 * Render Comment Count text controls.
 *
 * @since 1.0
 */

function md_archive_meta_comment_count_fields( $group, $type, $fields ) {
	$fields->field( array( 'builder', $group, 'singular_name' ), array(
		'type' => 'text',
		'label' => __( 'Singular Text', 'md-sections' ),
		'placeholder' => __( '{count} comment', 'md-sections' )
	) );

	echo '<p class="description">' . wp_kses_post( __( 'Use <code>{count}</code> in the text above to insert the number. The singular text is used only when the count is one.', 'md-sections' ) ) . '</p>';
}
