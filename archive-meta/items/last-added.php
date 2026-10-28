<?php
/**
 * Last Added: when the newest entry of an archive, a term or another
 * post type was added, or last updated.
 *
 * @since 1.0
 */

add_filter( 'md_archive_meta_items', 'md_archive_meta_last_added_item' );

/**
 * Add Last Added to the Archive Meta items.
 *
 * @since 1.0
 */

function md_archive_meta_last_added_item( $items ) {
	$items['last_added'] = array(
		'title' => __( 'Last Added', 'md-sections' ),
		'placeholder' => __( 'added', 'md-sections' ),
		'icon' => 'calendar-alt',
		'meta_icon' => 'calendar',
		'color' => '#d44c3c',
		'singles' => true,
		'fields' => array(
			'source' => array( 'type' => 'text' ),
			'settings' => array(
				'type' => 'checkbox',
				'options' => array( 'updated', 'date' )
			)
		),
		'callback' => 'md_archive_meta_last_added',
		'admin_callback' => 'md_archive_meta_last_added_fields'
	);

	return $items;
}

/**
 * Format when the newest entry of an archive or term was added, or
 * last updated.
 *
 * @since 1.0
 */

function md_archive_meta_last_added( $fields, $post_type, $term = null ) {
	$source = md_archive_meta_source( $fields, $post_type );
	$term = md_archive_meta_term( $term );
	$updated = ! empty( $fields['settings']['updated'] );
	$args = array(
		'post_type' => $source ?: $post_type,
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'orderby' => $updated ? 'modified' : 'date',
		'order' => 'DESC',
		'fields' => 'ids'
	);

	if ( ! $source && $term )
		$args['tax_query'] = array( array(
			'taxonomy' => $term->taxonomy,
			'terms' => $term->term_id
		) );

	$posts = get_posts( $args );

	if ( ! $posts )
		return '';

	$time = $updated ? get_post_modified_time( 'U', false, $posts[0] ) : get_post_time( 'U', false, $posts[0] );
	$date = ! empty( $fields['settings']['date'] ) ? date_i18n( get_option( 'date_format' ), $time ) : sprintf( __( '%s ago', 'md-sections' ), human_time_diff( $time, current_time( 'U' ) ) );
	$label = ! empty( $fields['name'] ) ? $fields['name'] : ( $updated ? __( 'updated', 'md-sections' ) : __( 'added', 'md-sections' ) );

	// The whole date reads as the value, like a count's number

	return esc_html( $label ) . ' <strong>' . esc_html( $date ) . '</strong>';
}

/**
 * Render Last Added controls.
 *
 * @since 1.0
 */

function md_archive_meta_last_added_fields( $group, $type, $fields ) {
	md_archive_meta_source_field( $group, $fields );

	$fields->field( array( 'builder', $group, 'settings' ), array(
		'type' => 'checkbox',
		'label' => __( 'Settings', 'md-sections' ),
		'options' => array(
			'updated' => __( 'Use the last updated date', 'md-sections' ),
			'date' => __( 'Show the date instead of how long ago', 'md-sections' )
		)
	) );

	md_archive_meta_label_note();
}
