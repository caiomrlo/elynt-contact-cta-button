<?php
if (!defined('WPINC')) {
	exit;
}

$is_edit = isset($button) && !empty($button);
$btn_id = $is_edit ? intval($button['id']) : 0;
$name = $is_edit ? $button['name'] : '';
$type = $is_edit ? $button['type'] : 'fixed';
$status = $is_edit ? $button['status'] : 'active';
$options = $is_edit ? $button['options'] : array();

$text = isset($options['text']) ? $options['text'] : 'Chat with us';
$number = isset($options['number']) ? $options['number'] : '';
$position = isset($options['position']) ? $options['position'] : 'right';
$layout = isset($options['layout']) ? $options['layout'] : 'standard';
$initial_message = isset($options['initial_message']) ? $options['initial_message'] : '';
$bg_color = isset($options['bg_color']) ? $options['bg_color'] : '#25D366';
$text_color = isset($options['text_color']) ? $options['text_color'] : '#ffffff';
?>
<div class="ecb-header-actions">
	<h2>
		<span class="dashicons dashicons-<?php echo esc_attr($is_edit ? 'edit' : 'plus-alt2'); ?>"></span>
		<?php echo esc_html($is_edit ? 'Edit Button' : 'Create New Button'); ?>
	</h2>
	<button class="ecb-btn ecb-btn-secondary ecb-back-btn">
		<span class="dashicons dashicons-arrow-left-alt2"></span> Back to List
	</button>
	</div>

	<div class="ecb-form-card">
		<form id="ecb-button-form">
			<input type="hidden" name="id" value="<?php echo esc_attr($btn_id); ?>">

			<div class="ecb-form-grid">
				<div class="ecb-form-group">
					<label for="button_name">Button Name <span class="ecb-required">*</span></label>
					<input name="button_name" type="text" id="button_name" value="<?php echo esc_attr($name); ?>"
						required placeholder="e.g. Sales Team">
					<p class="ecb-help-text">For internal organization only.</p>
				</div>

				<div class="ecb-form-group">
					<label>Status</label>
					<label class="ecb-switch">
						<input type="hidden" name="button_status" value="inactive">
						<input type="checkbox" class="ecb-switch-input" name="button_status" id="button_status"
							value="active" <?php checked($status, 'active'); ?>>
						<div class="ecb-switch-track">
							<div class="ecb-switch-thumb"></div>
						</div>
						<span class="ecb-switch-text" data-on="Active" data-off="Inactive"></span>
					</label>
				</div>

				<div class="ecb-form-group">
					<label for="button_type">Display Type</label>
					<select name="button_type" id="button_type">
					<option value="fixed" <?php selected($type, 'fixed'); ?>>Fixed (Global on bottom)</option>
					<option value="inline" <?php selected($type, 'inline'); ?>>Inline (Via Shortcode)</option>
					</select>
				</div>

				<div class="ecb-form-group">
					<label>Button Layout</label>
					<div class="ecb-layout-selector">
						<label class="ecb-layout-option">
							<input type="radio" name="button_layout" value="standard" <?php checked($layout, 'standard'); ?>>
							<div class="ecb-layout-preview">
								<span class="dashicons dashicons-whatsapp"></span> Text
							</div>
							<span>Standard (Icon + Text)</span>
						</label>
						<label class="ecb-layout-option">
							<input type="radio" name="button_layout" value="icon_only" <?php checked($layout, 'icon_only'); ?>>
							<div class="ecb-layout-preview ecb-layout-icon-only">
								<span class="dashicons dashicons-whatsapp"></span>
							</div>
							<span>Round (Icon Only)</span>
						</label>
					</div>
				</div>

				<div
					class="ecb-form-group" id="row_button_position" style="<?php echo esc_attr($type === 'inline' ? 'display:none;' : ''); ?>">
					<label for="button_position">Fixed Position</label>
					<select name="button_position" id="button_position"> <option value="left" <?php selected($position, 'left'); ?>>Bottom Left</option>
						<option value="right" <?php selected($position, 'right'); ?>>Bottom Right</option>
						<option value="center" <?php selected($position, 'center'); ?>>Bottom Center</option>
						</select>
				</div>

				<div class="ecb-form-group">
					<label for="button_text">Button Text</label>
					<input name="button_text" type="text" id="button_text" value="<?php echo esc_attr($text); ?>"
						placeholder="e.g. Need Help? Chat with us!">
				</div>

				<div class="ecb-form-group">
					<label for="whatsapp_number">WhatsApp Number <span class="ecb-required">*</span></label>
					<input name="whatsapp_number" type="tel" id="whatsapp_number"
						value="<?php echo esc_attr($number); ?>" required>
					<p class="ecb-help-text">Select your country and enter your number.</p>
				</div>

				<div class="ecb-form-group">
					<label for="bg_color">Background Color</label>
					<input name="bg_color" type="color" id="bg_color" value="<?php echo esc_attr($bg_color); ?>">
				</div>

				<div class="ecb-form-group">
					<label for="text_color">Text/Icon Color</label>
					<input name="text_color" type="color" id="text_color"
						value="<?php echo esc_attr($text_color); ?>">
				</div>

				<div class="ecb-form-group">

					<label for="initial_message">Initial Message</label>
					<textarea name="initial_message" id="initial_message" rows="3"
						placeholder="e.g. Hello! I would like more information."><?php echo esc_textarea($initial_message); ?></textarea>
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