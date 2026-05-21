<?php

if (!defined('WPINC')) {
	exit;
}

/**
 * The core plugin class.
 */
class ELYNT_Chat_Button
{

	protected $plugin_name;
	protected $version;

	public function __construct()
	{
		$this->plugin_name = 'elynt-contact-cta-button';
		$this->version = ELYNT_CHAT_BUTTON_VERSION;

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies()
	{
		require_once ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'includes/class-db-manager.php';

		// Admin
		require_once ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'admin/class-admin.php';
		require_once ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'admin/class-ajax-handler.php';

		// Public
		require_once ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'public/class-public.php';
	}

	private function define_admin_hooks()
	{
		$plugin_admin = new ELYNT_Chat_Button_Admin($this->get_plugin_name(), $this->get_version());
		$plugin_ajax = new ELYNT_Chat_Button_Ajax_Handler();

		add_action('admin_menu', array($plugin_admin, 'add_plugin_admin_menu'));
		add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_styles'));
		add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts'));

		// AJAX hooks
		add_action('wp_ajax_ecb_get_list', array($plugin_ajax, 'ajax_get_list'));
		add_action('wp_ajax_ecb_get_form', array($plugin_ajax, 'ajax_get_form'));
		add_action('wp_ajax_ecb_save_button', array($plugin_ajax, 'ajax_save_button'));
		add_action('wp_ajax_ecb_delete_button', array($plugin_ajax, 'ajax_delete_button'));
	}

	private function define_public_hooks()
	{
		$plugin_public = new ELYNT_Chat_Button_Public($this->get_plugin_name(), $this->get_version());

		add_action('wp_enqueue_scripts', array($plugin_public, 'enqueue_styles'));
		add_action('wp_footer', array($plugin_public, 'render_fixed_buttons'));
		add_shortcode('ELYNT_chat_button', array($plugin_public, 'render_inline_button_shortcode'));
	}

	public function run()
	{
		// Execution handled via standard hooks, no manual trigger needed here but kept for architecture pattern.
	}

	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	public function get_version()
	{
		return $this->version;
	}

}
