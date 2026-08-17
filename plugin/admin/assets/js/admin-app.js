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

	// Handlers for conditional toggling of form rows and fields
	$appContainer.on('change', '#button_type', function () {
		if ($(this).val() === 'inline') {
			$('#row_button_position').hide();
			$('#row_button_targeting').hide();
			$('#row_button_exclusions').hide();
		} else {
			$('#row_button_position').show();
			$('#row_button_targeting').show();
			$('#row_button_exclusions').show();
		}
	});

	// Toggle collapsible exclusions card
	$appContainer.on('click', '#ecb_exclusions_toggle', function () {
		var $header = $(this);
		var $body = $header.next('.ecb-collapsible-body');
		var isExpanded = $header.attr('aria-expanded') === 'true';

		$header.attr('aria-expanded', !isExpanded);
		$header.toggleClass('is-open', !isExpanded);
		$body.stop(true, true).slideToggle(250);
	});

	$appContainer.on('keydown', '#ecb_exclusions_toggle', function (e) {
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			$(this).trigger('click');
		}
	});

	function updateExclusionBadge() {
		var count = $('#row_button_exclusions').find('.ecb-exclusion-tag').length;
		var $countBadge = $('#row_button_exclusions').find('.ecb-exclusion-count');
		if (count > 0) {
			$countBadge.text(count + ' excluded').show();
		} else {
			$countBadge.hide();
		}
	}

	// Toggle custom targeting settings wrapper
	$appContainer.on('change', '#display_target', function () {
		if ($(this).val() === 'custom') {
			$('.ecb-targeting-custom-settings').slideDown(200);
		} else {
			$('.ecb-targeting-custom-settings').slideUp(200);
		}
	});

	// Toggle post type sub-settings (radio groups, etc)
	$appContainer.on('change', '.ecb-pt-enable-checkbox', function () {
		var $settings = $(this).closest('.ecb-post-type-row').find('.ecb-pt-settings');
		if ($(this).is(':checked')) {
			$settings.slideDown(200);
		} else {
			$settings.slideUp(200);
		}
	});

	// Toggle taxonomy sub-settings
	$appContainer.on('change', '.ecb-tax-enable-checkbox', function () {
		var $settings = $(this).closest('.ecb-taxonomy-row').find('.ecb-tax-settings');
		if ($(this).is(':checked')) {
			$settings.slideDown(200);
		} else {
			$settings.slideUp(200);
		}
	});

	// Toggle manual selection search bar
	$appContainer.on('change', '.ecb-radio-group input[type="radio"]', function () {
		var $specificSelection = $(this).closest('.ecb-targeting-sub-settings').find('.ecb-specific-selection');
		if ($(this).val() === 'specific') {
			$specificSelection.slideDown(200);
		} else {
			$specificSelection.slideUp(200);
		}
	});

	// Remove selected tag/badge
	$appContainer.on('click', '.ecb-remove-tag', function () {
		var $tag = $(this).closest('.ecb-tag-badge, .ecb-post-tag, .ecb-term-tag, .ecb-exclusion-tag');
		var isExclusion = $tag.hasClass('ecb-exclusion-tag');
		$tag.remove();
		if (isExclusion) {
			updateExclusionBadge();
		}
	});

	// Autocomplete logic for post/page exclusion search
	var exclusionSearchTimeout;
	$appContainer.on('input', '.ecb-exclusion-search-input', function () {
		var $input = $(this);
		var query = $input.val().trim();
		var $wrapper = $input.closest('.ecb-autocomplete-wrapper');
		var $spinner = $wrapper.find('.ecb-search-spinner');
		var $results = $wrapper.find('.ecb-search-results');
		var postType = $input.closest('.ecb-exclusion-item-row').data('post-type');

		clearTimeout(exclusionSearchTimeout);

		if (query.length < 2) {
			$results.hide().html('');
			return;
		}

		$spinner.addClass('is-active');

		exclusionSearchTimeout = setTimeout(function () {
			$.post(elyncoct_admin.ajax_url, {
				action: 'elyncoct_search_posts',
				nonce: elyncoct_admin.nonce,
				post_type: postType,
				q: query
			}, function (response) {
				$spinner.removeClass('is-active');
				if (response.success) {
					var resultsHtml = '';
					var results = response.data.results;
					if (results && results.length > 0) {
						results.forEach(function (item) {
							resultsHtml += '<div class="ecb-search-result-item" data-id="' + item.id + '" data-title="' + esc_html_attr(item.title) + '">' + esc_html(item.title) + '</div>';
						});
					} else {
						resultsHtml = '<div class="ecb-search-result-no-match">No results found</div>';
					}
					$results.html(resultsHtml).show();
				} else {
					$results.html('<div class="ecb-search-result-no-match">Error fetching results</div>').show();
				}
			}).fail(function () {
				$spinner.removeClass('is-active');
				$results.html('<div class="ecb-search-result-no-match">Network error</div>').show();
			});
		}, 350);
	});

	// Autocomplete logic for post search
	var postSearchTimeout;
	$appContainer.on('input', '.ecb-post-search-input', function () {
		var $input = $(this);
		var query = $input.val().trim();
		var $wrapper = $input.closest('.ecb-autocomplete-wrapper');
		var $spinner = $wrapper.find('.ecb-search-spinner');
		var $results = $wrapper.find('.ecb-search-results');
		var postType = $input.closest('.ecb-post-type-row').data('post-type');

		clearTimeout(postSearchTimeout);

		if (query.length < 2) {
			$results.hide().html('');
			return;
		}

		$spinner.addClass('is-active');

		postSearchTimeout = setTimeout(function () {
			$.post(elyncoct_admin.ajax_url, {
				action: 'elyncoct_search_posts',
				nonce: elyncoct_admin.nonce,
				post_type: postType,
				q: query
			}, function (response) {
				$spinner.removeClass('is-active');
				if (response.success) {
					var resultsHtml = '';
					var results = response.data.results;
					if (results && results.length > 0) {
						results.forEach(function (item) {
							resultsHtml += '<div class="ecb-search-result-item" data-id="' + item.id + '" data-title="' + esc_html_attr(item.title) + '">' + esc_html(item.title) + '</div>';
						});
					} else {
						resultsHtml = '<div class="ecb-search-result-no-match">No results found</div>';
					}
					$results.html(resultsHtml).show();
				} else {
					$results.html('<div class="ecb-search-result-no-match">Error fetching results</div>').show();
				}
			}).fail(function () {
				$spinner.removeClass('is-active');
				$results.html('<div class="ecb-search-result-no-match">Network error</div>').show();
			});
		}, 350);
	});

	// Autocomplete logic for taxonomy terms search
	var termSearchTimeout;
	$appContainer.on('input', '.ecb-term-search-input', function () {
		var $input = $(this);
		var query = $input.val().trim();
		var $wrapper = $input.closest('.ecb-autocomplete-wrapper');
		var $spinner = $wrapper.find('.ecb-search-spinner');
		var $results = $wrapper.find('.ecb-search-results');
		var taxonomy = $input.closest('.ecb-taxonomy-row').data('taxonomy');

		clearTimeout(termSearchTimeout);

		if (query.length < 2) {
			$results.hide().html('');
			return;
		}

		$spinner.addClass('is-active');

		termSearchTimeout = setTimeout(function () {
			$.post(elyncoct_admin.ajax_url, {
				action: 'elyncoct_search_terms',
				nonce: elyncoct_admin.nonce,
				taxonomy: taxonomy,
				q: query
			}, function (response) {
				$spinner.removeClass('is-active');
				if (response.success) {
					var resultsHtml = '';
					var results = response.data.results;
					if (results && results.length > 0) {
						results.forEach(function (item) {
							resultsHtml += '<div class="ecb-search-result-item" data-id="' + item.id + '" data-title="' + esc_html_attr(item.title) + '">' + esc_html(item.title) + '</div>';
						});
					} else {
						resultsHtml = '<div class="ecb-search-result-no-match">No results found</div>';
					}
					$results.html(resultsHtml).show();
				} else {
					$results.html('<div class="ecb-search-result-no-match">Error fetching results</div>').show();
				}
			}).fail(function () {
				$spinner.removeClass('is-active');
				$results.html('<div class="ecb-search-result-no-match">Network error</div>').show();
			});
		}, 350);
	});

	// Handle selection of search result (Posts & Terms)
	$appContainer.on('click', '.ecb-search-result-item', function () {
		var $item = $(this);
		var id = $item.data('id');
		var title = $item.data('title');
		var $wrapper = $item.closest('.ecb-autocomplete-wrapper');

		// If inside a post type row
		var $postRow = $item.closest('.ecb-post-type-row');
		if ($postRow.length > 0) {
			var postType = $postRow.data('post-type');
			var $tagsContainer = $postRow.find('.ecb-selected-posts-tags');

			if ($tagsContainer.find('.ecb-post-tag[data-id="' + id + '"]').length === 0) {
				var tagHtml = '<span class="ecb-tag-badge ecb-post-tag" data-id="' + id + '">' +
					esc_html(title) +
					'<input type="hidden" name="display_conditions[post_types][' + postType + '][ids][]" value="' + id + '">' +
					'<span class="dashicons dashicons-no-alt ecb-remove-tag"></span>' +
					'</span>';
				$tagsContainer.append(tagHtml);
			}
		}

		// If inside a taxonomy row
		var $taxRow = $item.closest('.ecb-taxonomy-row');
		if ($taxRow.length > 0) {
			var taxonomy = $taxRow.data('taxonomy');
			var $taxTagsContainer = $taxRow.find('.ecb-selected-terms-tags');

			if ($taxTagsContainer.find('.ecb-term-tag[data-id="' + id + '"]').length === 0) {
				var taxTagHtml = '<span class="ecb-tag-badge ecb-term-tag" data-id="' + id + '">' +
					esc_html(title) +
					'<input type="hidden" name="display_conditions[taxonomies][' + taxonomy + '][ids][]" value="' + id + '">' +
					'<span class="dashicons dashicons-no-alt ecb-remove-tag"></span>' +
					'</span>';
				$taxTagsContainer.append(taxTagHtml);
			}
		}

		// If inside an exclusion row
		var $exRow = $item.closest('.ecb-exclusion-item-row');
		if ($exRow.length > 0) {
			var exPostType = $exRow.data('post-type');
			var $exTagsContainer = $exRow.find('.ecb-excluded-posts-tags');

			if ($exTagsContainer.find('.ecb-exclusion-tag[data-id="' + id + '"]').length === 0) {
				var exTagHtml = '<span class="ecb-tag-badge ecb-exclusion-tag" data-id="' + id + '">' +
					'<span class="dashicons dashicons-minus"></span>' +
					esc_html(title) +
					'<input type="hidden" name="display_conditions[exclusions][post_types][' + exPostType + '][ids][]" value="' + id + '">' +
					'<span class="dashicons dashicons-no-alt ecb-remove-tag"></span>' +
					'</span>';
				$exTagsContainer.append(exTagHtml);
				updateExclusionBadge();
			}
		}

		// Clear search
		$wrapper.find('input[type="text"]').val('');
		$wrapper.find('.ecb-search-results').hide().html('');
	});

	// Close autocomplete results when clicking outside
	$(document).on('click', function (e) {
		if (!$(e.target).closest('.ecb-autocomplete-wrapper').length) {
			$('.ecb-search-results').hide();
		}
	});

	// Helper functions for escaping html in client-side results
	function esc_html(str) {
		return String(str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function esc_html_attr(str) {
		return String(str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

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
				$messages.html('<div class="ecb-notice ecb-notice-error"><span class="dashicons dashicons-warning"></span> Please enter a valid phone number.</div>');
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
