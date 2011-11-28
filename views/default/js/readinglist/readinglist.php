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

// Init function
elgg.readinglist.init = function() {
	// Click handler for book search
	$('#book-search-submit').live('click', elgg.readinglist.bookSearchSubmit);
}

// Click handler for book search
elgg.readinglist.bookSearchSubmit = function(event) {
	var term = $('#book-search-title').val();
	var container = 'books-search-results';
	$('#' + container).html("<div class='elgg-ajax-loader'></div>");
	elgg.readinglist.loadSearchResults(term, container)
	event.preventDefault();
}

// Load todo assignees into container
elgg.readinglist.loadSearchResults = function(term, container) {
	elgg.get(elgg.readinglist.loadSearchResultsURL, {
		data: {
			term: term
		},
		success: function(data){
			$("#" + container).html(data);
		}
	});
}

elgg.register_hook_handler('init', 'system', elgg.readinglist.init);
//</script>