<?php
/**
 * Plugin Name: Lil Notices
 * Plugin URI: http://mlteal.com/
 * Description: Giant admin notices are annoying. Make them lil'.
 * Author: mlteal
 * Version: 0.1.2
 * License: GPLv2
 * Text Domain: ln_domain
 *
 * GitHub Plugin URI: https://github.com/
 * GitHub Branch: master
 *
 * @package ln_domain
 * @category plugin
 */

define( 'LIL_NOTICES__VERSION', '0.1.2' );

require_once( 'class-lil-notices.php' );

/**
 * Build and initialize the plugin
 */
if ( class_exists( 'Lil_Notices' ) ) {
	// Installation and un-installation hooks
	register_activation_hook( __FILE__, array( 'Lil_Notices', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'Lil_Notices', 'deactivate' ) );

	// initialize
	add_action( 'plugins_loaded', array( 'Lil_Notices', 'init' ) );
}