<?php
/**
 * Plugin Name:       Elynt Contact CTA Button
 * Plugin URI:        https://github.com/caiomrlo/
 * Description:       A clean and powerful plugin to create WhatsApp Call-to-Action buttons.
 * Version:           1.1.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            github.com/caiomrlo
 * Author URI:        https://github.com/caiomrlo
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       elynt-contact-cta-button
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 */
define('ELYNCOCT_VERSION', '1.1.0');
define('ELYNCOCT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ELYNCOCT_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function elyncoct_activate_contact_cta_button()
{
	require_once ELYNCOCT_PLUGIN_DIR . 'includes/class-activator.php';
	ELYNCOCT_Chat_Button_Activator::activate();
}

register_activation_hook(__FILE__, 'elyncoct_activate_contact_cta_button');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once ELYNCOCT_PLUGIN_DIR . 'includes/class-elynt-contact-cta-button.php';

/**
 * Begins execution of the plugin.
 */
function elyncoct_run_contact_cta_button()
{
	$plugin = new ELYNCOCT_Chat_Button();
	$plugin->run();
}
elyncoct_run_contact_cta_button();
