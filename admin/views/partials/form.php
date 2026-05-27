<?php
if (!defined('WPINC')) {
	exit;
}

$elyncoct_is_edit = isset($elyncoct_button) && !empty($elyncoct_button);
$elyncoct_btn_id = $elyncoct_is_edit ? intval($elyncoct_button['id']) : 0;
$elyncoct_name = $elyncoct_is_edit ? $elyncoct_button['name'] : '';
$elyncoct_type = $elyncoct_is_edit ? $elyncoct_button['type'] : 'fixed';
$elyncoct_status = $elyncoct_is_edit ? $elyncoct_button['status'] : 'active';
$elyncoct_options = $elyncoct_is_edit ? $elyncoct_button['options'] : array();

$elyncoct_text = isset($elyncoct_options['text']) ? $elyncoct_options['text'] : 'Chat with us';
$elyncoct_number = isset($elyncoct_options['number']) ? $elyncoct_options['number'] : '';
$elyncoct_position = isset($elyncoct_options['position']) ? $elyncoct_options['position'] : 'right';
$elyncoct_layout = isset($elyncoct_options['layout']) ? $elyncoct_options['layout'] : 'standard';
$elyncoct_initial_message = isset($elyncoct_options['initial_message']) ? $elyncoct_options['initial_message'] : '';
$elyncoct_bg_color = isset($elyncoct_options['bg_color']) ? $elyncoct_options['bg_color'] : '#25D366';
$elyncoct_text_color = isset($elyncoct_options['text_color']) ? $elyncoct_options['text_color'] : '#ffffff';
$elyncoct_icon_size = isset($elyncoct_options['icon_size']) ? intval($elyncoct_options['icon_size']) : 24;
$elyncoct_font_size = isset($elyncoct_options['font_size']) ? intval($elyncoct_options['font_size']) : 16;

$elyncoct_display_conditions = isset($elyncoct_options['display_conditions']) ? $elyncoct_options['display_conditions'] : array();
$elyncoct_target = isset($elyncoct_display_conditions['target']) ? $elyncoct_display_conditions['target'] : 'everywhere';
$elyncoct_post_types_config = isset($elyncoct_display_conditions['post_types']) ? $elyncoct_display_conditions['post_types'] : array();

