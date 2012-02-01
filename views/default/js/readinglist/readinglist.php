<?php
/**
 * Reading List JS
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

?>
//<script>
elgg.provide('elgg.readinglist');

elgg.readinglist.loadSearchResultsURL = elgg.get_site_url() + 'books/search';
elgg.readinglist.loadExistingResultURL = elgg.get_site_url() + 'ajax/view/books/existing';
elgg.readinglist.loadDuplicateResultURL = elgg.get_site_url() + 'ajax/view/books/duplicate';
elgg.readinglist.loadStatusURL = elgg.get_site_url() + 'ajax/view/readinglist/status';
elgg.readinglist.loadCompleteURL = elgg.get_site_url() + 'ajax/view/readinglist/completed';

elgg.readinglist.BOOK_READING_STATUS_QUEUED = <?php echo BOOK_READING_STATUS_QUEUED; ?>;
elgg.readinglist.BOOK_READING_STATUS_READING = <?php echo BOOK_READING_STATUS_READING; ?>;
elgg.readinglist.BOOK_READING_STATUS_COMPLETE = <?php echo BOOK_READING_STATUS_COMPLETE; ?>;

// Init function
elgg.readinglist.init = function() {
	// Click handler for book search
	$('#book-search-submit').live('click', elgg.readinglist.bookSearchSubmit);

	// Click handler for search pagination
	$('#books-search-results .elgg-pagination a').live('click', elgg.readinglist.searchPaginationClick);

	// Click handler for book select submit
	$('.book-select-submit').live('click', elgg.readinglist.bookSelectSubmitClick);

	// Click handler for search anyway
	$('#book-search-anyway').live('click', elgg.readinglist.searchAnywayClick);

	// Click handler for cancel search
	$('#book-search-cancel').live('click', elgg.readinglist.searchCancelClick);

	// Click handler for readinglist add button
	$('.readinglist-add-button').live('click', elgg.readinglist.readinglistAddClick);

	// Click handler for readinglist remove button
	$('.readinglist-remove-button').live('click', elgg.readinglist.readinglistRemoveClick);

	// Click handler for group readinglist add button
	$('.group-readinglist-add-button').live('click', elgg.readinglist.groupReadinglistAddClick);

	// Click handler for group readinglist remove button
	$('.group-readinglist-remove-button').live('click', elgg.readinglist.groupReadinglistRemoveClick);


	// Click handler for add to reading list button (on the book existing form)
	$('.book-add-to-readinglist').live('click', elgg.readinglist.readinglistExistingAddClick);

	// Change handler for book status
	$('.book-reading-status').live('change', elgg.readinglist.readinglistStatusChange);

	// Change handler for book category filter
	$('#readinglist-filter-category').live('change', elgg.readinglist.filterCategoryChange);

	// Change handler for book status filter
	$('#readinglist-filter-status').live('change', elgg.readinglist.filterStatusChange);

	// Change handler for book order filter
	$('#readinglist-filter-orderby').live('change', elgg.readinglist.filterOrderbyChange);

	// Click handler for sort order filter
	$('#readinglist-filter-sort-order').live('click', elgg.readinglist.filterSortOrderClick);

	// Change handler for whos reading status filter (uses the same handler as the regular status change)
	$('#readinglist-filter-user-status').live('change', elgg.readinglist.filterStatusChange);

	// Click handler for category link
	$('.readinglist-category-link').live('click', elgg.readinglist.categoryLinkClick);

	// Click handler for review form toggle link
	$('#review-form-toggle').live('click', elgg.readinglist.reviewToggleClick);

	// Click handler for review submit button
	$('#review-submit').live('click', elgg.readinglist.reviewSubmitClick);

	// Click handler for description show less link
	$('#book-description-showless').live('click', elgg.readinglist.showLessClick);

	// Click handler for description show more link
	$('#book-description-showmore').live('click', elgg.readinglist.showMoreClick);
}

// Click handler for book search
elgg.readinglist.bookSearchSubmit = function(event) {
	var term = $('#book-search-title').val();
	var container = 'books-search-results';

	// Clone the submit button
	$original = $(this).clone();

	// Show the spinner
	$(this).replaceWith("<div id='search-loader' class='elgg-ajax-loader'></div>");

	// Store the cloned button
	$('#search-loader').data('original', $original);

	// Check for existing book by title
	elgg.action('books/check', {
		data: {
			title: term,
		},
		success: function(data) {
			if (data.status != -1) {
				if (data.output) {
					// Title match
					elgg.readinglist.loadExistingResult(data.output, container, elgg.readinglist.triggerLightbox);
				} else {
					// Load search
					elgg.readinglist.loadSearchResults(term, container, 6, 0, elgg.readinglist.triggerLightbox);
				}
			}
		}
	});

	event.preventDefault();
}

// Init and trigger search results lightbox
elgg.readinglist.triggerLightbox = function() {
	// Create and trigger fancybox
	$('#trigger-book-results').fancybox().trigger('click');
}

// Click handler for book select submit
elgg.readinglist.bookSelectSubmitClick = function(event) {
	// Grab book listing element
	$book_listing = $(this).closest('.book-listing ');

	var google_id = $book_listing.find('input[name="google_id"]').val();

	// Check for existing book by google id
	elgg.action('books/check', {
		data: {
			gid: google_id,
		},
		success: function(data) {
			if (data.status != -1) {
				if (data.output) {
					var container = 'books-search-results';

					// Google ID Match
					elgg.readinglist.loadDuplicateResult(data.output, container, elgg.readinglist.triggerLightbox);
				} else {
					// Grab the selected book and clone it
					var $book = $book_listing.clone();

					// Tweak CSS
					$book.css({'margin-left':'auto', 'margin-right':'auto'});

					// Remove the input
					$book.find('.book-select-submit').remove();

					// Add cloned book to form
					$('#books-selected-item').html($book);

					// Clear search results
					$('#books-search-results').html('');

					// Enable the save button
					$('#book-save-input').removeAttr('disabled').removeClass('elgg-state-disabled');

					// Close lightbox
					$.fancybox.close();

					// Show the rest of the save form
					$('#book-form-hidden').fadeIn();
				}
			}
		}
	});

	event.preventDefault();
}

// Click handler for search results pagination
elgg.readinglist.searchPaginationClick = function(event) {
	// Make pagination load in the container
	$container = $(this).closest('#books-search-results');

	// Set container height so it doesn't look weird when reloading
	var height = $container.height()

	// Add spinner with some css applied, and load the href
	$container.html("<div style='height: 100%' class='elgg-ajax-loader'></div>").css({
		'height': height,
	}).load($(this).attr('href'), function() {
		// Reset height
		$(this).css({'height':'auto'});
	});

	event.preventDefault();
}

// Click handler for search anyway
elgg.readinglist.searchAnywayClick = function(event) {
	var term = $('#book-search-title').val();
	var container = 'books-search-results';

	// Clone the submit button
	$original = $(this).clone();

	// Nuke the remove button for aesthetic reasons
	$('#book-search-cancel').remove();

	// Show the spinner
	$(this).replaceWith("<div id='search-loader' class='elgg-ajax-loader'></div>");

	// Store the cloned button
	$('#search-loader').data('original', $original);

	// Load search
	elgg.readinglist.loadSearchResults(term, container, 6, 0, elgg.readinglist.triggerLightbox);

	event.preventDefault();
}


/**
 * Click handler for search cancel
 */
