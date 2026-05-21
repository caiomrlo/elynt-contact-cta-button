jQuery(document).ready(function ($) {

	var $appContainer = $('#ecb-app-container');
	var iti;

	function loadList() {
		$appContainer.html('<div class="ecb-loading">Loading...</div>');
		$.post(elyncoct_admin.ajax_url, {
			action: 'elyncoct_get_list',
			nonce: elyncoct_admin.nonce
		}, function (response) {
			if (response.success) {
				$appContainer.html(response.data.html);
			} else {
				$appContainer.html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
			}
		});
	}

	function loadForm(id) {
		$appContainer.html('<div class="ecb-loading">Loading form...</div>');
		$.post(elyncoct_admin.ajax_url, {
			action: 'elyncoct_get_form',
			nonce: elyncoct_admin.nonce,
			id: id
		}, function (response) {
			if (response.success) {
				$appContainer.html(response.data.html);
				initIntlTelInput();
			} else {
				$appContainer.html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
			}
		});
	}

	function initIntlTelInput() {
		var phoneInput = document.querySelector("#whatsapp_number");
		if (phoneInput) {
			// Destroy old instance if it exists to avoid memory leaks/duplicate listeners
			if (iti && typeof iti.destroy === 'function') {
				iti.destroy();
			}

			// Prepend '+' if the value exists and doesn't start with '+' so that
			// the library can parse it as an international number and separate the dial code correctly.
			var val = phoneInput.value;
			if (val && !val.startsWith('+')) {
				phoneInput.value = '+' + val;
			}

			iti = window.intlTelInput(phoneInput, {
				initialCountry: "br",
				loadUtils: () => import(elyncoct_admin.utils_script),
				separateDialCode: true,
				countryOrder: ["br", "us", "pt"]
			});
		}
	}

	// Initialize
	loadList();

	// Handlers
	$appContainer.on('click', '.ecb-create-new', function () {
		loadForm(0);
	});

	$appContainer.on('click', '.ecb-edit-btn', function () {
		var id = $(this).data('id');
		loadForm(id);
	});

	$appContainer.on('click', '.ecb-back-btn', function () {
		loadList();
	});

	$appContainer.on('click', '.ecb-delete-btn', function () {
		if (confirm('Are you sure you want to delete this button?')) {
			var id = $(this).data('id');
			var $btn = $(this);
			$btn.prop('disabled', true).text('Deleting...');

			$.post(elyncoct_admin.ajax_url, {
				action: 'elyncoct_delete_button',
				nonce: elyncoct_admin.nonce,
				id: id
			}, function (response) {
				if (response.success) {
					loadList();
				} else {
					alert(response.data.message || 'Error deleting button');
					$btn.prop('disabled', false).text('Delete');
				}
			});
		}
	});

	$appContainer.on('change', '#button_type', function () {
		if ($(this).val() === 'inline') {
			$('#row_button_position').hide();
		} else {
			$('#row_button_position').show();
		}
	});

	$appContainer.on('submit', '#ecb-button-form', function (e) {
		e.preventDefault();
		var $form = $(this);
		var $submitBtn = $form.find('button[type="submit"]');
		var $spinner = $form.find('.ecb-spinner');
		var $messages = $('#ecb-form-messages');

		$submitBtn.prop('disabled', true);
		$spinner.addClass('is-active');
		$messages.html('');

		// Get full international number from intl-tel-input
		var fullNumber = '';
		if (iti) {
			// Validate the phone number using intl-tel-input API before submitting
			if (!iti.isValidNumber()) {
				$submitBtn.prop('disabled', false);
				$spinner.removeClass('is-active');
				$messages.html('<div class="ecb-notice ecb-notice-error"><span class="dashicons dashicons-warning"></span> Por favor, insira um número de telefone válido.</div>');
				return;
			}
			fullNumber = iti.getNumber().replace('+', '');
		} else {
			fullNumber = $('#whatsapp_number').val().replace(/\D/g, '');
		}

		var formData = $form.serializeArray();
		
		// Replace the number with the full international version
		formData = formData.map(function(item) {
			if (item.name === 'whatsapp_number') {
				item.value = fullNumber;
			}
			return item;
		});

		formData.push({ name: 'action', value: 'elyncoct_save_button' });
		formData.push({ name: 'nonce', value: elyncoct_admin.nonce });

		$.post(elyncoct_admin.ajax_url, formData, function (response) {
			$submitBtn.prop('disabled', false);
			$spinner.removeClass('is-active');

			if (response.success) {
				if (response.data && response.data.id) {
					$form.find('input[name="id"]').val(response.data.id);
					var $headerH2 = $('.ecb-header-actions h2');
					if ($headerH2.text().indexOf('Create New Button') !== -1) {
						$headerH2.html('<span class="dashicons dashicons-edit"></span> Edit Button');
					}
				}
				$messages.html('<div class="ecb-notice ecb-notice-success"><span class="dashicons dashicons-yes-alt"></span> ' + (response.data.message || 'Button saved successfully.') + '</div>');

				setTimeout(function () {
					$messages.html('');
				}, 10000);
			} else {
				var errorMsg = response.data.message || 'Error saving button';
				$messages.html('<div class="ecb-notice ecb-notice-error"><span class="dashicons dashicons-warning"></span> ' + errorMsg + '</div>');
			}
		}).fail(function () {
			$submitBtn.prop('disabled', false);
			$spinner.removeClass('is-active');
			$messages.html('<div class="ecb-notice ecb-notice-error"><span class="dashicons dashicons-warning"></span> An unexpected error occurred. Please try again.</div>');
		});
	});

});
