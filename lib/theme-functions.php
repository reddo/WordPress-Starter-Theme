<?php
/**
 * _mbbasetheme theme functions definted in /lib/init.php
 *
 * @package _mbbasetheme
 */


/**
 * Register Widget Areas
 */
function mb_widgets_init() {
	// Main Sidebar
	register_sidebar( array(
		'name'          => __( 'Sidebar', '_mbbasetheme' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}

/**
 * Remove Dashboard Meta Boxes
 */
function mb_remove_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}

/**
 * Change Admin Menu Order
 */
function mb_custom_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;
	return array(
		// 'index.php', // Dashboard
		// 'separator1', // First separator
		// 'edit.php?post_type=page', // Pages
		// 'edit.php', // Posts
		// 'upload.php', // Media
		// 'gf_edit_forms', // Gravity Forms
		// 'genesis', // Genesis
		// 'edit-comments.php', // Comments
		// 'separator2', // Second separator
		// 'themes.php', // Appearance
		// 'plugins.php', // Plugins
		// 'users.php', // Users
		// 'tools.php', // Tools
		// 'options-general.php', // Settings
		// 'separator-last', // Last separator
	);
}

/**
 * Hide Admin Areas that are not used
 */
function mb_remove_menu_pages() {
	// remove_menu_page( 'link-manager.php' );
}

/**
 * Remove default link for images
 */
function mb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if ( $image_set !== 'none' ) {
		update_option( 'image_default_link_type', 'none' );
	}
}

/**
 * Enqueue scripts
 */
function mb_scripts() {
	wp_enqueue_style( '_mbbasetheme-style', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( !is_admin() ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'customplugins', get_template_directory_uri() . '/assets/js/plugins.min.js', array('jquery'), NULL, true );
		wp_enqueue_script( 'customscripts', get_template_directory_uri() . '/assets/js/main.min.js', array('jquery'), NULL, true );
	}
}

/**
 * Remove Query Strings From Static Resources
 */
function mb_remove_script_version( $src ){
	$parts = explode( '?ver', $src );
	return $parts[0];
}

/**
 * Remove Read More Jump
 */
function mb_remove_more_jump_link( $link ) {
	$offset = strpos( $link, '#more-' );
	if ($offset) {
		$end = strpos( $link, '"',$offset );
	}
	if ($end) {
		$link = substr_replace( $link, '', $offset, $end-$offset );
	}
	return $link;
}

/**
 * Wrap iframes in responsive divs
 */
function mb_div_wrapper($content) {
  // match any iframes
  $pattern = '~<iframe.*</iframe>|<embed.*</embed>~';
  preg_match_all($pattern, $content, $matches);

  foreach ($matches[0] as $match) {
    // wrap matched iframe with div
    $wrappedframe = '<div class="embed-responsive embed-responsive-16by9">' . $match . '</div>';

    //replace original iframe with new in content
    $content = str_replace($match, $wrappedframe, $content);
  }

  return $content;    
}

/**
 * Override WordPress' embed shortcode 
 */
function mb_embed_oembed_html($html, $url, $attr, $post_id) {
  $ratio = strpos($attr['width'], 'by') > -1 ? $attr['width'] : "16by9";
  return '<div class="embed-responsive embed-responsive-' . $ratio . '">' . $html . '</div>';
}

/**
 * Add page slug to body class
 */
function mb_add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}

/**
 * If Shortcake is active, initialize shortcodes
 */
function mb_shortcode_ui_detection() {
	if ( !function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		add_action( 'admin_notices', 'mb_shortcode_ui_dev_example_notices' );
	}
}
function mb_shortcode_ui_dev_example_notices() {
	if ( current_user_can( 'activate_plugins' ) ) {
		echo '<div class="error message"><p>Shortcode UI plugin must be active for Shortcode UI Example plugin to function.</p></div>';
	}
}

/**
 * Add shortcodes to be usable from shortcake
 */