elgg.readinglist.searchCancelClick = function(event) {
	$.fancybox.close();
}

// Click handler for readinglist add button
elgg.readinglist.readinglistAddClick = function(event) {
	var guid = $(this).attr('id');

	$_this = $(this);

	// Add to readinglist
	elgg.action('readinglist/add', {
		data: {
			guid: guid,
		},
		success: function(data) {
			if (data.status != -1) {
				$_this.removeClass('readinglist-add-button').addClass('readinglist-remove-button');
				$_this.find('.elgg-icon-round-plus').removeClass('elgg-icon-round-plus').addClass('elgg-icon-round-minus');
			}

			// Fill the status container (if its on the page)
			var url = elgg.readinglist.loadStatusURL + "?user_guid=" + elgg.get_logged_in_user_guid() + "&book_guid=" + guid;

			elgg.get(url, {
				success: function(data){
					// Load data to container on success
					var label_text = elgg.echo('readinglist:label:status') + ': ';
					var label = "<br /><label>" + label_text + "</label>";
					var completed = "<div class='book-completed-container elgg-subtext'></div>";
					$('.book-full-status-container').html(label + data + completed).show();
				}
			});
		}
	});

	event.preventDefault();
}

/**
 * Click handler for add to readinglist (on the existing form)
 */
elgg.readinglist.readinglistExistingAddClick = function() {
	var guid = $(this).attr('id');

	var user = elgg.get_logged_in_user_entity();

	var fwd_url = elgg.get_site_url() + "profile/" + user.username + "/readinglist";

	// Add to readinglist
	elgg.action('readinglist/add', {
		data: {
			guid: guid,
		},
		success: function(data) {
			if (data.status != -1) {
				// Forward to reading list
				window.location = fwd_url;
			}
		}
	});
}

