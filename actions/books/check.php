<?php
/**
 * Reading List Books Check Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$title = get_input('title', false);
$gid = get_input('gid', false);

// Need at either a title or gid (google id)
if (!$title && !$gid) {
	register_error(elgg_echo('readinglist:error:requiredfields'));
	forward(REFERER);
}

// Check based on title
if ($title) {
	$title = strtolower($title);

	// Batch options
	$options = array(
		'type' => 'object',
		'subtype' => 'book',
		'limit' => 0,
	);

	$books = new ElggBatch('elgg_get_entities', $options);

	// Check for a book with the same title
	foreach ($books as $book) {
		$book_title = strtolower($book->title);
		if ($book_title === $title) {
			// Return the guid
			echo $book->guid;
			forward(REFERER);
			break;
		}
	}
} else if ($gid) { // Check based on google id
	// Batch options
	$options = array(
		'type' => 'object',
		'subtype' => 'book',
		'limit' => 0,
		'metadata_name' => 'google_id',
		'metadata_value' => $gid,
		'count' => TRUE,
	);

	$books = elgg_get_entities_from_metadata($options);

	// If theres a book matching the google id..
	if ($books) {
		$options['count'] = FALSE;

		// Grab the book guid (should only be one, no dupes!)
		$books = elgg_get_entities_from_metadata($options);

		// Return the guid
		echo $books[0]->guid;
		forward(REFERER);
	}
}

// No match
echo FALSE;
forward(REFERER);