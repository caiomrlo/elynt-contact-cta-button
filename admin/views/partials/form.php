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
$elyncoct_border_size = isset($elyncoct_options['border_size']) ? intval($elyncoct_options['border_size']) : 0;
$elyncoct_border_color = isset($elyncoct_options['border_color']) ? $elyncoct_options['border_color'] : '';
$elyncoct_enable_mobile_settings = !empty($elyncoct_options['enable_mobile_settings']);
$elyncoct_position_mobile = isset($elyncoct_options['position_mobile']) ? $elyncoct_options['position_mobile'] : 'right';
$elyncoct_icon_size_mobile = isset($elyncoct_options['icon_size_mobile']) && $elyncoct_options['icon_size_mobile'] > 0 ? intval($elyncoct_options['icon_size_mobile']) : '';
$elyncoct_font_size_mobile = isset($elyncoct_options['font_size_mobile']) && $elyncoct_options['font_size_mobile'] > 0 ? intval($elyncoct_options['font_size_mobile']) : '';
$elyncoct_border_size_mobile = isset($elyncoct_options['border_size_mobile']) && $elyncoct_options['border_size_mobile'] !== '' ? intval($elyncoct_options['border_size_mobile']) : '';

$elyncoct_display_conditions   = isset($elyncoct_options['display_conditions']) ? $elyncoct_options['display_conditions'] : array();
$elyncoct_target               = isset($elyncoct_display_conditions['target']) ? $elyncoct_display_conditions['target'] : 'everywhere';
$elyncoct_post_types_config    = isset($elyncoct_display_conditions['post_types']) ? $elyncoct_display_conditions['post_types'] : array();
$elyncoct_taxonomies_config    = isset($elyncoct_display_conditions['taxonomies']) ? $elyncoct_display_conditions['taxonomies'] : array();
$elyncoct_special_pages_config = isset($elyncoct_display_conditions['special_pages']) ? $elyncoct_display_conditions['special_pages'] : array();
$elyncoct_exclusions_config    = isset($elyncoct_display_conditions['exclusions']) ? $elyncoct_display_conditions['exclusions'] : array();
$elyncoct_exclusion_pts        = isset($elyncoct_exclusions_config['post_types']) ? $elyncoct_exclusions_config['post_types'] : array();

$elyncoct_exclusion_count = 0;
if (!empty($elyncoct_exclusion_pts)) {
	foreach ($elyncoct_exclusion_pts as $elyncoct_pt_ex) {
		if (!empty($elyncoct_pt_ex['ids']) && is_array($elyncoct_pt_ex['ids'])) {
			$elyncoct_exclusion_count += count($elyncoct_pt_ex['ids']);
		}
	}
}

$elyncoct_public_post_types = get_post_types(array('public' => true), 'objects');
if (isset($elyncoct_public_post_types['attachment'])) {
	unset($elyncoct_public_post_types['attachment']);
}

$elyncoct_public_taxonomies = get_taxonomies(array('public' => true), 'objects');
if (isset($elyncoct_public_taxonomies['post_format'])) {
	unset($elyncoct_public_taxonomies['post_format']);
}
?>
<div class="ecb-header-actions">
	<h2>
		<span class="dashicons dashicons-<?php echo esc_attr($elyncoct_is_edit ? 'edit' : 'plus-alt2'); ?>"></span>
		<?php echo esc_html($elyncoct_is_edit ? __('Edit Button', 'elynt-contact-cta-button') : __('Create New Button', 'elynt-contact-cta-button')); ?>
	</h2>
	<button type="button" class="ecb-btn ecb-btn-secondary ecb-back-btn">
		<span class="dashicons dashicons-arrow-left-alt2"></span> <?php esc_html_e('Back to List', 'elynt-contact-cta-button'); ?>
	</button>
</div>

