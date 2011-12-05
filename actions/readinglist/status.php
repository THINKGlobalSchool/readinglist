<?php
/**
 * Reading List Status Change Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = get_input('guid');

$book = get_entity($guid);

$status = get_input('status', BOOK_READING_STATUS_QUEUED);

if (!elgg_instanceof($book, 'object', 'book')) {
	register_error(elgg_echo('readinglist:error:notfound'));
	forward(REFERER);
}

// Remove existing reading status
elgg_delete_annotations(array(
	'guid' => $book->guid,
	'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()),
	'annotation_names' => 'book_reading_status',
));

// Set status
$status = create_annotation(
	$book->guid,
	'book_reading_status',
	$status,
	"",
	elgg_get_logged_in_user_guid(),
	$book->access_id
);

system_message(elgg_echo('readinglist:success:statuschanged'));
forward(REFERER);