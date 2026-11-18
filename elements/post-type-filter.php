<?php
/**
 * Post Type Filter: on a term page shared by several post types, links to
 * all of them and to each post type alone, with counts.
 *
 * @since 1.2
 */

add_filter( 'md_archive_sections_builder_elements', 'md_post_type_filter_element' );

/**
 * Add Post Type Filter to the Sections elements.
 *
 * @since 1.2
 */

function md_post_type_filter_element( $elements ) {
	$elements['post_type_filter'] = array(
		'title' => __( 'Post Type Filter', 'md-sections' ),
		'placeholder' => __( 'Post Type Filter', 'md-sections' ),
		'icon' => 'filter',
		'color' => '#2271b1',
		'classes' => 'col-full',
		'callback' => 'md_post_type_filter'
	);

	return $elements;
}

/**
 * Render the post type links for the term being viewed, when its taxonomy
 * is shared by several post types (MD 6.2.4+).
 *
 * @since 1.2
 */

function md_post_type_filter( $fields, $post_type, $id, $main_query ) {
	$term = get_queried_object();

	if ( ! $term instanceof WP_Term || ! function_exists( 'md_taxonomy_post_types' ) )
		return;

	$counts = array();

	foreach ( md_taxonomy_post_types( $term->taxonomy ) as $type ) {
		$count = md_term_post_count( $term, $type );

		if ( $count )
			$counts[$type] = $count;
	}

	if ( count( $counts ) < 2 )
		return;

	$term_url = get_term_link( $term );
	$current_type = $main_query->query['post_type'] ?? '';
	$current_type = is_array( $current_type ) ? '' : sanitize_key( $current_type );

	include md_template( 'dropins', 'sections/post-type-filter', true );
}