<div class="ecb-form-card">
	<form id="ecb-button-form">
		<input type="hidden" name="id" value="<?php echo esc_attr($elyncoct_btn_id); ?>">

		<!-- SEÇÃO 1: Informações Gerais -->
		<div class="ecb-form-section">
			<div class="ecb-section-header">
				<div class="ecb-section-header-text">
					<h3 class="ecb-section-title"><?php esc_html_e('General Information', 'elynt-contact-cta-button'); ?></h3>
					<p class="ecb-section-subtitle"><?php esc_html_e('Basic identifier and display type for this button.', 'elynt-contact-cta-button'); ?></p>
				</div>
			</div>
			<div class="ecb-section-body">
				<div class="ecb-form-group">
					<label for="button_name"><?php esc_html_e('Button Name', 'elynt-contact-cta-button'); ?> <span class="ecb-required">*</span></label>
					<input name="button_name" type="text" id="button_name" value="<?php echo esc_attr($elyncoct_name); ?>" required placeholder="<?php esc_attr_e('e.g. Sales Team', 'elynt-contact-cta-button'); ?>">
					<p class="ecb-help-text"><?php esc_html_e('Internal identification name.', 'elynt-contact-cta-button'); ?></p>
				</div>

				<div class="ecb-form-group">
					<label><?php esc_html_e('Status', 'elynt-contact-cta-button'); ?></label>
					<label class="ecb-switch">
						<input type="hidden" name="button_status" value="inactive">
						<input type="checkbox" class="ecb-switch-input" name="button_status" id="button_status" value="active" <?php checked($elyncoct_status, 'active'); ?>>
						<div class="ecb-switch-track">
							<div class="ecb-switch-thumb"></div>
						</div>
						<span class="ecb-switch-text" data-on="<?php esc_attr_e('Active', 'elynt-contact-cta-button'); ?>" data-off="<?php esc_attr_e('Inactive', 'elynt-contact-cta-button'); ?>"></span>
					</label>
				</div>

				<div class="ecb-form-group">
					<label for="button_type"><?php esc_html_e('Display Type', 'elynt-contact-cta-button'); ?></label>
					<select name="button_type" id="button_type">
						<option value="fixed" <?php selected($elyncoct_type, 'fixed'); ?>><?php esc_html_e('Fixed (Floating on bottom)', 'elynt-contact-cta-button'); ?></option>
						<option value="inline" <?php selected($elyncoct_type, 'inline'); ?>><?php esc_html_e('Inline (Via Shortcode)', 'elynt-contact-cta-button'); ?></option>
					</select>
					<p class="ecb-help-text"><?php esc_html_e('Fixed buttons float globally on the screen; inline buttons are placed manually via shortcode.', 'elynt-contact-cta-button'); ?></p>
				</div>
			</div>
		</div>

		<!-- SEÇÃO 2: Conteúdo & WhatsApp -->
		<div class="ecb-form-section">
			<div class="ecb-section-header">
				<div class="ecb-section-header-text">
					<h3 class="ecb-section-title"><?php esc_html_e('Button Content & WhatsApp', 'elynt-contact-cta-button'); ?></h3>
					<p class="ecb-section-subtitle"><?php esc_html_e('Configure the phone number and chat message.', 'elynt-contact-cta-button'); ?></p>
				</div>
			</div>
			<div class="ecb-section-body">
				<div class="ecb-grid-2">
					<div class="ecb-form-group">
						<label for="whatsapp_number"><?php esc_html_e('WhatsApp Number', 'elynt-contact-cta-button'); ?> <span class="ecb-required">*</span></label>
						<input name="whatsapp_number" type="tel" id="whatsapp_number" value="<?php echo esc_attr($elyncoct_number); ?>" required>
						<p class="ecb-help-text"><?php esc_html_e('Select country and enter phone number.', 'elynt-contact-cta-button'); ?></p>
					</div>

					<div class="ecb-form-group">
						<label for="button_text"><?php esc_html_e('Button Text', 'elynt-contact-cta-button'); ?></label>
						<input name="button_text" type="text" id="button_text" value="<?php echo esc_attr($elyncoct_text); ?>" placeholder="<?php esc_attr_e('e.g. Need Help? Chat with us!', 'elynt-contact-cta-button'); ?>">
						<p class="ecb-help-text"><?php esc_html_e('Call to action label (for standard layout).', 'elynt-contact-cta-button'); ?></p>
					</div>
				</div>

				<div class="ecb-form-group">
					<label for="initial_message"><?php esc_html_e('Initial Message', 'elynt-contact-cta-button'); ?></label>
					<textarea name="initial_message" id="initial_message" rows="3" placeholder="<?php esc_attr_e('e.g. Hello! I would like more information.', 'elynt-contact-cta-button'); ?>"><?php echo esc_textarea($elyncoct_initial_message); ?></textarea>
					<p class="ecb-help-text"><?php esc_html_e('Pre-filled message when the visitor opens WhatsApp.', 'elynt-contact-cta-button'); ?></p>
				</div>
			</div>
		</div>

		<!-- SEÇÃO 3: Design & Aparência -->
		<div class="ecb-form-section">
			<div class="ecb-section-header">
				<div class="ecb-section-header-text">
					<h3 class="ecb-section-title"><?php esc_html_e('Design & Appearance', 'elynt-contact-cta-button'); ?></h3>
					<p class="ecb-section-subtitle"><?php esc_html_e('Customize the visual style, position, colors, and dimensions.', 'elynt-contact-cta-button'); ?></p>
				</div>
			</div>
			<div class="ecb-section-body">
				<div class="ecb-form-group">
					<label><?php esc_html_e('Button Layout', 'elynt-contact-cta-button'); ?></label>
					<div class="ecb-layout-selector">
						<label class="ecb-layout-option">
							<input type="radio" name="button_layout" value="standard" <?php checked($elyncoct_layout, 'standard'); ?>>
							<div class="ecb-layout-preview">
								<span class="dashicons dashicons-whatsapp"></span> Text
							</div>
							<span><?php esc_html_e('Standard (Icon + Text)', 'elynt-contact-cta-button'); ?></span>
						</label>
						<label class="ecb-layout-option">
							<input type="radio" name="button_layout" value="icon_only" <?php checked($elyncoct_layout, 'icon_only'); ?>>
							<div class="ecb-layout-preview ecb-layout-icon-only">
								<span class="dashicons dashicons-whatsapp"></span>
							</div>
							<span><?php esc_html_e('Round (Icon Only)', 'elynt-contact-cta-button'); ?></span>
						</label>
					</div>
				</div>

				<!-- Fixed Position: Posicionado no Design & Aparência -->
				<div class="ecb-form-group" id="row_button_position" style="<?php echo esc_attr($elyncoct_type === 'inline' ? 'display:none;' : ''); ?>">
					<label for="button_position"><?php esc_html_e('Fixed Position', 'elynt-contact-cta-button'); ?></label>
					<select name="button_position" id="button_position">
						<option value="right" <?php selected($elyncoct_position, 'right'); ?>><?php esc_html_e('Bottom Right', 'elynt-contact-cta-button'); ?></option>
						<option value="left" <?php selected($elyncoct_position, 'left'); ?>><?php esc_html_e('Bottom Left', 'elynt-contact-cta-button'); ?></option>
						<option value="center" <?php selected($elyncoct_position, 'center'); ?>><?php esc_html_e('Bottom Center', 'elynt-contact-cta-button'); ?></option>
					</select>
					<p class="ecb-help-text"><?php esc_html_e('Screen position for floating button.', 'elynt-contact-cta-button'); ?></p>
				</div>

				<!-- Cores em 2 colunas -->
				<div class="ecb-grid-2">
					<div class="ecb-form-group">
						<label for="bg_color"><?php esc_html_e('Background Color', 'elynt-contact-cta-button'); ?></label>
						<div class="ecb-color-picker-wrap">
							<input name="bg_color" type="color" id="bg_color" value="<?php echo esc_attr($elyncoct_bg_color); ?>">
							<span class="ecb-color-hex"><?php echo esc_html($elyncoct_bg_color); ?></span>
						</div>
					</div>

					<div class="ecb-form-group">
						<label for="text_color"><?php esc_html_e('Text / Icon Color', 'elynt-contact-cta-button'); ?></label>
						<div class="ecb-color-picker-wrap">
							<input name="text_color" type="color" id="text_color" value="<?php echo esc_attr($elyncoct_text_color); ?>">
							<span class="ecb-color-hex"><?php echo esc_html($elyncoct_text_color); ?></span>
						</div>
					</div>
				</div>

				<!-- Dimensões de Ícone e Fonte em 2 colunas -->
				<div class="ecb-grid-2">
					<div class="ecb-form-group">
						<label for="icon_size"><?php esc_html_e('Icon Size (px)', 'elynt-contact-cta-button'); ?></label>
						<div class="ecb-input-with-unit">
							<input name="icon_size" type="number" id="icon_size" min="10" max="100" value="<?php echo esc_attr($elyncoct_icon_size); ?>">
							<span class="ecb-unit-badge">px</span>
						</div>
						<p class="ecb-help-text"><?php esc_html_e('Default: 24px', 'elynt-contact-cta-button'); ?></p>
					</div>

					<div class="ecb-form-group">
						<label for="font_size"><?php esc_html_e('Font Size (px)', 'elynt-contact-cta-button'); ?></label>
						<div class="ecb-input-with-unit">
							<input name="font_size" type="number" id="font_size" min="10" max="100" value="<?php echo esc_attr($elyncoct_font_size); ?>">
							<span class="ecb-unit-badge">px</span>
						</div>
						<p class="ecb-help-text"><?php esc_html_e('Default: 16px', 'elynt-contact-cta-button'); ?></p>
					</div>
				</div>

				<!-- Borda: Espessura e Cor na mesma linha (2 colunas) -->
				<div class="ecb-grid-2">
					<div class="ecb-form-group">
						<label for="border_size"><?php esc_html_e('Border Size (px)', 'elynt-contact-cta-button'); ?></label>
						<div class="ecb-input-with-unit">
							<input name="border_size" type="number" id="border_size" min="0" max="50" value="<?php echo esc_attr($elyncoct_border_size); ?>">
							<span class="ecb-unit-badge">px</span>
						</div>
						<p class="ecb-help-text"><?php esc_html_e('Default: 0px (none)', 'elynt-contact-cta-button'); ?></p>
					</div>

					<div class="ecb-form-group">
						<label for="border_color"><?php esc_html_e('Border Color', 'elynt-contact-cta-button'); ?></label>
						<div class="ecb-color-picker-wrap">
							<input name="border_color" type="color" id="border_color" value="<?php echo esc_attr(!empty($elyncoct_border_color) ? $elyncoct_border_color : '#000000'); ?>">
							<span class="ecb-color-hex"><?php echo esc_html(!empty($elyncoct_border_color) ? $elyncoct_border_color : '#000000'); ?></span>
						</div>
						<p class="ecb-help-text"><?php esc_html_e('Applied when border size > 0.', 'elynt-contact-cta-button'); ?></p>
					</div>
				</div>
			</div>
		</div>

		<!-- SEÇÃO 4: Configurações Mobile -->
		<div class="ecb-form-section ecb-mobile-section">
			<div class="ecb-collapsible-card ecb-mobile-card">
				<div class="ecb-mobile-header">
					<div class="ecb-mobile-header-info">
						<div>
							<strong><?php esc_html_e('Custom Mobile Settings (Optional)', 'elynt-contact-cta-button'); ?></strong>
							<span class="ecb-help-text" style="display: block; margin-top: 2px;"><?php esc_html_e('Customize position and dimensions specifically for mobile devices (≤ 768px).', 'elynt-contact-cta-button'); ?></span>
						</div>
					</div>
					<label class="ecb-switch">
						<input type="hidden" name="enable_mobile_settings" value="0">
						<input type="checkbox" class="ecb-switch-input" name="enable_mobile_settings" id="enable_mobile_settings" value="1" <?php checked($elyncoct_enable_mobile_settings, true); ?>>
						<div class="ecb-switch-track">
							<div class="ecb-switch-thumb"></div>
						</div>
					</label>
				</div>

				<div id="ecb_mobile_fields_container" class="ecb-mobile-body" style="<?php echo esc_attr($elyncoct_enable_mobile_settings ? '' : 'display: none;'); ?>">
					<div class="ecb-form-group" id="row_mobile_position" style="<?php echo esc_attr($elyncoct_type === 'inline' ? 'display:none;' : 'margin-bottom: 18px;'); ?>">
						<label for="position_mobile"><?php esc_html_e('Fixed Position (Mobile)', 'elynt-contact-cta-button'); ?></label>
						<select name="position_mobile" id="position_mobile">
							<option value="right" <?php selected($elyncoct_position_mobile, 'right'); ?>><?php esc_html_e('Bottom Right', 'elynt-contact-cta-button'); ?></option>
							<option value="left" <?php selected($elyncoct_position_mobile, 'left'); ?>><?php esc_html_e('Bottom Left', 'elynt-contact-cta-button'); ?></option>
							<option value="center" <?php selected($elyncoct_position_mobile, 'center'); ?>><?php esc_html_e('Bottom Center', 'elynt-contact-cta-button'); ?></option>
						</select>
						<p class="ecb-help-text"><?php esc_html_e('Position on mobile screens.', 'elynt-contact-cta-button'); ?></p>
					</div>

					<div class="ecb-grid-3">
						<div class="ecb-form-group">
							<label for="icon_size_mobile"><?php esc_html_e('Icon Size (px)', 'elynt-contact-cta-button'); ?></label>
							<div class="ecb-input-with-unit">
								<input name="icon_size_mobile" type="number" id="icon_size_mobile" min="10" max="100" value="<?php echo esc_attr($elyncoct_icon_size_mobile); ?>" placeholder="<?php echo esc_attr($elyncoct_icon_size); ?>">
								<span class="ecb-unit-badge">px</span>
							</div>
							<p class="ecb-help-text"><?php esc_html_e('Empty = desktop size', 'elynt-contact-cta-button'); ?></p>
						</div>

						<div class="ecb-form-group">
							<label for="font_size_mobile"><?php esc_html_e('Font Size (px)', 'elynt-contact-cta-button'); ?></label>
							<div class="ecb-input-with-unit">
								<input name="font_size_mobile" type="number" id="font_size_mobile" min="10" max="100" value="<?php echo esc_attr($elyncoct_font_size_mobile); ?>" placeholder="<?php echo esc_attr($elyncoct_font_size); ?>">
								<span class="ecb-unit-badge">px</span>
							</div>
							<p class="ecb-help-text"><?php esc_html_e('Empty = desktop size', 'elynt-contact-cta-button'); ?></p>
						</div>

						<div class="ecb-form-group">
							<label for="border_size_mobile"><?php esc_html_e('Border (px)', 'elynt-contact-cta-button'); ?></label>
							<div class="ecb-input-with-unit">
								<input name="border_size_mobile" type="number" id="border_size_mobile" min="0" max="50" value="<?php echo esc_attr($elyncoct_border_size_mobile); ?>" placeholder="<?php echo esc_attr($elyncoct_border_size); ?>">
								<span class="ecb-unit-badge">px</span>
							</div>
							<p class="ecb-help-text"><?php esc_html_e('Empty = desktop size', 'elynt-contact-cta-button'); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- SEÇÃO 5: Regras de Exibição & Exclusões (Apenas se Fixo) -->
		<div class="ecb-form-section" id="section_display_rules" style="<?php echo esc_attr($elyncoct_type === 'inline' ? 'display:none;' : ''); ?>">
			<div class="ecb-section-header">
				<div class="ecb-section-header-text">
					<h3 class="ecb-section-title"><?php esc_html_e('Display Rules & Targeting', 'elynt-contact-cta-button'); ?></h3>
					<p class="ecb-section-subtitle"><?php esc_html_e('Control where the floating button is displayed on your website.', 'elynt-contact-cta-button'); ?></p>
				</div>
			</div>
			<div class="ecb-section-body">
				<div id="row_button_targeting" class="ecb-form-group">
					<label for="display_target"><?php esc_html_e('Display Target', 'elynt-contact-cta-button'); ?></label>
					<select name="display_conditions[target]" id="display_target">
						<option value="everywhere" <?php selected($elyncoct_target, 'everywhere'); ?>><?php esc_html_e('Everywhere on the site', 'elynt-contact-cta-button'); ?></option>
						<option value="custom" <?php selected($elyncoct_target, 'custom'); ?>><?php esc_html_e('Specific Pages / Posts / Archives (Custom)', 'elynt-contact-cta-button'); ?></option>
					</select>

					<div class="ecb-targeting-custom-settings" style="<?php echo esc_attr($elyncoct_target === 'custom' ? '' : 'display: none;'); ?>">
						
						<!-- Section 1: Singular Post Types -->
						<div class="ecb-targeting-section">
							<div class="ecb-targeting-section-title">
								<span class="dashicons dashicons-admin-post"></span> <?php esc_html_e('Post Types (Singular Pages & Posts)', 'elynt-contact-cta-button'); ?>
							</div>
							<div class="ecb-targeting-items-list">
								<?php foreach ($elyncoct_public_post_types as $elyncoct_pt_name => $elyncoct_pt_obj) : 
									$elyncoct_pt_data      = isset($elyncoct_post_types_config[$elyncoct_pt_name]) ? $elyncoct_post_types_config[$elyncoct_pt_name] : array();
									$elyncoct_pt_enabled   = isset($elyncoct_post_types_config[$elyncoct_pt_name]);
									$elyncoct_pt_condition = isset($elyncoct_pt_data['condition']) ? $elyncoct_pt_data['condition'] : 'all';
									$elyncoct_pt_sel_ids   = isset($elyncoct_pt_data['ids']) ? $elyncoct_pt_data['ids'] : array();
								?>
									<div class="ecb-targeting-item-row ecb-post-type-row" data-post-type="<?php echo esc_attr($elyncoct_pt_name); ?>">
										<label class="ecb-checkbox-label">
											<input type="checkbox" name="display_conditions[post_types][<?php echo esc_attr($elyncoct_pt_name); ?>][enabled]" class="ecb-pt-enable-checkbox" value="1" <?php checked($elyncoct_pt_enabled, true); ?>>
											<?php echo esc_html($elyncoct_pt_obj->labels->name); ?>
										</label>
										
										<div class="ecb-targeting-sub-settings ecb-pt-settings" style="<?php echo esc_attr($elyncoct_pt_enabled ? '' : 'display: none;'); ?>">
											<div class="ecb-radio-group">
												<label>
													<input type="radio" name="display_conditions[post_types][<?php echo esc_attr($elyncoct_pt_name); ?>][condition]" value="all" <?php checked($elyncoct_pt_condition, 'all'); ?>>
													<?php
													/* translators: %s: Post type name */
													printf(esc_html__('All %s', 'elynt-contact-cta-button'), esc_html($elyncoct_pt_obj->labels->name));
													?>
												</label>
												<label>
													<input type="radio" name="display_conditions[post_types][<?php echo esc_attr($elyncoct_pt_name); ?>][condition]" value="specific" <?php checked($elyncoct_pt_condition, 'specific'); ?>>
													<?php esc_html_e('Select manually', 'elynt-contact-cta-button'); ?>
												</label>
											</div>
											
											<div class="ecb-specific-selection ecb-pt-specific-selection" style="<?php echo esc_attr($elyncoct_pt_condition === 'specific' ? '' : 'display: none;'); ?>">
												<div class="ecb-autocomplete-wrapper">
													<?php
													/* translators: %s: Post type singular name */
													$elyncoct_pt_search_placeholder = sprintf(__('Search %s...', 'elynt-contact-cta-button'), $elyncoct_pt_obj->labels->singular_name);
													?>
													<input type="text" class="ecb-post-search-input" placeholder="<?php echo esc_attr($elyncoct_pt_search_placeholder); ?>">
													<span class="spinner ecb-search-spinner"></span>
													<div class="ecb-search-results" style="display: none;"></div>
												</div>
												<div class="ecb-selected-tags-container ecb-selected-posts-tags">
													<?php 
													if (!empty($elyncoct_pt_sel_ids)) {
														$elyncoct_posts = get_posts(array(
															'post_type'      => $elyncoct_pt_name,
															'post__in'       => $elyncoct_pt_sel_ids,
															'posts_per_page' => -1,
															'post_status'    => 'any'
														));
														foreach ($elyncoct_posts as $elyncoct_post_item) {
															?>
															<span class="ecb-tag-badge ecb-post-tag" data-id="<?php echo esc_attr($elyncoct_post_item->ID); ?>">
																<?php echo esc_html($elyncoct_post_item->post_title); ?>
																<input type="hidden" name="display_conditions[post_types][<?php echo esc_attr($elyncoct_pt_name); ?>][ids][]" value="<?php echo esc_attr($elyncoct_post_item->ID); ?>">
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

						<!-- Section 2: Taxonomy Archives (Categories & Tags) -->
						<div class="ecb-targeting-section">
							<div class="ecb-targeting-section-title">
								<span class="dashicons dashicons-category"></span> <?php esc_html_e('Taxonomy Archives (Categories, Tags & Taxonomies)', 'elynt-contact-cta-button'); ?>
							</div>
							<div class="ecb-targeting-items-list">
								<?php foreach ($elyncoct_public_taxonomies as $elyncoct_tax_name => $elyncoct_tax_obj) : 
									$elyncoct_tax_data      = isset($elyncoct_taxonomies_config[$elyncoct_tax_name]) ? $elyncoct_taxonomies_config[$elyncoct_tax_name] : array();
									$elyncoct_tax_enabled   = isset($elyncoct_taxonomies_config[$elyncoct_tax_name]);
									$elyncoct_tax_condition = isset($elyncoct_tax_data['condition']) ? $elyncoct_tax_data['condition'] : 'all';
									$elyncoct_tax_sel_ids   = isset($elyncoct_tax_data['ids']) ? $elyncoct_tax_data['ids'] : array();
								?>
									<div class="ecb-targeting-item-row ecb-taxonomy-row" data-taxonomy="<?php echo esc_attr($elyncoct_tax_name); ?>">
										<label class="ecb-checkbox-label">
											<input type="checkbox" name="display_conditions[taxonomies][<?php echo esc_attr($elyncoct_tax_name); ?>][enabled]" class="ecb-tax-enable-checkbox" value="1" <?php checked($elyncoct_tax_enabled, true); ?>>
											<?php echo esc_html($elyncoct_tax_obj->labels->name); ?>
										</label>
										
										<div class="ecb-targeting-sub-settings ecb-tax-settings" style="<?php echo esc_attr($elyncoct_tax_enabled ? '' : 'display: none;'); ?>">
											<div class="ecb-radio-group">
												<label>
													<input type="radio" name="display_conditions[taxonomies][<?php echo esc_attr($elyncoct_tax_name); ?>][condition]" value="all" <?php checked($elyncoct_tax_condition, 'all'); ?>>
													<?php
													/* translators: %s: Taxonomy name */
													printf(esc_html__('All %s', 'elynt-contact-cta-button'), esc_html($elyncoct_tax_obj->labels->name));
													?>
												</label>
												<label>
													<input type="radio" name="display_conditions[taxonomies][<?php echo esc_attr($elyncoct_tax_name); ?>][condition]" value="specific" <?php checked($elyncoct_tax_condition, 'specific'); ?>>
													<?php esc_html_e('Select manually', 'elynt-contact-cta-button'); ?>
												</label>
											</div>
											
											<div class="ecb-specific-selection ecb-tax-specific-selection" style="<?php echo esc_attr($elyncoct_tax_condition === 'specific' ? '' : 'display: none;'); ?>">
												<div class="ecb-autocomplete-wrapper">
													<?php
													/* translators: %s: Taxonomy singular name */
													$elyncoct_tax_search_placeholder = sprintf(__('Search %s...', 'elynt-contact-cta-button'), $elyncoct_tax_obj->labels->singular_name);
													?>
													<input type="text" class="ecb-term-search-input" placeholder="<?php echo esc_attr($elyncoct_tax_search_placeholder); ?>">
													<span class="spinner ecb-search-spinner"></span>
													<div class="ecb-search-results" style="display: none;"></div>
												</div>
												<div class="ecb-selected-tags-container ecb-selected-terms-tags">
													<?php 
													if (!empty($elyncoct_tax_sel_ids)) {
														foreach ($elyncoct_tax_sel_ids as $elyncoct_term_id) {
															$elyncoct_term = get_term(intval($elyncoct_term_id), $elyncoct_tax_name);
															if ($elyncoct_term && !is_wp_error($elyncoct_term)) {
																?>
																<span class="ecb-tag-badge ecb-term-tag" data-id="<?php echo esc_attr($elyncoct_term->term_id); ?>">
																	<?php echo esc_html($elyncoct_term->name); ?>
																	<input type="hidden" name="display_conditions[taxonomies][<?php echo esc_attr($elyncoct_tax_name); ?>][ids][]" value="<?php echo esc_attr($elyncoct_term->term_id); ?>">
																	<span class="dashicons dashicons-no-alt ecb-remove-tag"></span>
																</span>
																<?php
															}
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

						<!-- Section 3: Special Archive & Other Pages -->
						<div class="ecb-targeting-section">
							<div class="ecb-targeting-section-title">
								<span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e('Special Archive & Other Pages', 'elynt-contact-cta-button'); ?>
							</div>
							<div class="ecb-special-pages-grid">
								<label class="ecb-checkbox-label">
									<input type="checkbox" name="display_conditions[special_pages][blog_index]" value="1" <?php checked(!empty($elyncoct_special_pages_config['blog_index']), true); ?>>
									<?php esc_html_e('Blog / Posts Index Page', 'elynt-contact-cta-button'); ?>
								</label>
								<label class="ecb-checkbox-label">
									<input type="checkbox" name="display_conditions[special_pages][search]" value="1" <?php checked(!empty($elyncoct_special_pages_config['search']), true); ?>>
									<?php esc_html_e('Search Results Page', 'elynt-contact-cta-button'); ?>
								</label>
								<label class="ecb-checkbox-label">
									<input type="checkbox" name="display_conditions[special_pages][author]" value="1" <?php checked(!empty($elyncoct_special_pages_config['author']), true); ?>>
									<?php esc_html_e('Author Archive Pages', 'elynt-contact-cta-button'); ?>
								</label>
								<label class="ecb-checkbox-label">
									<input type="checkbox" name="display_conditions[special_pages][date]" value="1" <?php checked(!empty($elyncoct_special_pages_config['date']), true); ?>>
									<?php esc_html_e('Date Archive Pages', 'elynt-contact-cta-button'); ?>
								</label>
								<label class="ecb-checkbox-label">
									<input type="checkbox" name="display_conditions[special_pages][not_found_404]" value="1" <?php checked(!empty($elyncoct_special_pages_config['not_found_404']), true); ?>>
									<?php esc_html_e('404 Error Page', 'elynt-contact-cta-button'); ?>
								</label>
							</div>
						</div>

					</div>
				</div>

				<div id="row_button_exclusions" class="ecb-form-group">
					<div class="ecb-collapsible-card ecb-exclusions-card">
						<div class="ecb-collapsible-header" id="ecb_exclusions_toggle" tabindex="0" role="button" aria-expanded="false">
							<div class="ecb-collapsible-title">
								<span class="dashicons dashicons-hidden"></span>
								<strong><?php esc_html_e('Exclusion Rules (Optional)', 'elynt-contact-cta-button'); ?></strong>
								<span class="ecb-collapsible-desc"><?php esc_html_e('Hide this button on specific posts, pages, or custom post types', 'elynt-contact-cta-button'); ?></span>
							</div>
							<div class="ecb-collapsible-indicator">
								<span class="ecb-badge ecb-badge-neutral ecb-exclusion-count" style="<?php echo esc_attr(empty($elyncoct_exclusion_count) ? 'display:none;' : ''); ?>">
									<?php echo esc_html($elyncoct_exclusion_count); ?> <?php esc_html_e('excluded', 'elynt-contact-cta-button'); ?>
								</span>
								<span class="dashicons dashicons-arrow-down-alt2 ecb-chevron"></span>
							</div>
						</div>

						<div class="ecb-collapsible-body" style="display: none;">
							<div class="ecb-exclusions-content">
								<p class="ecb-help-text" style="margin-top: 0; margin-bottom: 15px;">
									<?php esc_html_e('Select specific posts or pages where the button will be completely excluded from rendering, even if matching Display Targeting rules above.', 'elynt-contact-cta-button'); ?>
								</p>
								
								<div class="ecb-targeting-items-list">
									<?php foreach ($elyncoct_public_post_types as $elyncoct_ex_pt_name => $elyncoct_ex_pt_obj) : 
										$elyncoct_excluded_ids = isset($elyncoct_exclusion_pts[$elyncoct_ex_pt_name]['ids']) ? $elyncoct_exclusion_pts[$elyncoct_ex_pt_name]['ids'] : array();
									?>
										<div class="ecb-exclusion-item-row" data-post-type="<?php echo esc_attr($elyncoct_ex_pt_name); ?>">
											<label class="ecb-exclusion-pt-label">
												<span class="dashicons dashicons-admin-post"></span>
												<strong><?php echo esc_html($elyncoct_ex_pt_obj->labels->name); ?></strong>
											</label>
											<div class="ecb-autocomplete-wrapper">
												<?php
												/* translators: %s: Post type singular name */
												$elyncoct_ex_search_placeholder = sprintf(__('Search %s to exclude...', 'elynt-contact-cta-button'), $elyncoct_ex_pt_obj->labels->singular_name);
												?>
												<input type="text" class="ecb-exclusion-search-input" placeholder="<?php echo esc_attr($elyncoct_ex_search_placeholder); ?>">
												<span class="spinner ecb-search-spinner"></span>
												<div class="ecb-search-results" style="display: none;"></div>
											</div>
											<div class="ecb-selected-tags-container ecb-excluded-posts-tags">
												<?php 
												if (!empty($elyncoct_excluded_ids)) {
													$elyncoct_ex_posts = get_posts(array(
														'post_type'      => $elyncoct_ex_pt_name,
														'post__in'       => $elyncoct_excluded_ids,
														'posts_per_page' => -1,
														'post_status'    => 'any'
													));
													foreach ($elyncoct_ex_posts as $elyncoct_ex_p) {
														?>
														<span class="ecb-tag-badge ecb-exclusion-tag" data-id="<?php echo esc_attr($elyncoct_ex_p->ID); ?>">
															<span class="dashicons dashicons-minus"></span>
															<?php echo esc_html($elyncoct_ex_p->post_title); ?>
															<input type="hidden" name="display_conditions[exclusions][post_types][<?php echo esc_attr($elyncoct_ex_pt_name); ?>][ids][]" value="<?php echo esc_attr($elyncoct_ex_p->ID); ?>">
															<span class="dashicons dashicons-no-alt ecb-remove-tag"></span>
														</span>
														<?php
													}
												}
												?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="ecb-form-actions">
			<button type="submit" class="ecb-btn ecb-btn-primary">
				<span class="dashicons dashicons-saved"></span> <?php esc_html_e('Save Button', 'elynt-contact-cta-button'); ?>
			</button>
			<span class="spinner ecb-spinner"></span>
		</div>

		<div id="ecb-form-messages"></div>
	</form>
</div>