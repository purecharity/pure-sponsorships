<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://purecharity.com
 * @since             1.0.0
 * @package           Purecharity_Wp_Sponsorships
 *
 * @wordpress-plugin
 * Plugin Name:       Pure Charity Sponsorships
 * Plugin URI:        http://purecharity.com/
 * Description:       Plugin to display a list of or a single sponsorship program from the Pure Charity app.
 * Version:           1.0.2
 * Author:            Rafael Dalprá / Pure Charity
 * Author URI:        http://purecharity.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       purecharity-wp-sponsorships
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The paginator.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/paginator.class.php';

/**
 * The shortcodes.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcode.class.php';

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/activator.class.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/deactivator.class.php';

/** This action is documented in includes/purecharity-wp-sponsorships-activator.class.php */
register_activation_hook( __FILE__, array( 'Purecharity_Wp_Sponsorships_Activator', 'activate' ) );

/** This action is documented in includes/purecharity-wp-sponsorships-deactivator.class.php */
register_deactivation_hook( __FILE__, array( 'Purecharity_Wp_Sponsorships_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/purecharity-wp-sponsorships.class.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_purecharity_wp_sponsorships() {

	$plugin = new Purecharity_Wp_Sponsorships();
	$plugin->run();

}
run_purecharity_wp_sponsorships();
register_activation_hook( __FILE__, array( 'Purecharity_Wp_Sponsorships', 'activation_check' ) );

/**
 * Force the use of a specific template
 *
 * @since    1.0.1
 */
function ss_force_template() {
	$options = get_option( 'purecharity_sponsorships_settings' );
  include(get_template_directory() . '/' . $options['single_view_template']);
  exit;
}