// Click handler for readinglist remove button
elgg.readinglist.readinglistRemoveClick = function(event) {
	var guid = $(this).attr('id');

	$_this = $(this);

	// Remove from readinglist
	elgg.action('readinglist/remove', {
		data: {
			guid: guid,
		},
		success: function(data) {
			if (data.status != -1) {
				$_this.removeClass('readinglist-remove-button').addClass('readinglist-add-button');
				$_this.find('.elgg-icon-round-minus').removeClass('elgg-icon-round-minus').addClass('elgg-icon-round-plus');

				// Nuke listing, check for fade class
				if ($_this.hasClass('readinglist-fade')) {
					$_this.closest('li.elgg-item').fadeOut('slow', function() {
						$(this).remove();
					});
				}

				// Hide the status container (if its on the page)
				$('.book-full-status-container').hide();
			}
		}
	});

	event.preventDefault();
}

// Click handler for group readinglist add button
elgg.readinglist.groupReadinglistAddClick = function(event) {
	var group_guid = $(this).find('.readinglist-group-data').html();
	var book_guid = $(this).attr('id');

	$_this = $(this);

	// Add to readinglist
	elgg.action('readinglist/addgroup', {
		data: {
			book_guid: book_guid,
			group_guid: group_guid,
		},
		success: function(data) {
			if (data.status != -1) {
				$_this.removeClass('group-readinglist-add-button').addClass('group-readinglist-remove-button');
				$_this.find('.elgg-icon-round-plus').removeClass('elgg-icon-round-plus').addClass('elgg-icon-round-minus');
			}
		}
	});

	event.preventDefault();
}

// Click handler for group readinglist remove button
elgg.readinglist.groupReadinglistRemoveClick = function(event) {
	var group_guid = $(this).find('.readinglist-group-data').html();
	var book_guid = $(this).attr('id');

	$_this = $(this);

	// Remove from readinglist
	elgg.action('readinglist/removegroup', {
		data: {
			book_guid: book_guid,
			group_guid: group_guid,
		},
		success: function(data) {
			if (data.status != -1) {
				$_this.removeClass('group-readinglist-remove-button').addClass('group-readinglist-add-button');
				$_this.find('.elgg-icon-round-minus').removeClass('elgg-icon-round-minus').addClass('elgg-icon-round-plus');

				// Nuke listing, check for fade class
				if ($_this.hasClass('readinglist-fade')) {
					$_this.closest('li.elgg-item').fadeOut('slow', function() {
						$(this).remove();
					});
				}
			}
		}
	});

	event.preventDefault();
}

// Change handler for status input
elgg.readinglist.readinglistStatusChange = function(event) {
	var guid = $(this).attr('id');
	var status = $(this).val();

	$_this = $(this);

	// Remove from readinglist
	elgg.action('readinglist/status', {
		data: {
			guid: guid,
			status: status,
		},
		success: function(data) {
			if (data.status != -1) {
				// Grab the completed container
				$book_completed = $_this.closest('.book').find('.book-completed-container');

				// Hide the complete box if changing status to other than complete
				if (status != elgg.readinglist.BOOK_READING_STATUS_COMPLETE) {
					$book_completed.hide();
				} else {
					// Complete selected, show or load the complete view
					var url = elgg.readinglist.loadCompleteURL + "?user_guid=" + elgg.get_logged_in_user_guid() + "&book_guid=" + guid;

					elgg.get(url, {
						success: function(data){
							// Load data to container on success
							$book_completed.replaceWith(data);

							// Make sure its showing
							$book_completed.show();
						}
					});
				}
			}
		}
	});

	event.preventDefault();
}

/**
 * Change handler for category filter change
 */
