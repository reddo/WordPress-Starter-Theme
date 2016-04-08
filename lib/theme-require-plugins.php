<?php

/**
 * Register the required plugins for this theme.
 */
function mb_register_required_plugins() {

	$plugins = array(

        /*
        array(
            'name'               => 'TGM Example Plugin', // The plugin name.
            'slug'               => 'tgm-example-plugin', // The plugin slug (typically the folder name).
            'source'             => get_template_directory() . '/lib/plugins/tgm-example-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        ),
        */

        array(
            'name'              => 'Advanced Custom Fields',
            'slug'              => 'advanced-custom-fields',
            'required'          => false,
            'force_activation'  => false
        ),

        array(
            'name'              => 'Bootstrap 3 Shortcodes',
            'slug'              => 'bootstrap-3-shortcodes',
            'required'          => false,
            'force_activation'  => false
        ),

        array(
            'name'              => 'Font Awesome Shortcodes',
            'slug'              => 'font-awesome-shortcodes',
            'required'          => false,
            'force_activation'  => false
        ),

        array(
            'name'              => 'Contact Form 7',
            'slug'              => 'contact-form-7',
            'required'          => false,
            'force_activation'  => false
        ),

        array(
            'name'              => 'Contact Form 7',
            'slug'              => 'contact-form-7',
            'required'          => false,
            'force_activation'  => false
        ),

        array(
            'name'              => 'Bootstrap for Contact Form 7',
            'slug'              => 'bootstrap-for-contact-form-7',
            'required'          => false,
            'force_activation'  => false
        ),

        array(
            'name'              => 'Custom Post Type UI',
            'slug'              => 'custom-post-type-ui',
            'required'          => false,
            'force_activation'  => false
        ),

        array(
            'name'              => 'Shortcake (Shortcode UI)',
            'slug'              => 'shortcode-ui',
            'required'          => false,
            'force_activation'  => false
        )
    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        /*
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', '_mbbasetheme' ),
            'menu_title'                      => __( 'Install Plugins', '_mbbasetheme' ),
            'installing'                      => __( 'Installing Plugin: %s', '_mbbasetheme' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', '_mbbasetheme' ),
            'notice_can_install_required'     => _n_noop(
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop(
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop(
                'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update_maybe'      => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop(
                'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop(
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop(
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop(
                'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
                '_mbbasetheme'
            ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                '_mbbasetheme'
            ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                '_mbbasetheme'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                '_mbbasetheme'
            ),
            'return'                          => __( 'Return to Required Plugins Installer', '_mbbasetheme' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', '_mbbasetheme' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', '_mbbasetheme' ),
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', '_mbbasetheme' ),  // %1$s = plugin name(s).
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', '_mbbasetheme' ),  // %1$s = plugin name(s).
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', '_mbbasetheme' ), // %s = dashboard link.
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', '_mbbasetheme' ),

            'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        ),
        */
    );

    tgmpa( $plugins, $config );

}
