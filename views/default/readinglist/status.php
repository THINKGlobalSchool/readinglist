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

elgg_load_library('elgg:readinglist');

$book = get_entity($vars['book_guid']);
$user = get_entity($vars['user_guid']);

$status_info = readinglist_get_reading_status($book->guid, $user->guid);

$status = $status_info['status'];

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