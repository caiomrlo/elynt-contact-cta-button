<?php

if (!defined('WPINC')) {
	exit;
}
class ELYNCOCT_Chat_Button_Public
{

	private $plugin_name;
	private $version;
	private $db;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->db = new ELYNCOCT_Chat_Button_DB_Manager();
	}

	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, ELYNCOCT_PLUGIN_URL . 'public/css/public-style.css', array(), $this->version, 'all');
	}

	public function render_fixed_buttons()
	{
		$elyncoct_buttons = $this->db->get_active_fixed_buttons();

		if (empty($elyncoct_buttons)) {
			return;
		}

		foreach ($elyncoct_buttons as $elyncoct_button) {
			if ($this->should_display_button($elyncoct_button)) {
				$this->load_button_view($elyncoct_button);
			}
		}
	}

	private function should_display_button($elyncoct_button)
	{
		$options = isset($elyncoct_button['options']) ? $elyncoct_button['options'] : array();
		$conditions = isset($options['display_conditions']) ? $options['display_conditions'] : array();
		
		$target = isset($conditions['target']) ? $conditions['target'] : 'everywhere';
		
		if ($target === 'everywhere') {
			return true;
		}
		
		if ($target === 'custom') {
			if (!is_singular()) {
				return false;
			}
			
			$current_post_type = get_post_type();
			$post_types = isset($conditions['post_types']) ? $conditions['post_types'] : array();
			
			if (!isset($post_types[$current_post_type])) {
				return false;
			}
			
			$pt_condition = $post_types[$current_post_type];
			$condition = isset($pt_condition['condition']) ? $pt_condition['condition'] : 'all';
			
			if ($condition === 'all') {
				return true;
			}
			
			if ($condition === 'specific') {
				$ids = isset($pt_condition['ids']) ? (array) $pt_condition['ids'] : array();
				$current_id = get_the_ID();
				return in_array($current_id, $ids, true);
			}
		}
		
		return false;
	}

	public function render_inline_button_shortcode($atts)
	{
		$atts = shortcode_atts(array(
			'id' => 0,
		), $atts, 'elyncoct_chat_button');

		$id = intval($atts['id']);

		if ($id === 0) {
			return '';
		}

		$elyncoct_button = $this->db->get_button($id);

		if (!$elyncoct_button || $elyncoct_button['status'] !== 'active') {
			return '';
		}

		// Ensure we don't apply fixed positioning classes for inline buttons, although the view handles this.
		ob_start();
		$this->load_button_view($elyncoct_button);
		return ob_get_clean();
	}

	private function load_button_view($elyncoct_button)
	{
		require ELYNCOCT_PLUGIN_DIR . 'public/views/button.php';
	}
}
