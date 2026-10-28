<?php
/*
 * Archive Meta settings and output: the builder on post type and term
 * screens, Archive Meta in titles, and stats offered as byline items.
 * The items themselves are in items/.
 *
 * @since 1.0
 */

class md_archive_meta extends md_api {

	/**
	 * Add Archive Meta to titles and bylines.
	 *
	 * @since 1.0
	 */

	public function actions() {
		add_filter( 'md_byline', array( $this, 'byline_items' ), 20 );
		add_filter( 'md_title_archive_meta', array( $this, 'title_meta' ), 10, 4 );
	}

	/**
	 * Add Archive Meta to the page title and category entry titles, keyed
	 * by title position. The page title skips it when Meta is set to
	 * Hide in Page Title.
	 *
	 * @since 1.0
	 */

	public function title_meta( $meta, $area, $term = null, $post_type = '' ) {
		if ( $area === 'archives' && md_module( array( 'archive_meta', 'display', 'hide_page_title' ) ) )
			return $meta;

		return array_merge( $meta, md_get_archive_meta( $area, $term, $post_type ) );
	}

	/**
	 * Offer site stats as byline items, so a single post or page can show
	 * the same counts an archive title does. Items opt in with singles.
	 *
	 * @since 1.0
	 */

	public function byline_items( $items ) {
		foreach ( md_archive_meta_items() as $type => $item )
			if ( ! empty( $item['singles'] ) && ! isset( $items[$type] ) )
				$items[$type] = array_merge( $item, array( 'template' => array( $this, 'byline_item' ) ) );

		return $items;
	}

	/**
	 * Render a stat as a byline item, with Archive Meta's value markup.
	 *
	 * @since 1.0
	 */

	public function byline_item( $fields ) {
		$type = $fields['builder_type'] ?? '';
		$items = md_archive_meta_items();

		if ( empty( $items[$type]['callback'] ) || ! is_callable( $items[$type]['callback'] ) )
			return;

		$value = call_user_func( $items[$type]['callback'], $fields, $fields['post_type'] ?? md_get_post_type() );

		if ( ! $value )
			return;

		$icon = ! empty( $items[$type]['meta_icon'] ) ? md_icon( $items[$type]['meta_icon'] ) : '';

		echo '<span class="' . md_byline_classes( $fields, 'byline-' . str_replace( '_', '-', $type ) ) . '">' . $icon . '<span class="byline-value">' . md_archive_meta_value( $value ) . '</span></span>';
	}

	/**
	 * Register Meta under the shared Archive settings group.
	 *
	 * @since 1.0
	 */

	public function register() {
		$this->name = __( 'Meta', 'md-sections' );

		$args = array(
			'name' => $this->name,
			'child_of' => array( 'archive', 'page_settings' ),
			'fields' => $this->fields()
		);

		return array(
			'admin_page' => $args,
			'term' => $args
		);
	}

	/**
	 * Build the storage schema from registered meta items.
	 *
	 * @since 1.0
	 */

	public function fields() {
		$fields = array(
			'builder_type' => array( 'type' => 'text' ),
			'builder_area' => array( 'type' => 'text' ),
			'name' => array( 'type' => 'text' ),
			'position' => array(
				'type' => 'select',
				'options' => array_keys( md_title_meta_positions() )
			),
			'scope' => array( 'type' => 'checkbox', 'options' => array( 'post_type_only', 'paged' ) )
		);

		foreach ( md_archive_meta_items() as $item )
			if ( ! empty( $item['fields'] ) )
				$fields = array_merge( $fields, $item['fields'] );

		return array(
			'builder' => array(
				'type' => 'builder',
				'fields' => $fields
			),
			'display' => array(
				'type' => 'checkbox',
				'options' => array( 'hide_page_title' )
			)
		);
	}

	/**
	 * Render the Archive Meta builder.
	 *
	 * @since 1.0
	 */

	public function admin_fields() {
		$post_type = $this->fields->screen_post_type();

		$scope = $this->_get_screen['is_admin'] && empty( $this->_get_screen['is_taxonomy'] ) && (bool) get_post_type_object( $post_type );

		echo '<div class="md-' . esc_attr( $this->_clean_id ) . ' md-tab-content active">';

		$this->fields->field( 'builder', array(
			'type' => 'builder',
			'title' => __( 'Add Archive Meta', 'md-sections' ),
			'description' => __( 'Choose the aggregate details shown in the archive title.', 'md-sections' ),
			'wrap_classes' => 'md-tabs',
			'active_tab' => 'archives',
			'tabs' => array(
				'archives' => array(
					'label' => __( 'Archive Title', 'md-sections' ),
					'context' => 'archives'
				),
				'category_entry' => array(
					'label' => __( 'Category Entry', 'md-sections' ),
					'context' => 'category_entry'
				)
			),
			'areas' => array(
				'archives' => array(
					'title' => __( 'Archive Title', 'md-sections' ),
					'description' => __( 'Drag items into order, and set where each one shows in the archive title.', 'md-sections' ),
					'tab' => 'archives'
				),
				'category_entry' => array(
					'title' => __( 'Category Entry', 'md-sections' ),
					'description' => __( 'Details about each category, shown in its title when the loop lists posts by category.', 'md-sections' ),
					'tab' => 'category_entry'
				)
			),
			'elements' => $this->elements( $post_type ),
			'scope' => (bool) $scope
		) );

		echo '<hr class="md-sep-small" />';

		$this->fields->field( 'display', array(
			'type' => 'checkbox',
			'options' => array(
				'hide_page_title' => __( 'Hide in Page Title', 'md-sections' )
			),
			'inherit' => array(
				'hide_page_title' => array(
					'on' => __( 'Hide in Page Title', 'md-sections' ),
					'off' => __( 'Show in Page Title', 'md-sections' )
				)
			),
			'description' => __( 'Hide from the main Page Title, and optionally add back in a <b>Section Title</b>.', 'md-sections' )
		) );

		echo '</div>';
	}

	/**
	 * Prepare meta items available to the current post type.
	 *
	 * @since 1.0
	 */

	private function elements( $post_type ) {
		$elements = md_archive_meta_items( $post_type );

		foreach ( $elements as $id => $element )
			$elements[$id]['admin_callback'] = array( $this, 'item_fields' );

		return $elements;
	}

	/**
	 * Render every item's Position, then the item's own controls.
	 *
	 * @since 1.0
	 */

	public function item_fields( $group, $type, $fields, $row = array() ) {
		$fields->field( array( 'builder', $group, 'position' ), array(
			'type' => 'select',
			'label' => __( 'Position', 'md-sections' ),
			'default' => 'after_title',
			'options' => md_title_meta_positions()
		) );

		$items = md_archive_meta_items( $fields->screen_post_type() );
		$callback = $items[$type]['admin_callback'] ?? null;

		if ( is_callable( $callback ) )
			call_user_func( $callback, $group, $type, $fields, $row );
		else
			md_archive_meta_label_note();
	}
}

new md_archive_meta;