function _mbbasetheme_shortcode_ui() {
	/**
	 * Register a UI for the Shortcode.
	 * Pass the shortcode tag (string)
	 * and an array or args.
	 */
	shortcode_ui_register_for_shortcode(
	  'example-shortcode', array(
		  /*
			 * How the shortcode should be labeled in the UI. Required argument.
			 */
			'label' => __( 'Shortcake Example', '_mbbasetheme' ),
			/*
			 * Include an icon with your shortcode. Optional.
			 * Use a dashicon, or full URL to image.
			 */
			'listItemImage' => 'dashicons-editor-quote',
			/*
			 * Limit this shortcode UI to specific posts. Optional.
			 */
			'post_type' => array( 'post' ),
			/*
			 * Register UI for the "inner content" of the shortcode. Optional.
			 * If no UI is registered for the inner content, then any inner content
			 * data present will be backed up during editing.
			 */
			'inner_content' => array(
				'label'        => __( 'Quote', '_mbbasetheme' ),
				'description'  => __( 'Include a statement from someone famous.', '_mbbasetheme' )
			),
			/*
			 * Register UI for attributes of the shortcode. Optional.
			 *
			 * If no UI is registered for an attribute, then the attribute will 
			 * not be editable through Shortcake's UI. However, the value of any 
			 * unregistered attributes will be preserved when editing.
			 * 
			 * Each array must include 'attr', 'type', and 'label'.
			 * 'attr' should be the name of the attribute.
			 * 'type' options include: text, checkbox, textarea, radio, select, email, 
			 *     url, number, and date, post_select, attachment, color.
			 * Use 'meta' to add arbitrary attributes to the HTML of the field.
			 * Use 'encode' to encode attribute data. Requires customization to callback to decode.
			 * Depending on 'type', additional arguments may be available.
			 */
			'attrs' => array(
				array(
					'label'       => __( 'Attachment', '_mbbasetheme' ),
					'attr'        => 'attachment',
					'type'        => 'attachment',
					/*
					 * These arguments are passed to the instantiation of the media library:
					 * 'libraryType' - Type of media to make available.
					 * 'addButton' - Text for the button to open media library.
					 * 'frameTitle' - Title for the modal UI once the library is open.
					 */
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select Image', '_mbbasetheme' ),
					'frameTitle'  => __( 'Select Image', '_mbbasetheme ' )
				),
				array(
					'label'  => __( 'Citation Source', '_mbbasetheme' ),
					'attr'   => 'source',
					'type'   => 'text',
					'encode' => true,
					'meta'   => array(
						'placeholder' => __( 'Test placeholder', '_mbbasetheme' ),
						'data-test'   => 1
					)
				),
				array(
					'label' => __( 'Select Page', '_mbbasetheme' ),
					'attr' => 'page',
					'type' => 'post_select',
					'query' => array( 'post_type' => 'page' ),
					'multiple' => true
				),
				array(
					'label' => __( 'Select Whatever', '_mbbasetheme' ),
					'attr' => 'select',
					'type' => 'select',
					'options' => array( 
						'option_1_value' => 'Option 1',
						'option_2_value' => 'Option 2'
						),
					'multiple' => true
				)
			)
	  )
	);

	// Register bootstrap's [column] shortcode
	/* shortcode_ui_register_for_shortcode(
	  'column', array(
	  	'label' => __( 'BS Column', '_mbbasetheme' ),
			'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
			'inner_content' => array(
				'label'        => __( 'Content', '_mbbasetheme' )
			),
			'attrs' => array(
				array(
					'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
					'attr'   => 'xclass',
					'type'   => 'text',
					'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
				),
				array(
					'label' => __('col-xs-'),
					'attr' => 'xs',
					'type' => 'text',
					'description' => __( 'Size of column on extra small screens (less than 768px); optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('col-sm-'),
					'attr' => 'sm',
					'type' => 'text',
					'description' => __( 'Size of column on small screens (greater than 768px);	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('col-md-'),
					'attr' => 'md',
					'type' => 'text',
					'description' => __( 'Size of column on medium screens (greater than 992px);	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('col-lg-'),
					'attr' => 'lg',
					'type' => 'text',
					'description' => __( 'Size of column on large screens (greater than 1200px);	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('col-xs-offset-'),
					'attr' => 'offset_xs',
					'type' => 'text',
					'description' => __( 'Offset on extra small screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('col-sm-offset-'),
					'attr' => 'offset_sm',
					'type' => 'text',
					'description' => __( 'Offset on small screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('col-md-offset-'),
					'attr' => 'offset_md',
					'type' => 'text',
					'description' => __( 'Offset on column on medium screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('col-lg-offset-'),
					'attr' => 'offset_lg',
					'type' => 'text',
					'description' => __( 'Offset on column on large screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('pull-xs-'),
					'attr' => 'pull_xs',
					'type' => 'text',
					'description' => __( 'Pull on extra small screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('pull-sm-'),
					'attr' => 'pull_sm',
					'type' => 'text',
					'description' => __( 'Pull on small screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('pull-md-'),
					'attr' => 'pull_md',
					'type' => 'text',
					'description' => __( 'Pull on column on medium screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('pull-lg-'),
					'attr' => 'pull_lg',
					'type' => 'text',
					'description' => __( 'Pull on column on large screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('pull-xs-'),
					'attr' => 'push_xs',
					'type' => 'text',
					'description' => __( 'Push on extra small screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('pull-sm-'),
					'attr' => 'push_sm',
					'type' => 'text',
					'description' => __( 'Push on small screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('pull-md-'),
					'attr' => 'push_md',
					'type' => 'text',
					'description' => __( 'Push on column on medium screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('pull-lg-'),
					'attr' => 'push_lg',
					'type' => 'text',
					'description' => __( 'Push on column on large screens;	optional;	1-12	false', '_mbbasetheme')
				),
				array(
					'label' => __('Data attribute(s)'),
					'attr' => 'data',
					'type' => 'text',
					'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe (see example at Button Dropdowns).;	optional;	any text	none', '_mbbasetheme')
				)
			)
		)
	); */

	$tableMarkup = '<table>
	<tr>
		<td>...</td>
		<td>...</td>
	</tr>
</table>';

	if (is_plugin_active('bootstrap-3-shortcodes/bootstrap-shortcodes.php')) :

		// Register bootstrap's [lead] shortcode
		shortcode_ui_register_for_shortcode(
		  'lead', array(
		  	'label' => __( 'BS lead copy', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [emphasis] shortcode
		shortcode_ui_register_for_shortcode(
		  'emphasis', array(
		  	'label' => __( 'BS emphasis', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Type', '_mbbasetheme' ),
						'attr' => 'type',
						'type' => 'select',
						'options' => array(
							'primary' => 'primary',
							'success' => 'success',
							'info' => 'info',
							'warning' => 'warning',
							'danger' => 'danger',
							'muted' => 'muted' 
						),
						'multiple' => false,
						'description'  => __( 'The type of emphasis to display', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [code] shortcode
		shortcode_ui_register_for_shortcode(
		  'code', array(
		  	'label' => __( 'BS code', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Inline?', '_mbbasetheme' ),
						'attr' => 'inline',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Display the code inline?', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Scrollable?', '_mbbasetheme' ),
						'attr' => 'scrollable',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Set a max height of 350px and provide a scroll bar. Not usable if inline is true.', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [button] shortcode
		shortcode_ui_register_for_shortcode(
		  'button', array(
		  	'label' => __( 'BS button', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Type', '_mbbasetheme' ),
						'attr' => 'type',
						'type' => 'select',
						'options' => array(
							'' => 'default',
							'primary' => 'primary',
							'success' => 'success',
							'info' => 'info',
							'warning' => 'warning',
							'danger' => 'danger',
							'link' => 'link'
						),
						'multiple' => false,
						'description'  => __( 'The type of button to display', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Size', '_mbbasetheme' ),
						'attr' => 'size',
						'type' => 'select',
						'options' => array(
							'' => 'default',
							'xs' => 'xs',
							'sm' => 'sm',
							'lg' => 'lg'
						),
						'multiple' => false,
						'description'  => __( 'The size of the button', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Block', '_mbbasetheme' ),
						'attr' => 'block',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Whether the button should be a block-level button', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Dropdown', '_mbbasetheme' ),
						'attr' => 'dropdown',
						'type' => 'radio',
						'options' => array(
							'false' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Whether the button triggers a dropdown menu', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Active', '_mbbasetheme' ),
						'attr' => 'active',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Apply the "active" style', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Disabled', '_mbbasetheme' ),
						'attr' => 'disabled',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Whether the button be disabled', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Link of the button', '_mbbasetheme' ),
						'attr'   => 'link',
						'type'   => 'text',
						'description'  => __( 'The url you want the button to link to', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Target', '_mbbasetheme' ),
						'attr' => 'target',
						'type' => 'text',
						'multiple' => false,
						'description'  => __( 'Target where the link should open', '_mbbasetheme' ),
						'meta'   => array(
							'placeholder' => __( '_blank|_self|_parent|_top|framename', '_mbbasetheme' )
						)
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [responsive] shortcode
		shortcode_ui_register_for_shortcode(
		  'responsive', array(
		  	'label' => __( 'BS responsive', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Visible', '_mbbasetheme' ),
						'attr' => 'visible',
						'type' => 'text',
						'multiple' => false,
						'description'  => __( 'Sizes at which this element is visible (separated by spaces)<br>
							<strong>NOTE: as of Bootstrap 3.2 "visible" is deprecated in favor of "block", "inline", and "inline-block" (see below)</strong>', '_mbbasetheme' ),
						'meta'   => array(
							'placeholder' => __( 'xs sm md lg', '_mbbasetheme' )
						)
					),
					array(
						'label' => __( 'Hidden', '_mbbasetheme' ),
						'attr' => 'hidden',
						'type' => 'text',
						'multiple' => false,
						'description'  => __( 'Sizes at which this element is hidden (separated by spaces)', '_mbbasetheme' ),
						'meta'   => array(
							'placeholder' => __( 'xs sm md lg', '_mbbasetheme' )
						)
					),
					array(
						'label' => __( 'Block', '_mbbasetheme' ),
						'attr' => 'block',
						'type' => 'text',
						'multiple' => false,
						'description'  => __( 'Sizes at which this element is visible and displayed as a "block" element (separated by spaces)', '_mbbasetheme' ),
						'meta'   => array(
							'placeholder' => __( 'xs sm md lg', '_mbbasetheme' )
						)
					),
					array(
						'label' => __( 'Inline', '_mbbasetheme' ),
						'attr' => 'inline',
						'type' => 'text',
						'multiple' => false,
						'description'  => __( 'Sizes at which this element is visible and displayed as an "inline" element (separated by spaces)', '_mbbasetheme' ),
						'meta'   => array(
							'placeholder' => __( 'xs sm md lg', '_mbbasetheme' )
						)
					),
					array(
						'label' => __( 'Inline-block', '_mbbasetheme' ),
						'attr' => 'inline_block',
						'type' => 'text',
						'multiple' => false,
						'description'  => __( 'Sizes at which this element is visible and displayed as an "inline-block" element (separated by spaces)', '_mbbasetheme' ),
						'meta'   => array(
							'placeholder' => __( 'xs sm md lg', '_mbbasetheme' )
						)
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [icon] shortcode
		shortcode_ui_register_for_shortcode(
		  'icon', array(
		  	'label' => __( 'BS glyphicon', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label'  => __( 'Type', '_mbbasetheme' ),
						'attr'   => 'type',
						'type'   => 'text',
						'description'  => __( 'See <a href="http://getbootstrap.com/components/#glyphicons-glyphs" target="_blank">Bootstrap docs</a>.', '_mbbasetheme' ),
						'meta'   => array(
							'placeholder' => __( 'glyphicon-star', '_mbbasetheme' )
						)
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [label] shortcode
		shortcode_ui_register_for_shortcode(
		  'label', array(
		  	'label' => __( 'BS label', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Type', '_mbbasetheme' ),
						'attr' => 'type',
						'type' => 'select',
						'options' => array(
							'' => 'default',
							'primary' => 'primary',
							'success' => 'success',
							'info' => 'info',
							'warning' => 'warning',
							'danger' => 'danger'
						),
						'multiple' => false,
						'description'  => __( 'The type of label to display', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [badge] shortcode
		shortcode_ui_register_for_shortcode(
		  'badge', array(
		  	'label' => __( 'BS badge', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Right', '_mbbasetheme' ),
						'attr' => 'right',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Whether the badge should align to the right of its container', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [jumbotron] shortcode
		shortcode_ui_register_for_shortcode(
		  'jumbotron', array(
		  	'label' => __( 'BS jumbotron', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' ),
					'description'  => __( '<a href="http://getbootstrap.com/components/#jumbotron" target="_blank">Bootstrap jumbotron documentation</a>', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label'  => __( 'Title', '_mbbasetheme' ),
						'attr'   => 'title',
						'type'   => 'text',
						'description'  => __( 'The jumbotron title', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [page-header] shortcode
		shortcode_ui_register_for_shortcode(
		  'page-header', array(
		  	'label' => __( 'BS page-header', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' ),
					'description'  => __( 'Automatically inserts H1 tag if not present', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [alert] shortcode
		shortcode_ui_register_for_shortcode(
		  'alert', array(
		  	'label' => __( 'BS alert', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Type', '_mbbasetheme' ),
						'attr' => 'type',
						'type' => 'select',
						'options' => array(
							'primary' => 'primary',
							'success' => 'success',
							'info' => 'info',
							'warning' => 'warning',
							'danger' => 'danger'
						),
						'multiple' => false,
						'description'  => __( 'The type of the alert', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Dismissable', '_mbbasetheme' ),
						'attr' => 'dismissable',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'If the alert should be dismissable', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [panel] shortcode
		shortcode_ui_register_for_shortcode(
		  'panel', array(
		  	'label' => __( 'BS panel', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Type', '_mbbasetheme' ),
						'attr' => 'type',
						'type' => 'select',
						'options' => array(
							'' => 'default',
							'primary' => 'primary',
							'success' => 'success',
							'info' => 'info',
							'warning' => 'warning',
							'danger' => 'danger'
						),
						'multiple' => false,
						'description'  => __( 'The type of the panel', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Heading', '_mbbasetheme' ),
						'attr'   => 'heading',
						'type'   => 'text',
						'description'  => __( 'The panel heading', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Title', '_mbbasetheme' ),
						'attr' => 'title',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Whether the panel heading should have a title tag around it', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Footer', '_mbbasetheme' ),
						'attr'   => 'footer',
						'type'   => 'text',
						'description'  => __( 'The panel footer text if desired	', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [well] shortcode
		shortcode_ui_register_for_shortcode(
		  'well', array(
		  	'label' => __( 'BS well', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Size', '_mbbasetheme' ),
						'attr' => 'size',
						'type' => 'select',
						'options' => array(
							'' => 'normal',
							'sm' => 'sm',
							'lg' => 'lg'
						),
						'multiple' => false,
						'description'  => __( 'Modifies the amount of padding inside the well', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

		// Register bootstrap's [tooltip] shortcode
		shortcode_ui_register_for_shortcode(
		  'tooltip', array(
		  	'label' => __( 'BS tooltip', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Title', '_mbbasetheme' ),
						'attr' => 'title',
						'type' => 'text',
						'description'  => __( 'The text of the tooltip', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Placement', '_mbbasetheme' ),
						'attr' => 'placement',
						'type' => 'select',
						'options' => array(
							'left' => 'left', 
							'' => 'top', 
							'bottom' => 'bottom', 
							'right' => 'right'
						),
						'multiple' => false,
						'description'  => __( 'The placement of the tooltip', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Animation', '_mbbasetheme' ),
						'attr' => 'animation',
						'type' => 'text',
						'description'  => __( 'Apply a CSS fade transition to the tooltip', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Html', '_mbbasetheme' ),
						'attr' => 'html',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Insert HTML into the tooltip', '_mbbasetheme' )
					)
				)
			)
		);

		// Register bootstrap's [popover] shortcode
		shortcode_ui_register_for_shortcode(
		  'popover', array(
		  	'label' => __( 'BS popover', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' )
				),
				'attrs' => array(
					array(
						'label' => __( 'Title', '_mbbasetheme' ),
						'attr' => 'title',
						'type' => 'text',
						'description'  => __( 'The title of the popover', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Text', '_mbbasetheme' ),
						'attr' => 'text',
						'type' => 'text',
						'description'  => __( 'The text of the popover', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Placement', '_mbbasetheme' ),
						'attr' => 'placement',
						'type' => 'select',
						'options' => array(
							'left' => 'left', 
							'' => 'top', 
							'bottom' => 'bottom', 
							'right' => 'right'
						),
						'multiple' => false,
						'description'  => __( 'The placement of the popover', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Animation', '_mbbasetheme' ),
						'attr' => 'animation',
						'type' => 'text',
						'description'  => __( 'Apply a CSS fade transition to the popover', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Html', '_mbbasetheme' ),
						'attr' => 'html',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Insert HTML into the popover', '_mbbasetheme' )
					)
				)
			)
		);

		// Register bootstrap's [table-wrap] shortcode
		shortcode_ui_register_for_shortcode(
		  'table-wrap', array(
		  	'label' => __( 'BS table-wrap', '_mbbasetheme' ),
				'listItemImage' => '<img src="http://getbootstrap.com/apple-touch-icon.png" alt="Bootstrap">',  
				'inner_content' => array(
					'label'        => __( 'Content', '_mbbasetheme' ),
					'description'  => esc_html__( 'Standard HTML table code goes here. Including opening (<table>) and closing (</table>) tags.', '_mbbasetheme' ),
					'meta'   => array(
						'placeholder' => $tableMarkup
					)
				),
				'attrs' => array(
					array(
						'label' => __( 'Bordered', '_mbbasetheme' ),
						'attr' => 'bordered',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Set "bordered" table style (see Bootstrap documentation)', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Striped', '_mbbasetheme' ),
						'attr' => 'striped',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Set "striped" table style (see Bootstrap documentation)', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Hover', '_mbbasetheme' ),
						'attr' => 'hover',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Set "hover" table style (see Bootstrap documentation)', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Condensed', '_mbbasetheme' ),
						'attr' => 'condensed',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Set "condensed" table style (see Bootstrap documentation)', '_mbbasetheme' )
					),
					array(
						'label' => __( 'Responsive', '_mbbasetheme' ),
						'attr' => 'responsive',
						'type' => 'radio',
						'options' => array(
							'' => 'false',
							'true' => 'true'
						),
						'description'  => __( 'Wrap the table in a div with the class "table-responsive" (see Bootstrap documentation)', '_mbbasetheme' )
					),
					array(
						'label'  => __( 'Extra Class(es)', '_mbbasetheme' ),
						'attr'   => 'xclass',
						'type'   => 'text',
						'description'  => __( 'Any extra classes you want to add', '_mbbasetheme' )
					),
					array(
						'label' => __('Data attribute(s)'),
						'attr' => 'data',
						'type' => 'text',
						'description' => __( 'Data attribute and value pairs separated by a comma. Pairs separated by pipe', '_mbbasetheme'),
						'meta'   => array(
							'placeholder' => __( 'attribute,value|another-attr,value', '_mbbasetheme' )
						)
					)
				)
			)
		);

	endif;
}
