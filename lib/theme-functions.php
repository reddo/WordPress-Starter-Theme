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

/*
 * This is still work in progress, it is not activated yet - Activate from /lib/init.php
 * Adds shortcode button above tinymce editor
 */
function mb_add_tinymce_media_button( $context ){
return $context.= "
	<a href=\"#TB_inline?width=360&inlineId=mb_shortcode_popup&width=500&height=513\" class=\"button thickbox\" id=\"mb_shortcode_popup_button\" title=\"Custom Shortcodes\"><span class=\"dashicons dashicons-format-status\"></span>" . __('Custom Shortcodes', '_mbbasetheme') . "</a>";
}

/*
 * This is still work in progress, it is not activated yet - Activate from /lib/init.php
 * Generate inline content for the popup window when the "my shortcode" button is clicked
 */
function mb_shortcode_media_button_popup(){
?>
  <div id="mb_shortcode_popup" style="display:none;">
    <!--".wrap" class div is needed to make thickbox content look good-->
    <div id="shortcode_popup_inner" class="wrap">
      <div class="shortcode-date">
        <h2>Insert [date] Shortcode</h2>
        <p>More information on the availabale formats <a href="http://php.net/manual/en/function.date.php" target="_blank">here</a></p>
        <div class="shortcode-wrap" data-shortcode="date" data-self-closing="true" data-attributes="format">
          <input type="text" class="regular-text format-value" placeholder="format"><br>
          <button class="button-primary">Insert</button>
        </div>
      </div>
      <div class="shortcode-bloginfo">
        <h2>Insert [bloginfo] Shortcode</h2>
        <p>More information on the availabale values <a href="https://developer.wordpress.org/reference/functions/bloginfo/" target="_blank">here</a></p>
        <div class="shortcode-wrap" data-shortcode="bloginfo" data-self-closing="true" data-attributes="show">
          <select class="show-value">
          	<option value="">Select value to be shown</option>
          	<option value="name">name</option>
						<option value="description">description</option>
						<option value="wpurl">wpurl</option>
						<option value="url">url</option>
						<option value="admin_email">admin_email</option>
						<option value="charset">charset</option>
						<option value="version">version</option>
						<option value="html_type">html_type</option>
						<option value="text_direction">text_direction</option>
						<option value="language">language</option>
						<option value="stylesheet_url">stylesheet_url</option>
						<option value="stylesheet_directory">stylesheet_directory</option>
						<option value="template_url">template_url</option>
						<option value="pingback_url">pingback_url</option>
						<option value="atom_url">atom_url</option>
						<option value="rdf_url">rdf_url</option>
						<option value="rss_url">rss_url</option>
						<option value="rss2_url">rss2_url</option>
						<option value="comments_atom_url">comments_atom_url</option>
						<option value="comments_rss2_url">comments_rss2_url</option>
					</select><br>
          <button class="button-primary">Insert</button>
        </div>
      </div>
      <div class="shortcode-read-more">
        <h2>Insert [read-more] Shortcode</h2>
        <div class="shortcode-wrap" data-shortcode="read-more" data-attributes="xclass,btn-text">
          <input type="text" class="regular-text xclass-value" placeholder="xclass"><br>
          <input type="text" class="regular-text btn-text-value" placeholder="btn-text (default: <?php _e( 'Discover More', '_quartermoore' ) ?>)"><br>
          <button class="button-primary">Insert</button>
        </div>
      </div>
    </div>
  </div>
<?php
}

/*
 * This is still work in progress, it is not activated yet - Activate from /lib/init.php
 * Adds javascript code needed to make shortcode appear in TinyMCE editor
 */
function mb_add_shortcode_to_editor(){
?>
<script>
	jQuery('#shortcode_popup_inner ').on('click', 'button', function(){
		var self = this;

	  var $shortcodeWrap = jQuery(self).parents('.shortcode-wrap');
	  var shortcode = $shortcodeWrap.data('shortcode');
	  var attributes = $shortcodeWrap.data('attributes').split(',');
	  var attrList = '';

	  jQuery.each(attributes, function( index, value ) {
	  	var inputClass = '.' + value + '-value';
	  	var attrVal = jQuery(self).siblings(inputClass).val();
		  attrList += attrVal !== '' ? ' ' + value + '="' + attrVal + '"' : '';
		});

	  var selfClosing = $shortcodeWrap.data('self-closing') ? ']' : '][/' + shortcode + ']';

	  var codeToAdd = '[' + shortcode + attrList + selfClosing;

    var win = window.dialogArguments || opener || parent || top;
    win.send_to_editor(codeToAdd);
	  //empty the text field and close the thickbox after adding shortcode to editor
	  jQuery(self).siblings('input, select').val('');
	});
</script>
<?php
}
