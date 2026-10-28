<?php
$display_mode = $row['loop_type'] ?? '';
$default_items = get_option( 'posts_per_page' ) ?: 10;

// Which display modes show which fields, matching their md-conditional-* classes

$shows_items = in_array( $display_mode, array( '', 'month' ), true );
$shows_order = $display_mode !== 'category';
$shows_category = in_array( $display_mode, array( 'category', 'category_posts' ), true );
?>
<div class="md-query-loop-fields">
	<div class="md-query-loop-section">
		<h4><?php echo esc_html__( 'Query', 'md-sections' ); ?></h4>
		<p class="description"><?php echo esc_html__( 'Choose what to query and how it relates to the current page.', 'md-sections' ); ?></p>
		<div class="md-query-loop-grid md-full-select">
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'source' ), array(
					'type' => 'select',
					'value' => $row['source'] ?? '',
					'label' => __( 'Content type', 'md-sections' ),
					'description' => __( 'Choose the post type to query.', 'md-sections' ),
					'empty_label' => __( 'Choose content type', 'md-sections' ),
					'options' => $sources
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'context' ), array(
					'type' => 'select',
					'value' => $row['context'] ?? '',
					'label' => __( 'Query context', 'md-sections' ),
					'description' => __( 'Match the current page, or show the latest.', 'md-sections' ),
					'options' => array( 'current' => __( 'Current page', 'md-sections' ), 'latest' => __( 'Latest posts', 'md-sections' ) )
				) ); ?>
			</div>
		</div>
	</div>

	<div class="md-query-loop-section md-conditional">
		<h4><?php echo esc_html__( 'Results', 'md-sections' ); ?></h4>
		<p class="description"><?php echo esc_html__( 'Choose how matching items are displayed and ordered.', 'md-sections' ); ?></p>
		<div class="md-query-loop-grid md-full-select">
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'loop_type' ), array(
					'type' => 'select',
					'value' => $display_mode,
					'label' => __( 'Display mode', 'md-sections' ),
					'description' => __( 'Choose how the results are grouped or presented.', 'md-sections' ),
					'classes' => 'md-conditional-option',
					'empty_label' => __( 'Post listing', 'md-sections' ),
					'options' => array(
						'month' => __( 'Posts grouped by month', 'md-sections' ),
						'category' => __( 'Category overview', 'md-sections' ),
						'category_posts' => __( 'List posts by category', 'md-sections' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field md-conditional-item md-conditional-default md-conditional-month<?php echo $shows_items ? ' is-condition' : ''; ?>">
				<?php $fields->field( array( 'builder', $group, 'items' ), array(
					'type' => 'number',
					'value' => $row['items'] ?? '',
					'placeholder' => $default_items,
					'label' => __( 'Number of items', 'md-sections' ),
					'description' => __( 'Blank uses the site default. -1 shows all.', 'md-sections' ),
					'min' => -1,
					'max' => 100
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'taxonomy' ), array(
					'type' => 'select',
					'value' => $row['taxonomy'] ?? '',
					'label' => __( 'Taxonomy filter', 'md-sections' ),
					'description' => __( 'Choose a taxonomy for the selected source.', 'md-sections' ),
					'empty_label' => __( 'All', 'md-sections' ),
					'options' => $taxonomies
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'terms' ), array(
					'type' => 'text',
					'value' => $row['terms'] ?? '',
					'label' => __( 'Include terms', 'md-sections' ),
					'description' => __( 'Only these terms. Slugs or IDs, comma-separated.', 'md-sections' )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'exclude_terms' ), array(
					'type' => 'text',
					'value' => $row['exclude_terms'] ?? '',
					'label' => __( 'Exclude terms', 'md-sections' ),
					'description' => __( 'Leave out these terms. Slugs or IDs, comma-separated.', 'md-sections' )
				) ); ?>
			</div>
			<div class="md-query-loop-field md-conditional-item md-conditional-default md-conditional-month md-conditional-category_posts<?php echo $shows_order ? ' is-condition' : ''; ?>">
				<?php $fields->field( array( 'builder', $group, 'orderby' ), array(
					'type' => 'select',
					'value' => $row['orderby'] ?? '',
					'label' => __( 'Order by', 'md-sections' ),
					'description' => __( 'Field used for sorting.', 'md-sections' ),
					'empty_label' => __( 'Date', 'md-sections' ),
					'options' => array(
						'title' => __( 'Title', 'md-sections' ),
						'modified' => __( 'Last Modified', 'md-sections' ),
						'comment_count' => __( 'Comment Count', 'md-sections' ),
						'menu_order' => __( 'Menu Order', 'md-sections' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field md-conditional-item md-conditional-default md-conditional-month md-conditional-category_posts<?php echo $shows_order ? ' is-condition' : ''; ?>">
				<?php $fields->field( array( 'builder', $group, 'order' ), array(
					'type' => 'select',
					'value' => $row['order'] ?? '',
					'label' => __( 'Order', 'md-sections' ),
					'description' => __( 'Sort direction.', 'md-sections' ),
					'empty_label' => __( 'Descending', 'md-sections' ),
					'options' => array( 'ASC' => __( 'Ascending', 'md-sections' ) )
				) ); ?>
			</div>
		</div>
		<div class="md-query-loop-category-settings md-conditional-item md-conditional-category md-conditional-category_posts<?php echo $shows_category ? ' is-condition' : ''; ?>">
			<hr class="md-sep-small" />
			<h4><?php echo esc_html__( 'Category', 'md-sections' ); ?></h4>
			<p class="description"><?php echo esc_html__( 'These settings apply to this query and do not inherit the source archive’s category layout.', 'md-sections' ); ?></p>
			<div class="md-query-loop-grid md-full-select">
				<div class="md-query-loop-field">
					<?php $fields->field( array( 'builder', $group, 'category_per_page' ), array(
						'type' => 'number',
						'value' => $row['category_per_page'] ?? '',
						'placeholder' => $default_items,
						'label' => __( 'Categories to show', 'md-sections' ),
						'description' => __( 'Number of category sections to show. -1 shows all.', 'md-sections' ),
						'min' => -1,
						'max' => 100
					) ); ?>
				</div>
				<div class="md-query-loop-field md-conditional-item md-conditional-category_posts<?php echo $display_mode === 'category_posts' ? ' is-condition' : ''; ?>">
					<?php $fields->field( array( 'builder', $group, 'posts_per_category' ), array(
						'type' => 'number',
						'value' => $row['posts_per_category'] ?? '',
						'placeholder' => $default_items,
						'label' => __( 'Posts per category', 'md-sections' ),
						'description' => __( 'Number of posts in each category. -1 shows all.', 'md-sections' ),
						'min' => -1,
						'max' => 100
					) ); ?>
				</div>
				<div class="md-query-loop-field">
					<?php $fields->field( array( 'builder', $group, 'category_columns' ), array(
						'type' => 'number',
						'value' => $row['category_columns'] ?? '',
						'placeholder' => 1,
						'label' => __( 'Category columns', 'md-sections' ),
						'description' => __( 'Number of category sections per row.', 'md-sections' ),
						'min' => 1,
						'max' => 6
					) ); ?>
				</div>
				<div class="md-query-loop-field">
					<?php $fields->field( array( 'builder', $group, 'category_orderby' ), array(
						'type' => 'select',
						'value' => $row['category_orderby'] ?? '',
						'label' => __( 'Order categories by', 'md-sections' ),
						'description' => __( 'Latest post puts recent updates first.', 'md-sections' ),
						'empty_label' => __( 'Name', 'md-sections' ),
						'options' => array(
							'latest' => __( 'Latest post', 'md-sections' ),
							'slug' => __( 'Slug', 'md-sections' ),
							'term_id' => __( 'Term ID', 'md-sections' ),
							'count' => __( 'Post count', 'md-sections' ),
							'parent' => __( 'Parent', 'md-sections' )
						)
					) ); ?>
				</div>
				<div class="md-query-loop-field">
					<?php $fields->field( array( 'builder', $group, 'category_order' ), array(
						'type' => 'select',
						'value' => $row['category_order'] ?? '',
						'label' => __( 'Category order', 'md-sections' ),
						'description' => __( 'Ascending or descending.', 'md-sections' ),
						'empty_label' => __( 'Ascending', 'md-sections' ),
						'options' => array( 'DESC' => __( 'Descending', 'md-sections' ) )
					) ); ?>
				</div>
				<div class="md-query-loop-field md-query-loop-field-wide">
					<?php $fields->field( array( 'builder', $group, 'category' ), array(
						'type' => 'checkbox',
						'value' => $row['category'] ?? array(),
						'label' => __( 'Category options', 'md-sections' ),
						'inline' => true,
						'options' => array(
							'show_empty' => __( 'Show empty categories', 'md-sections' ),
							'hide_description' => __( 'Hide descriptions', 'md-sections' )
						)
					) ); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="md-query-loop-section">
		<h4><?php echo esc_html__( 'Display', 'md-sections' ); ?></h4>
		<p class="description"><?php echo esc_html__( 'Blank options use Query Loop defaults, not the source archive’s Loop settings. The default view is Article view.', 'md-sections' ); ?></p>
		<div class="md-query-loop-grid md-full-select">
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'loop' ), array(
					'type' => 'select',
					'value' => $row['loop'] ?? '',
					'label' => __( 'Template', 'md-sections' ),
					'description' => __( 'Visual design.', 'md-sections' ),
					'empty_label' => __( 'Use Default View', 'md-sections' ),
					'options' => array_merge( md_loops( 'options' ), array( 'article' => __( 'Article view', 'md-sections' ) ) )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'columns' ), array(
					'type' => 'number',
					'value' => $row['columns'] ?? '',
					'placeholder' => 1,
					'label' => __( 'Post Columns', 'md-sections' ),
					'description' => __( 'Desktop columns.', 'md-sections' ),
					'min' => 1
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'columns_mobile' ), array(
					'type' => 'number',
					'value' => $row['columns_mobile'] ?? '',
					'placeholder' => 1,
					'label' => __( 'Mobile Columns', 'md-sections' ),
					'description' => __( 'Mobile columns.', 'md-sections' ),
					'min' => 1
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'content' ), array(
					'type' => 'select',
					'value' => $row['content'] ?? '',
					'label' => __( 'Post Content', 'md-sections' ),
					'description' => __( 'Excerpt, full, or hidden.', 'md-sections' ),
					'empty_label' => __( 'Show excerpt', 'md-sections' ),
					'options' => array(
						'excerpt' => __( 'Show excerpt', 'md-sections' ),
						'full' => __( 'Show full content', 'md-sections' ),
						'hide' => __( 'Hide content', 'md-sections' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'featured_image' ), array(
					'type' => 'select',
					'value' => $row['featured_image'] ?? '',
					'label' => __( 'Featured Media', 'md-sections' ),
					'description' => __( 'Override the post’s media setting.', 'md-sections' ),
					'empty_label' => __( 'Use post media setting', 'md-sections' ),
					'options' => $fields->data->values['featured_image']
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'featured_image_mobile' ), array(
					'type' => 'select',
					'value' => $row['featured_image_mobile'] ?? '',
					'label' => __( 'Mobile Image', 'md-sections' ),
					'description' => __( 'Featured image on small screens.', 'md-sections' ),
					'empty_label' => __( 'Stack above or below', 'md-sections' ),
					'options' => array(
						'beside' => __( 'Keep beside', 'md-sections' ),
						'hide' => __( 'Hide', 'md-sections' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'byline' ), array(
					'type' => 'select',
					'value' => $row['byline'] ?? '',
					'label' => __( 'Byline', 'md-sections' ),
					'description' => __( 'Show the source post type’s byline or hide it.', 'md-sections' ),
					'empty_label' => __( 'Show source byline', 'md-sections' ),
					'options' => array( 'hide' => __( 'Hide all bylines', 'md-sections' ) )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'pagination' ), array(
					'type' => 'select',
					'value' => $row['pagination'] ?? '',
					'label' => __( 'Pagination', 'md-sections' ),
					'description' => __( 'Off unless selected. Not used when showing all.', 'md-sections' ),
					'empty_label' => __( 'No pagination', 'md-sections' ),
					'options' => array(
						'page_numbers' => __( 'Page numbers', 'md-sections' ),
						'prev_next' => __( 'Previous/next links', 'md-sections' )
					)
				) ); ?>
			</div>
		</div>
	</div>
</div>