elgg.readinglist.filterCategoryChange = function(event) {
	$module = $('.readinglist-module');
	$category_input = $module.find('div.options > input#category');
	$category_input.val(escape($(this).val()));

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

/**
 * Change handler for status filter change
 */
elgg.readinglist.filterStatusChange = function(event) {
	$module = $('.readinglist-module');
	$status_input = $module.find('div.options > input#status');
	$status_input.val($(this).val());

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

/**
 * Change handler for order filter change
 */
elgg.readinglist.filterOrderbyChange = function(event) {
	$module = $('.readinglist-module');
	$orderby_input = $module.find('div.options > input#order_by');
	$orderby_input.val($(this).val());

	console.log($orderby_input.val());

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

/**
 * Click handler for sort order filter
 */
elgg.readinglist.filterSortOrderClick = function(event) {
	var order = $(this).attr('title');

	$module = $('.readinglist-module');
	$sortorder_input = $module.find('div.options > input#sort_order');
	$sortorder_input.val(order);

	if (order == 'desc') {
		$(this).html(elgg.echo('readinglist:label:sortasc'));
		$(this).attr('title', 'asc');
	} else {
		$(this).html(elgg.echo('readinglist:label:sortdesc'));
		$(this).attr('title', 'desc');
	}

	console.log($sortorder_input.val());

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

/**
 * Click handler for category links
 */
elgg.readinglist.categoryLinkClick = function(event) {
	var category = $(this).attr('title');

	$module = $('.readinglist-module');
	$category_input = $module.find('div.options > input#category');
	$category_input.val(escape(category));

	// Set the dropdown value as well
	$('select#readinglist-filter-category').val(category);

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

/**
 * Click handler for review form toggle link
 */
elgg.readinglist.reviewToggleClick = function(event) {
	if ($(this).html() == elgg.echo('readinglist:label:togglereviews')) {
		$(this).html(elgg.echo('readinglist:label:hidereviewform'));
	} else {
		$(this).html(elgg.echo('readinglist:label:togglereviews'));
	}
	event.preventDefault();
}

/**
 * Click handler for review submit click
 */
elgg.readinglist.reviewSubmitClick = function(event) {
	// Check to see if the user has supplied a rating
	if (!$('input[name="rating"]:checked').val()) {
		// If nothing is set, display an error and prevent the form from being submitted
		$('#rating-error').html(elgg.echo('readinglist:error:ratingrequired'));
		event.preventDefault();
	} else {
		// Clear error and carry on..
		$('#rating-error').html('');
		$(this).closest('form').submit();
		event.preventDefault();
	}
}

/**
 * Click handler for description show less link
 */
elgg.readinglist.showLessClick = function(event) {
	$('#book-description-full').hide();
	$('#book-description-excerpt').show();
	event.preventDefault();
}

/**
 * Click handler for description show more link
 */
elgg.readinglist.showMoreClick = function(event) {
	$('#book-description-excerpt').hide();
	$('#book-description-full').show();
	event.preventDefault();
}

/**
 * Load search results into container
 *
 * @param {String}   term        search term
 * @param {String}   container   id of container to load
 * @param {Integer}  limit       limit of items to load
 * @param {Integer}  offset      offset of items to load
 * @param {Function} callback    function to call on success
 *
 * @return void
 */
elgg.readinglist.loadSearchResults = function(term, container, limit, offset, callback) {
	elgg.get(elgg.readinglist.loadSearchResultsURL, {
		data: {
			term: term,
			limit: limit,
			offset: offset,
		},
		success: function(data){
			// Load data to container on success
			$("#" + container).html(data);

			// Replace the loader with the original submit button
			$('#search-loader').replaceWith($('#search-loader').data('original'));

			// Call the callback (if supplied)
			callback();
		}
	});
}

/**
 * Modified version of the elgg initDatePicker function
 *
 * Uses the class .elgg-input-date as the selector.
 *
 * @return void
 */
elgg.readinglist.initDatePicker = function() {
	$('.elgg-input-date').datepicker({
		// ISO-8601
		dateFormat: 'yy-mm-dd',
		onSelect: function(dateText) {
			// convert to unix timestamp
			var date = $.datepicker.parseDate('yy-mm-dd', dateText);
			var timestamp = $.datepicker.formatDate('@', date);
			timestamp = timestamp / 1000;

			var $_this = $($.datepicker._lastInput);

			// Find the book guid
			var guid = $_this.closest('.book-completed-container').find('.book-guid-hidden').val();

			// Set complete date
			elgg.action('readinglist/status', {
				data: {
					guid: guid,
					status: elgg.readinglist.BOOK_READING_STATUS_COMPLETE,
					complete_date: timestamp,
				},
				success: function(data) {
					if (data.status != -1) {
						// Grab the completed container
						$book_completed = $_this.closest('.book').find('.book-completed-container');

						// Complete selected, show or load the complete view
						var url = elgg.readinglist.loadCompleteURL + "?user_guid=" + elgg.get_logged_in_user_guid() + "&book_guid=" + guid;
						elgg.get(url, {
							success: function(data){
								// Load data to container on success
								$book_completed.replaceWith(data);

								// Make sure its showing
								$book_completed.show();
							}
						});
					}
				}
			});
		}
	});
}

/**
 * Load content for existing book match
 *
 * @param {String}   guid        book guid
 * @param {String}   container   id of container to load
 * @param {Function} callback    function to call on success
 *
 * @return void
 */
elgg.readinglist.loadExistingResult = function(guid, container, callback) {

	var url = elgg.readinglist.loadExistingResultURL + '?guid=' + guid;

	// Load the container
	$("#" + container).load(url, function() {
		// Replace the loader with the original submit button
		$('#search-loader').replaceWith($('#search-loader').data('original'));

		// Call the callback (if supplied)
		callback();
	});
}

/**
 * Load content for duplicate book match
 *
 * @param {String}   guid        book guid
 * @param {String}   container   id of container to load
 * @param {Function} callback    function to call on success
 *
 * @return void
 */
elgg.readinglist.loadDuplicateResult = function(guid, container, callback) {

	var url = elgg.readinglist.loadDuplicateResultURL + '?guid=' + guid;

	// Load the container
	$("#" + container).load(url, function() {
		// Call the callback (if supplied)
		callback();
	});
}

elgg.register_hook_handler('init', 'system', elgg.readinglist.init);