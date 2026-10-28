<?php
/**
 * Archive Meta functions: the item registry, and how items render in
 * archive titles and category entry titles.
 *
 * @since 1.0
 */

/**
 * Get list of items that can be used in Archive Meta, optionally
 * limited to items available for a post type.
 *
 * @since 1.0
 */

function md_archive_meta_items( $post_type = null ) {
	$items = apply_filters( 'md_archive_meta_items', array(), $post_type );

	return $post_type === null ? $items : md_builder_items_for( $items, $post_type );
}

/**
 * Emphasize the numbers in a plain text Archive Meta value. A value with
 * markup marks its own numbers, like a link or a strong date, so it's
 * left as it is.
 *
 * @since 1.0
 */

function md_archive_meta_value( $value ) {
	$value = wp_kses_post( $value );

	return strpos( $value, '<' ) === false ? preg_replace( '/\d[\d,.]*/', '<strong>$0</strong>', $value ) : $value;
}

/**
 * Link an Archive Meta value to a page, unless it's the page being
 * viewed. Numbers inside the link are emphasized here, since values
 * only get that outside markup.
 *
 * @since 1.0
 */

function md_archive_meta_link( $text, $url ) {
	if ( ! $url || is_wp_error( $url ) )
		return $text;

	$current = untrailingslashit( strtok( get_pagenum_link( 1, false ), '?#' ) );

	if ( untrailingslashit( strtok( $url, '?#' ) ) === $current )
		return $text;

	return '<a href="' . esc_url( $url ) . '">' . md_archive_meta_value( $text ) . '</a>';
}

/**
 * Get Archive Meta markup for a builder area, keyed by the title position
 * each item is placed in. Items without a position are After Title. The
 * archives area measures the current archive; category_entry measures
 * the given term, with the settings of the post type its loop lists.
 *
 * @since 1.0
 */

function md_get_archive_meta( $area = 'archives', $term = null, $post_type = '' ) {
	if ( $area === 'archives' && ! is_home() && ! is_archive() )
		return array();

	$post_type = $post_type ?: md_get_post_type();
	$builder = md_get_post_type_builder( 'archive_meta', $post_type );
	$items = md_archive_meta_items( $post_type );
	$positions = $meta = array();

	foreach ( $builder as $fields ) {
		if ( md_builder_row_hidden( $fields ) )
			continue;

		if ( ( $fields['builder_area'] ?? '' ) !== $area )
			continue;

		$type = $fields['builder_type'] ?? '';
		$callback = $items[$type]['callback'] ?? null;

		if ( ! is_callable( $callback ) )
			continue;

		$value = call_user_func( $callback, $fields, $post_type, $term );

		if ( ! $value )
			continue;

		$position = ! empty( $fields['position'] ) ? $fields['position'] : 'after_title';

		if ( ! isset( $positions[$position] ) )
			$positions[$position] = '';

		$icon = ! empty( $items[$type]['meta_icon'] ) ? md_icon( $items[$type]['meta_icon'] ) : '';

		$positions[$position] .= '<span class="byline-item">' . $icon . '<span class="byline-value">' . md_archive_meta_value( $value ) . '</span></span>';
	}

	foreach ( $positions as $position => $output )
		$meta[$position] = '<div class="byline ' . esc_attr( str_replace( '_', '-', $position ) ) . ' archive-meta">' . $output . '</div>';

	return $meta;
}

/**
 * Explain the shared label override available to meta items.
 *
 * @since 1.0
 */

function md_archive_meta_label_note() {
	echo '<p class="description">' . esc_html__( 'Use the item label above to override its default text.', 'md-sections' ) . '</p>';
}

/**
 * Choose which post type an item counts. Empty counts the archive's
 * own posts.
 *
 * @since 1.0
 */

function md_archive_meta_source_field( $group, $fields ) {
	$fields->field( array( 'builder', $group, 'source' ), array(
		'type' => 'select',
		'label' => __( 'Post Type', 'md-sections' ),
		'empty_label' => __( 'This post type', 'md-sections' ),
		'options' => md_post_type_options()
	) );
}

/**
 * Return an item's chosen post type when it isn't the archive's own.
 *
 * @since 1.0
 */

function md_archive_meta_source( $fields, $post_type ) {
	$source = sanitize_key( $fields['source'] ?? '' );

	return $source && $source !== $post_type && post_type_exists( $source ) ? $source : '';
}

/**
 * The term an item measures: the given one, or the term archive
 * being viewed.
 *
 * @since 1.0
 */

function md_archive_meta_term( $term = null ) {
	if ( $term instanceof WP_Term )
		return $term;

	$queried = get_queried_object();

	return $queried instanceof WP_Term ? $queried : null;
}
