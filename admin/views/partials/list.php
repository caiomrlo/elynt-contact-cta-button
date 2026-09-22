<?php
if (!defined('WPINC')) {
	exit;
}
?>
<div class="ecb-header-actions">
	<h2><span class="dashicons dashicons-list-view"></span> Your Buttons</h2>
	<button class="ecb-btn ecb-btn-primary ecb-create-new">
		<span class="dashicons dashicons-plus-alt2"></span> Create New Button
	</button>
</div>

<div class="ecb-cards-grid">
	<?php if (!empty($elyncoct_buttons)): ?>
		<?php foreach ($elyncoct_buttons as $elyncoct_btn): ?>
			<div class="ecb-card">
				<div class="ecb-card-header">
					<h3 class="ecb-card-title"><?php echo esc_html($elyncoct_btn['name']); ?></h3>
					<span class="ecb-status-badge <?php echo esc_attr($elyncoct_btn['status']); ?>">
						<?php echo esc_html(ucfirst($elyncoct_btn['status'])); ?>
					</span>
				</div>
				<div class="ecb-card-body">
					<?php
					$elyncoct_channel = isset($elyncoct_btn['options']['channel']) ? $elyncoct_btn['options']['channel'] : 'whatsapp';
					?>
					<p class="ecb-card-meta">
						<?php if ($elyncoct_channel === 'telegram'): ?>
							<span class="ecb-channel-badge-mini">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="#229ED9"><path d="M256 8a248 248 0 1 0 0 496 248 248 0 1 0 0-496zM371 176.7c-3.7 39.2-19.9 134.4-28.1 178.3-3.5 18.6-10.3 24.8-16.9 25.4-14.4 1.3-25.3-9.5-39.3-18.7-21.8-14.3-34.2-23.2-55.3-37.2-24.5-16.1-8.6-25 5.3-39.5 3.7-3.8 67.1-61.5 68.3-66.7 .2-.7 .3-3.1-1.2-4.4s-3.6-.8-5.1-.5c-2.2 .5-37.1 23.5-104.6 69.1-9.9 6.8-18.9 10.1-26.9 9.9-8.9-.2-25.9-5-38.6-9.1-15.5-5-27.9-7.7-26.8-16.3 .6-4.5 6.7-9 18.4-13.7 72.3-31.5 120.5-52.3 144.6-62.3 68.9-28.6 83.2-33.6 92.5-33.8 2.1 0 6.6 .5 9.6 2.9 2 1.7 3.2 4.1 3.5 6.7 .5 3.2 .6 6.5 .4 9.8z"/></svg>
							</span>
							<strong><?php esc_html_e('Action:', 'elynt-contact-cta-button'); ?></strong> Telegram
						<?php elseif ($elyncoct_channel === 'custom_link'): ?>
							<span class="dashicons dashicons-admin-links" style="color: #4f46e5;"></span>
							<strong><?php esc_html_e('Action:', 'elynt-contact-cta-button'); ?></strong> <?php esc_html_e('Custom Link', 'elynt-contact-cta-button'); ?>
						<?php else: ?>
							<span class="dashicons dashicons-whatsapp" style="color: #25D366;"></span>
							<strong><?php esc_html_e('Action:', 'elynt-contact-cta-button'); ?></strong> WhatsApp
						<?php endif; ?>
					</p>
					<p class="ecb-card-meta">
						<span
							class="dashicons <?php echo $elyncoct_btn['type'] === 'fixed' ? 'dashicons-location-alt' : 'dashicons-editor-code'; ?>"></span>
						<strong><?php esc_html_e('Display:', 'elynt-contact-cta-button'); ?></strong> <?php echo esc_html(ucfirst($elyncoct_btn['type'])); ?>
					</p>
					<?php if ($elyncoct_btn['type'] === 'inline'): ?>
						<p class="ecb-card-meta ecb-shortcode-box">
							<code>[elyncoct_chat_button id="<?php echo intval($elyncoct_btn['id']); ?>"]</code>
						</p>
					<?php else: ?>
						<p class="ecb-card-meta ecb-shortcode-box">
							<em>Auto-placed on site</em>
						</p>
					<?php endif; ?>
				</div>
				<div class="ecb-card-footer">
					<button class="ecb-btn ecb-btn-secondary ecb-edit-btn" data-id="<?php echo intval($elyncoct_btn['id']); ?>">
						<span class="dashicons dashicons-edit"></span> Edit
					</button>
					<button class="ecb-btn ecb-btn-danger ecb-delete-btn" data-id="<?php echo intval($elyncoct_btn['id']); ?>">
						<span class="dashicons dashicons-trash"></span> Delete
					</button>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="ecb-empty-state">
			<span class="dashicons dashicons-warning"></span>
			<p>No buttons found. Create your first button!</p>
		</div>
	<?php endif; ?>
</div>