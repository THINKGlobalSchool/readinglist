<?php
/**
 * Reading List Book Existing view
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = elgg_extract('guid', $vars);

$book = get_entity($guid);

if (!elgg_instanceof($book, 'object', 'book')) {
	return TRUE;
}

$title_label = elgg_echo('readinglist:label:titleexists', array(get_entity($book->owner_guid)->name));

elgg_push_context('book_existing');
$book_info = elgg_view_entity($book, array('full_view' => FALSE));
elgg_pop_context();

$readinglist_input = elgg_view('input/submit', array(
	'name' => 'add_to_reading_list',
	'class' => 'book-add-to-readinglist elgg-button elgg-button-submit',
	'id' => $book->guid,
	'value' => elgg_echo('readinglist:label:addtoreadinglist'),
));

$search_input = elgg_view('input/submit', array(
	'name' => 'search_anyway',
	'id' => 'book-search-anyway',
	'value' => elgg_echo('readinglist:label:searchanyway'),
));

$cancel_input = elgg_view('input/submit', array(
	'name' => 'cancel',
	'id' => 'book-search-cancel',
	'value' => elgg_echo('cancel'),
));

$content = <<<HTML
	<h3>$title_label</h3><br />
	<div id='book-existing-$guid' class='book-listing'>
		$book_info
	</div><br />
	<div class='center'>
		$readinglist_input $search_input $cancel_input
	</div>
HTML;

echo $content;