<?php
/**
 * Readinglist Bookrating JS Include
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
elgg.provide('elgg.readinglist.bookrating');

// Init function
elgg.readinglist.bookrating.init = function() {
	// Cancel click handler
	$('div.rating-cancel > a').live('click', elgg.readinglist.bookrating.cancelClick); 
}

/**
 * Callback for bookrating input
 *
 * @param {String} value of the input
 * @param {String} link that was was clicked
 * @return void
 */
elgg.readinglist.bookrating.ratingClick = function(value, link) {
	// This will prevent things crapping out when the cancel rating
	// button is clicked.. we'll use a seperate event for this
	if (value == undefined) {
		return;
	}

	$container = $(this).closest('div.bookrating-input');

	var book_guid = $container.find('input#bookrating-input-guid').val();

	elgg.readinglist.bookrating.submitRating(value, book_guid);
}

// Helper for the cancel button
elgg.readinglist.bookrating.cancelClick = function(event) {
	$container = $(this).closest('div.bookrating-input');

	var book_guid = $container.find('input#bookrating-input-guid').val();

	elgg.readinglist.bookrating.submitRating('0', book_guid);
}

/**
 * Submit book rating
 *
 * @param {String} value of the input
 * @param {String} entity guid
 * @return void
 */
elgg.readinglist.bookrating.submitRating = function(value, entity_guid) {
	// Call rate action
	elgg.action('books/rate', {
		data: {
			guid: entity_guid,
			rating: value,
		},
		success: function(data) {
			if (data.status != -1) {
				// ..
			}
		}
	});	
}

elgg.register_hook_handler('init', 'system', elgg.readinglist.bookrating.init);
//</script>
