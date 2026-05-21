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
	<?php if (!empty($buttons)): ?>
		<?php foreach ($buttons as $ecb_btn): ?>
			<div class="ecb-card">
				<div class="ecb-card-header">
					<h3 class="ecb-card-title"><?php echo esc_html($ecb_btn['name']); ?></h3>
					<span class="ecb-status-badge <?php echo esc_attr($ecb_btn['status']); ?>">
						<?php echo esc_html(ucfirst($ecb_btn['status'])); ?>
					</span>
				</div>
				<div class="ecb-card-body">
					<p class="ecb-card-meta">
						<span
							class="dashicons <?php echo $ecb_btn['type'] === 'fixed' ? 'dashicons-location-alt' : 'dashicons-editor-code'; ?>"></span>
						<strong>Type:</strong> <?php echo esc_html(ucfirst($ecb_btn['type'])); ?>
					</p>
					<?php if ($ecb_btn['type'] === 'inline'): ?>
						<p class="ecb-card-meta ecb-shortcode-box">
							<code>[ELYNT_chat_button id="<?php echo intval($ecb_btn['id']); ?>"]</code>
						</p>
					<?php else: ?>
						<p class="ecb-card-meta ecb-shortcode-box">
							<em>Auto-placed on site</em>
						</p>
					<?php endif; ?>
				</div>
				<div class="ecb-card-footer">
					<button class="ecb-btn ecb-btn-secondary ecb-edit-btn" data-id="<?php echo intval($ecb_btn['id']); ?>">
						<span class="dashicons dashicons-edit"></span> Edit
					</button>
					<button class="ecb-btn ecb-btn-danger ecb-delete-btn" data-id="<?php echo intval($ecb_btn['id']); ?>">
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