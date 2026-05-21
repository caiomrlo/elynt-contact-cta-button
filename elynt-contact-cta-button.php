<?php
/**
 * Plugin Name:       Elynt Contact CTA Button
 * Plugin URI:        https://github.com/caiomrlo/
 * Description:       A clean and powerful plugin to create WhatsApp Call-to-Action buttons.
 * Version:           1.0.0
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
define('ELYNT_CHAT_BUTTON_VERSION', '1.0.0');
define('ELYNT_CHAT_BUTTON_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ELYNT_CHAT_BUTTON_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_elynt_contact_cta_button()
{
	require_once ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'includes/class-activator.php';
	ELYNT_Chat_Button_Activator::activate();
}

register_activation_hook(__FILE__, 'activate_elynt_contact_cta_button');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'includes/class-elynt-contact-cta-button.php';

/**
 * Begins execution of the plugin.
 */
function run_ELYNT_chat_button()
{
	$plugin = new ELYNT_Chat_Button();
	$plugin->run();
}
run_ELYNT_chat_button();
