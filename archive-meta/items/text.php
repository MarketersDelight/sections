<?php
/**
 * Text: the item label as its whole value, like "Founded 2019".
 *
 * @since 1.0
 */

add_filter( 'md_archive_meta_items', 'md_archive_meta_text_item' );

/**
 * Add Text to the Archive Meta items.
 *
 * @since 1.0
 */

function md_archive_meta_text_item( $items ) {
	$items['text'] = array(
		'title' => __( 'Text', 'md-sections' ),
		'placeholder' => __( 'Founded 2019', 'md-sections' ),
		'icon' => 'editor-textcolor',
		'color' => '#646970',
		'singles' => true,
		'callback' => 'md_archive_meta_text',
		'admin_callback' => 'md_archive_meta_text_fields'
	);

	return $items;
}

/**
 * Print a Text item's own words.
 *
 * @since 1.0
 */

function md_archive_meta_text( $fields ) {
	return $fields['name'] ?? '';
}

/**
 * Explain the Text item, whose label is its whole value.
 *
 * @since 1.0
 */

function md_archive_meta_text_fields() {
	echo '<p class="description">' . esc_html__( 'Enter the text in the item label above. Numbers in it are emphasized like the other stats.', 'md-sections' ) . '</p>';
}
