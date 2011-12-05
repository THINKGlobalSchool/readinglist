<?php
/**
 * Reading List Status Control
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user_guid']  User
 * @uses $vars['book_guid']  Book guid
 */

$book = get_entity($vars['book_guid']);
$user = get_entity($vars['user_guid']);

// Options to grab the current users reading status
$options = array(
	'guid' => $book->guid,
	'annotation_names' => array('book_reading_status'),
	'annotation_owner_guids' => array($user->guid),
);

$status = elgg_get_annotations($options);

if ($status[0] && $status[0]->value !== NULL) {
	$status = $status[0]->value;
} else {
	// Shouldn't be here, but just in case
	$status = BOOK_READING_STATUS_QUEUED;
}


$input = elgg_view('input/dropdown', array(
	'name' => 'book_reading_status',
	'class' => 'book-reading-status',
	'id' => $book->guid,
	'value' => $status,
	'options_values' => array(
		BOOK_READING_STATUS_QUEUED => elgg_echo('readinglist:label:status:queued'),
		BOOK_READING_STATUS_READING => elgg_echo('readinglist:label:status:reading'),
		BOOK_READING_STATUS_COMPLETE => elgg_echo('readinglist:label:status:complete'),
	)
));

echo $input;