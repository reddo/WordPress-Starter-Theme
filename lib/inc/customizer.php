<?php
/**
 * _mbbasetheme Theme Customizer
 *
 * @package _mbbasetheme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function _mbbasetheme_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', '_mbbasetheme_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function _mbbasetheme_customize_preview_js() {
	wp_enqueue_script( '_mbbasetheme_customizer', get_template_directory_uri() . 'assets/js/vendor/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', '_mbbasetheme_customize_preview_js' );

/**
 * Add some custom options to customizer
 * More info and a comprehensive guide here: http://themefoundation.com/wordpress-theme-customizer/
 */
function _mbbasetheme_theme_customizer( $wp_customize ) {
  /**
   * Adds textarea support to the theme customizer
   */
  class Example_Customize_Textarea_Control extends WP_Customize_Control {
    public $type = 'textarea';
 
    public function render_content() {
      ?>
        <label>
          <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
          <textarea rows="3" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
        </label>
      <?php
    }
  }

  /**
   * Add support for site logo to customizer
   * usage: <?php echo get_theme_mod( '_mbbasetheme_logo' );  ?>
   * WP 4.5 introduced Theme Logo support @link {https://codex.wordpress.org/Theme_Logo}. This is obsolete.
   */
  // $wp_customize->add_section( '_mbbasetheme_logo_section' , array(
  //     'title'       => __( 'Logo', '_mbbasetheme' ),
  //     'priority'    => 30,
  //     'description' => __( 'Upload a logo to replace the default site name in the header', '_mbbasetheme' )
  // ) );
  // $wp_customize->add_setting( '_mbbasetheme_logo', array(
  //   'sanitize_callback' => 'esc_url_raw'
  // ) );
  // $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, '_mbbasetheme_logo', array(
  //   'label'    => __( 'Logo', '_mbbasetheme' ),
  //   'section'  => '_mbbasetheme_logo_section',
  //   'settings' => '_mbbasetheme_logo'
  // ) ) );

  /**
   * Add support for social links and other contact info info to customizer
   * usage: <?php echo get_theme_mod( '_mbbasetheme_facebook_textbox' );  ?>
   */
  $wp_customize->add_section( '_mbbasetheme_contact_info_section', array(
      'title' => __( 'Contact Info Section', '_mbbasetheme' ),
      'priority' => 35,
      'description' => __( 'Customize the social media links (leave empty to ommit), contact info - like address, phone number and email.', '_mbbasetheme' ),
  ) );

  $wp_customize->add_setting( '_mbbasetheme_facebook_textbox', array(
      'sanitize_callback' => 'esc_url_raw'
  ) );
  $wp_customize->add_control( '_mbbasetheme_facebook_textbox', array(
      'label' => __( 'Facebook Profile URL', '_mbbasetheme' ),
      'section' => '_mbbasetheme_contact_info_section',
      'type' => 'text'
  ) );

  $wp_customize->add_setting( '_mbbasetheme_twitter_textbox', array(
      'sanitize_callback' => 'esc_url_raw',
  ) );
  $wp_customize->add_control( '_mbbasetheme_twitter_textbox', array(
      'label' => __( 'Twitter Profile URL', '_mbbasetheme' ),
      'section' => '_mbbasetheme_contact_info_section',
      'type' => 'text'
  ) );

  $wp_customize->add_setting( '_mbbasetheme_linkedin_textbox', array(
      'sanitize_callback' => 'esc_url_raw',
  ) );
  $wp_customize->add_control( '_mbbasetheme_linkedin_textbox', array(
      'label' => __( 'Linkedin Profile URL', '_mbbasetheme' ),
      'section' => '_mbbasetheme_contact_info_section',
      'type' => 'text'
  ) );

  $wp_customize->add_setting( '_mbbasetheme_address_textarea', array(
      'sanitize_callback' => 'wpautop',
  ) );
  $wp_customize->add_control(
    new Example_Customize_Textarea_Control(
      $wp_customize,
      'textarea',
      array(
        'label' => __( 'Address', '_mbbasetheme' ),
        'section' => '_mbbasetheme_contact_info_section',
        'settings' => '_mbbasetheme_address_textarea'
      )
    )
  );

  $wp_customize->add_setting( '_mbbasetheme_phone_textbox', array(
    'sanitize_callback' => 'sanitize_text_field'
  ) );
  $wp_customize->add_control( '_mbbasetheme_phone_textbox', array(
      'label' => __( 'Phone number', '_mbbasetheme' ),
      'section' => '_mbbasetheme_contact_info_section',
      'type' => 'text'
  ) );

  $wp_customize->add_setting( '_mbbasetheme_email_textbox', array(
      'sanitize_callback' => 'sanitize_email',
  ) );
  $wp_customize->add_control( '_mbbasetheme_email_textbox', array(
      'label' => __( 'Email Address', '_mbbasetheme' ),
      'section' => '_mbbasetheme_contact_info_section',
      'type' => 'text'
  ) );

  /**
   * Add support for custom footer info to customizer
   * usage: <?php echo get_theme_mod( '_mbbasetheme_copyright_textbox' );  ?>
   */
  $wp_customize->add_section( '_mbbasetheme_footer_section', array(
      'title' => __( 'Footer Copyright Section', '_mbbasetheme' ),
      'priority' => 35,
      'description' => __( 'Customize the copyright info; you can use [date format="Y"] [bloginfo show="name"] shortcodes to show year and respectively site title.', '_mbbasetheme' )
  ) );

  $wp_customize->add_setting( '_mbbasetheme_copyright_textbox', array(
    'default' => __( '© [date format="Y"] All rights reserved', '_mbbasetheme' ),
    'sanitize_callback' => 'sanitize_text_field'
  ) );
  $wp_customize->add_control( '_mbbasetheme_copyright_textbox', array(
      'label' => __( 'Copyright Text', '_mbbasetheme' ),
      'section' => '_mbbasetheme_footer_section',
      'type' => 'text'
  ) );
}
// add_action( 'customize_register', '_mbbasetheme_theme_customizer' ); // Uncomment this line to add some more options to theme customizer.