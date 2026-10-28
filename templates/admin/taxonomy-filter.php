<?php

if ( $options ) {
	$fields->field( array( 'builder', $group, 'taxonomy' ), array(
		'type' => 'select',
		'label' => __( 'Taxonomy', 'md-sections' ),
		'empty_label' => __( 'Auto-detect taxonomy', 'md-sections' ),
		'options' => $options
	) );

	$fields->field( array( 'builder', $group, 'taxonomy_settings' ), array(
		'type' => 'checkbox',
		'label' => __( 'Settings', 'md-sections' ),
		'options' => array(
			'hide_empty' => __( 'Hide empty terms', 'md-sections' )
		)
	) );

	$fields->field( array( 'builder', $group, 'all_label' ), array(
		'type' => 'text',
		'label' => __( 'All label', 'md-sections' ),
		'placeholder' => __( 'All', 'md-sections' )
	) );
}

else echo '<p>' . __( 'No public taxonomies are registered for this post type.', 'md-sections' ) . '</p>';