$elyncoct_public_post_types = get_post_types(array('public' => true), 'objects');
if (isset($elyncoct_public_post_types['attachment'])) {
	unset($elyncoct_public_post_types['attachment']);
}
?>
<div class="ecb-header-actions">
	<h2>
		<span class="dashicons dashicons-<?php echo esc_attr($elyncoct_is_edit ? 'edit' : 'plus-alt2'); ?>"></span>
		<?php echo esc_html($elyncoct_is_edit ? 'Edit Button' : 'Create New Button'); ?>
	</h2>
	<button class="ecb-btn ecb-btn-secondary ecb-back-btn">
		<span class="dashicons dashicons-arrow-left-alt2"></span> Back to List
	</button>
	</div>

	<div class="ecb-form-card">
		<form id="ecb-button-form">
			<input type="hidden" name="id" value="<?php echo esc_attr($elyncoct_btn_id); ?>">

			<div class="ecb-form-grid">
				<div class="ecb-form-group">
					<label for="button_name">Button Name <span class="ecb-required">*</span></label>
					<input name="button_name" type="text" id="button_name" value="<?php echo esc_attr($elyncoct_name); ?>"
						required placeholder="e.g. Sales Team">
					<p class="ecb-help-text">For internal organization only.</p>
				</div>

				<div class="ecb-form-group">
					<label>Status</label>
					<label class="ecb-switch">
						<input type="hidden" name="button_status" value="inactive">
						<input type="checkbox" class="ecb-switch-input" name="button_status" id="button_status"
							value="active" <?php checked($elyncoct_status, 'active'); ?>>
						<div class="ecb-switch-track">
							<div class="ecb-switch-thumb"></div>
						</div>
						<span class="ecb-switch-text" data-on="Active" data-off="Inactive"></span>
					</label>
				</div>

				<div class="ecb-form-group">
					<label for="button_type">Display Type</label>
					<select name="button_type" id="button_type">
					<option value="fixed" <?php selected($elyncoct_type, 'fixed'); ?>>Fixed (Global on bottom)</option>
					<option value="inline" <?php selected($elyncoct_type, 'inline'); ?>>Inline (Via Shortcode)</option>
					</select>
				</div>

				<div class="ecb-form-group">
					<label>Button Layout</label>
					<div class="ecb-layout-selector">
						<label class="ecb-layout-option">
							<input type="radio" name="button_layout" value="standard" <?php checked($elyncoct_layout, 'standard'); ?>>
							<div class="ecb-layout-preview">
								<span class="dashicons dashicons-whatsapp"></span> Text
							</div>
							<span>Standard (Icon + Text)</span>
						</label>
						<label class="ecb-layout-option">
							<input type="radio" name="button_layout" value="icon_only" <?php checked($elyncoct_layout, 'icon_only'); ?>>
							<div class="ecb-layout-preview ecb-layout-icon-only">
								<span class="dashicons dashicons-whatsapp"></span>
							</div>
							<span>Round (Icon Only)</span>
						</label>
					</div>
				</div>

				<div
					class="ecb-form-group" id="row_button_position" style="<?php echo esc_attr($elyncoct_type === 'inline' ? 'display:none;' : ''); ?>">
					<label for="button_position">Fixed Position</label>
					<select name="button_position" id="button_position"> <option value="left" <?php selected($elyncoct_position, 'left'); ?>>Bottom Left</option>
						<option value="right" <?php selected($elyncoct_position, 'right'); ?>>Bottom Right</option>
						<option value="center" <?php selected($elyncoct_position, 'center'); ?>>Bottom Center</option>
						</select>
				</div>

				<div id="row_button_targeting" class="ecb-form-group" style="<?php echo esc_attr($elyncoct_type === 'inline' ? 'display:none;' : ''); ?>">
					<label for="display_target">Display Targeting</label>
					<select name="display_conditions[target]" id="display_target">
						<option value="everywhere" <?php selected($elyncoct_target, 'everywhere'); ?>>Todo o site (Everywhere)</option>
						<option value="custom" <?php selected($elyncoct_target, 'custom'); ?>>Páginas / Posts específicos (Custom)</option>
					</select>

					<div class="ecb-targeting-custom-settings" style="<?php echo esc_attr($elyncoct_target === 'custom' ? '' : 'display: none;'); ?>">
						<label style="font-weight:600; margin-bottom:10px; display:block;">Exibir nos tipos de posts:</label>
						<div class="ecb-post-types-list">
							<?php foreach ($elyncoct_public_post_types as $pt_name => $pt_obj) : 
								$pt_data = isset($elyncoct_post_types_config[$pt_name]) ? $elyncoct_post_types_config[$pt_name] : array();
								$enabled = isset($elyncoct_post_types_config[$pt_name]);
								$condition = isset($pt_data['condition']) ? $pt_data['condition'] : 'all';
								$selected_ids = isset($pt_data['ids']) ? $pt_data['ids'] : array();
							?>
								<div class="ecb-post-type-row" data-post-type="<?php echo esc_attr($pt_name); ?>">
									<label class="ecb-checkbox-label">
										<input type="checkbox" name="display_conditions[post_types][<?php echo esc_attr($pt_name); ?>][enabled]" class="ecb-pt-enable-checkbox" value="1" <?php checked($enabled, true); ?>>
										<?php echo esc_html($pt_obj->labels->name); ?>
									</label>
									
									<div class="ecb-pt-settings" style="<?php echo esc_attr($enabled ? '' : 'display: none;'); ?>">
										<div class="ecb-radio-group">
											<label>
												<input type="radio" name="display_conditions[post_types][<?php echo esc_attr($pt_name); ?>][condition]" value="all" <?php checked($condition, 'all'); ?>>
												Todos os <?php echo esc_html($pt_obj->labels->name); ?>
											</label>
											<label>
												<input type="radio" name="display_conditions[post_types][<?php echo esc_attr($pt_name); ?>][condition]" value="specific" <?php checked($condition, 'specific'); ?>>
												Selecionar manualmente
											</label>
										</div>
										
										<div class="ecb-pt-specific-selection" style="<?php echo esc_attr($condition === 'specific' ? '' : 'display: none;'); ?>">
											<div class="ecb-autocomplete-wrapper">
												<input type="text" class="ecb-post-search-input" placeholder="Buscar <?php echo esc_attr($pt_obj->labels->singular_name); ?>...">
												<span class="spinner ecb-search-spinner"></span>
												<div class="ecb-search-results" style="display: none;"></div>
											</div>
											<div class="ecb-selected-posts-tags">
												<?php 
												if (!empty($selected_ids)) {
													$posts = get_posts(array(
														'post_type' => $pt_name,
														'post__in' => $selected_ids,
														'posts_per_page' => -1,
														'post_status' => 'any'
													));
													foreach ($posts as $p) {
														?>
														<span class="ecb-post-tag" data-id="<?php echo esc_attr($p->ID); ?>">
															<?php echo esc_html($p->post_title); ?>
															<input type="hidden" name="display_conditions[post_types][<?php echo esc_attr($pt_name); ?>][ids][]" value="<?php echo esc_attr($p->ID); ?>">
															<span class="dashicons dashicons-no-alt ecb-remove-tag"></span>
														</span>
														<?php
													}
												}
												?>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<div class="ecb-form-group">
					<label for="button_text">Button Text</label>
					<input name="button_text" type="text" id="button_text" value="<?php echo esc_attr($elyncoct_text); ?>"
						placeholder="e.g. Need Help? Chat with us!">
				</div>

				<div class="ecb-form-group">
					<label for="whatsapp_number">WhatsApp Number <span class="ecb-required">*</span></label>
					<input name="whatsapp_number" type="tel" id="whatsapp_number"
						value="<?php echo esc_attr($elyncoct_number); ?>" required>
					<p class="ecb-help-text">Select your country and enter your number.</p>
				</div>

				<div class="ecb-form-group">
					<label for="bg_color">Background Color</label>
					<input name="bg_color" type="color" id="bg_color" value="<?php echo esc_attr($elyncoct_bg_color); ?>">
				</div>

				<div class="ecb-form-group">
					<label for="text_color">Text/Icon Color</label>
					<input name="text_color" type="color" id="text_color"
						value="<?php echo esc_attr($elyncoct_text_color); ?>">
				</div>

				<div class="ecb-form-group">
					<label for="icon_size">Icon Size (px)</label>
					<input name="icon_size" type="number" id="icon_size" min="10" max="100"
						value="<?php echo esc_attr($elyncoct_icon_size); ?>">
					<p class="ecb-help-text">Default is 24px.</p>
				</div>

				<div class="ecb-form-group">
					<label for="font_size">Font Size (px)</label>
					<input name="font_size" type="number" id="font_size" min="10" max="100"
						value="<?php echo esc_attr($elyncoct_font_size); ?>">
					<p class="ecb-help-text">Default is 16px.</p>
				</div>

				<div class="ecb-form-group">

					<label for="initial_message">Initial Message</label>
					<textarea name="initial_message" id="initial_message" rows="3"
						placeholder="e.g. Hello! I would like more information."><?php echo esc_textarea($elyncoct_initial_message); ?></textarea>
					<p class="ecb-help-text">Pre-filled message when the user opens WhatsApp.</p>
				</div>
			</div>

			<div class="ecb-form-actions">
				<button type="submit" class="ecb-btn ecb-btn-primary">
					<span class="dashicons dashicons-saved"></span> Save Button
				</button>
				<span class="spinner ecb-spinner"></span>
			</div>

			<div id="ecb-form-messages"></div>
		</form>
	</div>