<?php
if (!defined('WPINC')) {
	exit;
}

$elyncoct_type = isset($elyncoct_button['type']) ? $elyncoct_button['type'] : 'inline';
$elyncoct_options = isset($elyncoct_button['options']) ? $elyncoct_button['options'] : array();

$elyncoct_text = isset($elyncoct_options['text']) ? $elyncoct_options['text'] : '';
$elyncoct_number = isset($elyncoct_options['number']) ? $elyncoct_options['number'] : '';
// Clean the number
$elyncoct_number = preg_replace('/[^0-9]/', '', $elyncoct_number);
$elyncoct_position = isset($elyncoct_options['position']) ? $elyncoct_options['position'] : 'right';
$elyncoct_layout = isset($elyncoct_options['layout']) ? $elyncoct_options['layout'] : 'standard';
$elyncoct_initial_message = isset($elyncoct_options['initial_message']) ? $elyncoct_options['initial_message'] : '';
$elyncoct_bg_color = isset($elyncoct_options['bg_color']) ? $elyncoct_options['bg_color'] : '#25D366';
$elyncoct_text_color = isset($elyncoct_options['text_color']) ? $elyncoct_options['text_color'] : '#ffffff';
$elyncoct_icon_size = isset($elyncoct_options['icon_size']) ? intval($elyncoct_options['icon_size']) : 24;
$elyncoct_font_size = isset($elyncoct_options['font_size']) ? intval($elyncoct_options['font_size']) : 16;
$elyncoct_border_size = isset($elyncoct_options['border_size']) ? intval($elyncoct_options['border_size']) : 0;
$elyncoct_border_color = isset($elyncoct_options['border_color']) ? $elyncoct_options['border_color'] : '';

$elyncoct_enable_mobile = !empty($elyncoct_options['enable_mobile_settings']);
$elyncoct_position_mobile = isset($elyncoct_options['position_mobile']) ? $elyncoct_options['position_mobile'] : 'right';
$elyncoct_icon_size_mobile = (isset($elyncoct_options['icon_size_mobile']) && intval($elyncoct_options['icon_size_mobile']) > 0) ? intval($elyncoct_options['icon_size_mobile']) : $elyncoct_icon_size;
$elyncoct_font_size_mobile = (isset($elyncoct_options['font_size_mobile']) && intval($elyncoct_options['font_size_mobile']) > 0) ? intval($elyncoct_options['font_size_mobile']) : $elyncoct_font_size;
$elyncoct_border_size_mobile = (isset($elyncoct_options['border_size_mobile']) && $elyncoct_options['border_size_mobile'] !== '') ? intval($elyncoct_options['border_size_mobile']) : $elyncoct_border_size;

$elyncoct_whatsapp_url = "https://wa.me/{$elyncoct_number}";
if (!empty($elyncoct_initial_message)) {
	$elyncoct_whatsapp_url .= "?text=" . rawurlencode($elyncoct_initial_message);
}

// Construct classes
$elyncoct_classes = array('ecb-whatsapp-button');
if ($elyncoct_type === 'fixed') {
	$elyncoct_classes[] = 'ecb-fixed';
	$elyncoct_classes[] = 'ecb-pos-' . $elyncoct_position;
	if ($elyncoct_enable_mobile && !empty($elyncoct_position_mobile)) {
		$elyncoct_classes[] = 'ecb-pos-mobile-' . $elyncoct_position_mobile;
	}
} else {
	$elyncoct_classes[] = 'ecb-inline';
}

if ($elyncoct_layout === 'icon_only') {
	$elyncoct_classes[] = 'ecb-icon-only';
}

$elyncoct_class_string = implode(' ', $elyncoct_classes);

$elyncoct_border_color_val = !empty($elyncoct_border_color) ? $elyncoct_border_color : 'transparent';
$elyncoct_style_props = array(
	'--ecb-bg-color: ' . esc_attr($elyncoct_bg_color) . ';',
	'--ecb-text-color: ' . esc_attr($elyncoct_text_color) . ';',
	'--ecb-font-size: ' . intval($elyncoct_font_size) . 'px;',
	'--ecb-icon-size: ' . intval($elyncoct_icon_size) . 'px;',
	'--ecb-border-size: ' . intval($elyncoct_border_size) . 'px;',
	'--ecb-border-color: ' . esc_attr($elyncoct_border_color_val) . ';',
);

if ($elyncoct_enable_mobile) {
	$elyncoct_style_props[] = '--ecb-font-size-mobile: ' . intval($elyncoct_font_size_mobile) . 'px;';
	$elyncoct_style_props[] = '--ecb-icon-size-mobile: ' . intval($elyncoct_icon_size_mobile) . 'px;';
	$elyncoct_style_props[] = '--ecb-border-size-mobile: ' . intval($elyncoct_border_size_mobile) . 'px;';
}

$elyncoct_style = implode(' ', $elyncoct_style_props);
?>

<a href="<?php echo esc_url($elyncoct_whatsapp_url); ?>" class="<?php echo esc_attr($elyncoct_class_string); ?>"
	style="<?php echo esc_attr($elyncoct_style); ?>" target="_blank" rel="noopener noreferrer">
	<svg xmlns="http://www.w3.org/2000/svg"
	viewBox="0 0 640
	640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
	<path

		d="M476.9 161.1C435 119.1 379.2 96 319.9 96C197.5 96 97.9 195.6 97.9 318C97.9 357.1 108.1 395.3 127.5 429L96 544L213.7 513.1C246.1 530.8 282.6 540.1 319.8 540.1L319.9 540.1C442.2 540.1 544 440.5 544 318.1C544 258.8 518.8 203.1 476.9 161.1zM319.9 502.7C286.7 502.7 254.2 493.8 225.9 477L219.2 473L149.4 491.3L168 423.2L163.6 416.2C145.1 386.8 135.4 352.9 135.4 318C135.4 216.3 218.2 133.5 320 133.5C369.3 133.5 415.6 152.7 450.4 187.6C485.2 222.5 506.6 268.8 506.5 318.1C506.5 419.9 421.6 502.7 319.9 502.7zM421.1 364.5C415.6 361.7 388.3 348.3 383.2 346.5C378.1 344.6 374.4 343.7 370.7 349.3C367 354.9 356.4 367.3 353.1 371.1C349.9 374.8 346.6 375.3 341.1 372.5C308.5 356.2 287.1 343.4 265.6 306.5C259.9 296.7 271.3 297.4 281.9 276.2C283.7 272.5 282.8 269.3 281.4 266.5C280 263.7 268.9 236.4 264.3 225.3C259.8 214.5 255.2 216 251.8 215.8C248.6 215.6 244.9 215.6 241.2 215.6C237.5 215.6 231.5 217 226.4 222.5C221.3 228.1 207 241.5 207 268.8C207 296.1 226.9 322.5 229.6 326.2C232.4 329.9 268.7 385.9 324.4 410C359.6 425.2 373.4 426.5 391 423.9C401.7 422.3 423.8 410.5 428.4 397.5C433 384.5 433 373.4 431.6 371.1C430.3 368.6 426.6 367.2 421.1 364.5z" />
	</svg>
	<?php if (!empty($elyncoct_text)): ?>
			<span class="ecb-whatsapp-text">
		<?php echo esc_html($elyncoct_text); ?></span>
	<?php endif; ?>
</a>