<?php

$items = array();
$anchor = '#' . ( md_section_anchor() ?: 'post-type-filter' );
$links = array( array(
	'url' => $term_url,
	'label' => __( 'All', 'md-sections' ),
	'count' => array_sum( $counts ),
	'active' => ! isset( $counts[$current_type] )
) );

foreach ( $counts as $type => $type_count ) {
	$type_object = get_post_type_object( $type );
	$links[] = array(
		'url' => add_query_arg( 'post_type', $type, $term_url ) . $anchor,
		'label' => $type_object->labels->name,
		'count' => $type_count,
		'active' => $type === $current_type
	);
}

foreach ( $links as $link )
	$items[] = '<a href="' . esc_url( $link['url'] ) . '" class="tag' . ( $link['active'] ? ' active' : '' ) . '"' . ( $link['active'] ? ' aria-current="page"' : '' ) . '>' . esc_html( $link['label'] ) . ' <span class="small has-muted-color">' . esc_html( number_format_i18n( $link['count'] ) ) . '</span></a>';

md_scroller_nav( array(
	'html' => 'nav',
	'id' => 'post-type-filter',
	'label' => __( 'Filter by post type', 'md-sections' ),
	'classes' => 'taxonomy-filter post-type-filter',
	'items' => $items
) );
