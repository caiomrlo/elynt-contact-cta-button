<?php

if (!defined('WPINC')) {
	exit;
}
class ELYNCOCT_Chat_Button_Ajax_Handler
{

	private $db;

	public function __construct()
	{
		$this->db = new ELYNCOCT_Chat_Button_DB_Manager();
	}

	public function ajax_get_list()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'elyncoct_admin_nonce')) {
			wp_send_json_error(array('message' => 'Invalid security token.'));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'Unauthorized access.'));
		}

		$elyncoct_buttons = $this->db->get_all_buttons();

		ob_start();
		require ELYNCOCT_PLUGIN_DIR . 'admin/views/partials/list.php';
		$html = ob_get_clean();

		wp_send_json_success(array('html' => $html));
	}

	public function ajax_get_form()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'elyncoct_admin_nonce')) {
			wp_send_json_error(array('message' => 'Invalid security token.'));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'Unauthorized access.'));
		}

		$button_id = isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : 0;
		$elyncoct_button = null;

		if ($button_id > 0) {
			$elyncoct_button = $this->db->get_button($button_id);
		}

		ob_start();
		require ELYNCOCT_PLUGIN_DIR . 'admin/views/partials/form.php';
		$html = ob_get_clean();

		wp_send_json_success(array('html' => $html));
	}

	public function ajax_save_button()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'elyncoct_admin_nonce')) {
			wp_send_json_error(array('message' => 'Invalid security token.'));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'Unauthorized access.'));
		}

		$button_id = isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : 0;

		$display_conditions = array(
			'target'        => 'everywhere',
			'post_types'    => array(),
			'taxonomies'    => array(),
			'special_pages' => array(),
			'exclusions'    => array(
				'post_types' => array(),
			),
		);

		if (isset($_POST['display_conditions']) && is_array($_POST['display_conditions'])) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized manually below per field and validated against WP schemas.
			$raw_conditions = wp_unslash($_POST['display_conditions']);
			$display_conditions['target'] = isset($raw_conditions['target']) ? sanitize_text_field($raw_conditions['target']) : 'everywhere';
			
			// Exclusions (applies to both everywhere and custom target modes)
			if (isset($raw_conditions['exclusions']['post_types']) && is_array($raw_conditions['exclusions']['post_types'])) {
				foreach ($raw_conditions['exclusions']['post_types'] as $post_type => $ex_data) {
					if (post_type_exists($post_type)) {
						if (isset($ex_data['ids']) && is_array($ex_data['ids'])) {
							$clean_ids = array_filter(array_map('absint', $ex_data['ids']));
							if (!empty($clean_ids)) {
								$display_conditions['exclusions']['post_types'][$post_type] = array(
									'ids' => array_values(array_unique($clean_ids)),
								);
							}
						}
					}
				}
			}

			if ($display_conditions['target'] === 'custom') {
				// Post Types
				if (isset($raw_conditions['post_types']) && is_array($raw_conditions['post_types'])) {
					foreach ($raw_conditions['post_types'] as $post_type => $pt_data) {
						if (post_type_exists($post_type)) {
							$enabled = isset($pt_data['enabled']) && $pt_data['enabled'] === '1';
							if ($enabled) {
								$condition = isset($pt_data['condition']) ? sanitize_text_field($pt_data['condition']) : 'all';
								$ids       = array();
								if ($condition === 'specific' && isset($pt_data['ids']) && is_array($pt_data['ids'])) {
									$ids = array_map('absint', $pt_data['ids']);
								}
								$display_conditions['post_types'][$post_type] = array(
									'condition' => $condition,
									'ids'       => $ids,
								);
							}
						}
					}
				}

				// Taxonomies (Categories, Tags, Custom Taxonomies)
				if (isset($raw_conditions['taxonomies']) && is_array($raw_conditions['taxonomies'])) {
					foreach ($raw_conditions['taxonomies'] as $taxonomy => $tax_data) {
						if (taxonomy_exists($taxonomy)) {
							$enabled = isset($tax_data['enabled']) && $tax_data['enabled'] === '1';
							if ($enabled) {
								$condition = isset($tax_data['condition']) ? sanitize_text_field($tax_data['condition']) : 'all';
								$ids       = array();
								if ($condition === 'specific' && isset($tax_data['ids']) && is_array($tax_data['ids'])) {
									$ids = array_map('absint', $tax_data['ids']);
								}
								$display_conditions['taxonomies'][$taxonomy] = array(
									'condition' => $condition,
									'ids'       => $ids,
								);
							}
						}
					}
				}

				// Special Archive & Error Pages
				if (isset($raw_conditions['special_pages']) && is_array($raw_conditions['special_pages'])) {
					$allowed_special_keys = array('blog_index', 'search', 'author', 'date', 'not_found_404');
					foreach ($allowed_special_keys as $key) {
						if (!empty($raw_conditions['special_pages'][$key])) {
							$display_conditions['special_pages'][$key] = true;
						}
					}
				}
			}
		}

		$data = array(
			'name' => isset($_POST['button_name']) ? sanitize_text_field(wp_unslash($_POST['button_name'])) : 'Unnamed',
			'type' => isset($_POST['button_type']) ? sanitize_text_field(wp_unslash($_POST['button_type'])) : 'fixed',
			'status' => isset($_POST['button_status']) ? sanitize_text_field(wp_unslash($_POST['button_status'])) : 'active',
			'options' => array(
				'text' => isset($_POST['button_text']) ? sanitize_text_field(wp_unslash($_POST['button_text'])) : '',
				'number' => isset($_POST['whatsapp_number']) ? sanitize_text_field(wp_unslash($_POST['whatsapp_number'])) : '',
				'position' => isset($_POST['button_position']) ? sanitize_text_field(wp_unslash($_POST['button_position'])) : 'right',
				'layout' => isset($_POST['button_layout']) ? sanitize_text_field(wp_unslash($_POST['button_layout'])) : 'standard',
				'initial_message' => isset($_POST['initial_message']) ? sanitize_textarea_field(wp_unslash($_POST['initial_message'])) : '',
				'bg_color' => isset($_POST['bg_color']) ? sanitize_hex_color(wp_unslash($_POST['bg_color'])) : '#25D366',
				'text_color' => isset($_POST['text_color']) ? sanitize_hex_color(wp_unslash($_POST['text_color'])) : '#ffffff',
				'icon_size' => isset($_POST['icon_size']) ? intval(wp_unslash($_POST['icon_size'])) : 24,
				'font_size' => isset($_POST['font_size']) ? intval(wp_unslash($_POST['font_size'])) : 16,
				'display_conditions' => $display_conditions,
			)
		);

		if ($button_id > 0) {
			$this->db->update_button($button_id, $data);
			wp_send_json_success(array(
				'message' => 'Button updated successfully.',
				'id' => $button_id
			));
		} else {
			$new_id = $this->db->create_button($data);
			wp_send_json_success(array(
				'message' => 'Button created successfully.',
				'id' => $new_id
			));
		}
	}

	public function ajax_delete_button()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'elyncoct_admin_nonce')) {
			wp_send_json_error(array('message' => 'Invalid security token.'));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'Unauthorized access.'));
		}

		$button_id = isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : 0;
		if ($button_id > 0) {
			$this->db->delete_button($button_id);
			wp_send_json_success(array('message' => 'Button deleted successfully.'));
		}

		wp_send_json_error(array('message' => 'Invalid ID.'));
	}

	public function ajax_search_posts()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'elyncoct_admin_nonce')) {
			wp_send_json_error(array('message' => 'Invalid security token.'));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'Unauthorized access.'));
		}

		$post_type = isset($_POST['post_type']) ? sanitize_key(wp_unslash($_POST['post_type'])) : 'post';
		$search = isset($_POST['q']) ? sanitize_text_field(wp_unslash($_POST['q'])) : '';

		if (!post_type_exists($post_type)) {
			wp_send_json_error(array('message' => 'Invalid post type.'));
		}

		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => 10,
			'post_status' => 'publish',
		);

		if (!empty($search)) {
			$args['s'] = $search;
		}

		$query = new WP_Query($args);
		$results = array();

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$results[] = array(
					'id' => get_the_ID(),
					'title' => get_the_title()
				);
			}
			wp_reset_postdata();
		}

		wp_send_json_success(array('results' => $results));
	}

	public function ajax_search_terms()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'elyncoct_admin_nonce')) {
			wp_send_json_error(array('message' => 'Invalid security token.'));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'Unauthorized access.'));
		}

		$taxonomy = isset($_POST['taxonomy']) ? sanitize_key(wp_unslash($_POST['taxonomy'])) : 'category';
		$search   = isset($_POST['q']) ? sanitize_text_field(wp_unslash($_POST['q'])) : '';

		if (!taxonomy_exists($taxonomy)) {
			wp_send_json_error(array('message' => 'Invalid taxonomy.'));
		}

		$args = array(
			'taxonomy'   => $taxonomy,
			'number'     => 10,
			'hide_empty' => false,
		);

		if (!empty($search)) {
			$args['search'] = $search;
		}

		$terms   = get_terms($args);
		$results = array();

		if (!empty($terms) && !is_wp_error($terms)) {
			foreach ($terms as $term) {
				$results[] = array(
					'id'    => $term->term_id,
					'title' => $term->name,
				);
			}
		}

		wp_send_json_success(array('results' => $results));
	}
}
