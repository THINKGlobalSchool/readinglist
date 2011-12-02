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
// @TODO Check for dupes
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


// Click handler for search cancel
elgg.readinglist.searchCancelClick = function(event) {
	$.fancybox.close();
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
//</script>