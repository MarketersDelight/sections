<div class="md-section-title md-tabs">

	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-section-title-content"><?php echo __( 'Content', 'md-sections' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-section-title-image"><?php echo __( 'Image', 'md-sections' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-section-title-cover"><?php echo __( 'Cover', 'md-sections' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-section-title-cta"><?php echo __( 'Call to action', 'md-sections' ); ?></a>
	</div>

	<div class="md-section-title-content md-tab-content active">

		<div class="md-section-title-field md-sep-micro">

			<div class="md-section-title-heading">
				<?php $fields->field( array( 'builder', $group, 'section_heading' ), array(
					'type' => 'select',
					'empty_label' => __( 'H2', 'md-sections' ),
					'options' => array(
						'h3' => __( 'H3', 'md-sections' )
					)
				) ); ?>
			</div>

			<?php $fields->field( array( 'builder', $group, 'section_title' ), array(
				'type' => 'text',
				'label' => __( 'Title', 'md-sections' )
			) ); ?>

		</div>

		<div class="md-sep-micro">
			<?php $fields->field( array( 'builder', $group, 'section_text' ), array(
				'type' => 'textarea',
				'label' => __( 'Text', 'md-sections' ),
				'rows' => 3
			) ); ?>
		</div>

		<div class="md-sep-micro">
			<?php $fields->field( array( 'builder', $group, 'section_display' ), array(
				'type' => 'checkbox',
				'options' => array(
					'archive_meta' => __( 'Show archive meta', 'md-sections' )
				),
				'description' => __( 'Shows the items set in the Meta tab.', 'md-sections' )
			) ); ?>
		</div>

	</div>

	<div class="md-section-title-image md-tab-content">
		<?php md_featured_media_admin( $media_fields, $media_prefix, array(
			'image_only' => true,
			'position_label' => __( 'Left, beside the title (default)', 'md-sections' ),
			'remove' => false
		) ); ?>
	</div>

	<div class="md-section-title-cover md-tab-content">

		<div class="columns-2 columns-single">

			<div class="col col1">
				<?php $fields->field( array( 'builder', $group, 'section_cover', 'photo' ), array(
					'type' => 'upload',
					'label' => __( 'Background image', 'md-sections' ),
					'upload_type' => 'media'
				) ); ?>
			</div>

			<div class="col col2">

				<div class="md-sep-small">
					<?php $fields->field( array( 'builder', $group, 'section_cover', 'bg_color' ), array(
						'type' => 'color',
						'label' => __( 'Background color', 'md-sections' )
					) ); ?>
				</div>

				<div class="md-sep-small">
					<?php $fields->field( array( 'builder', $group, 'section_cover', 'display' ), array(
						'type' => 'checkbox',
						'label' => __( 'Cover settings', 'md-sections' ),
						'options' => array(
							'alternate' => __( 'Use dark text', 'md-sections' ),
							'disable_overlay' => __( 'Remove overlay', 'md-sections' )
						)
					) ); ?>
				</div>

			</div>

		</div>

	</div>

	<div class="md-section-title-cta md-tab-content">
		<?php md_page_cta_admin( $cta_fields, $cta_prefix, false ); ?>
	</div>

</div>
