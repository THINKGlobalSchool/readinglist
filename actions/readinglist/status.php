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

elgg_load_library('elgg:readinglist');

$guid = get_input('guid');

$book = get_entity($guid);

$status = get_input('status', BOOK_READING_STATUS_QUEUED);

$complete = get_input('complete_date', FALSE);

if (!elgg_instanceof($book, 'object', 'book')) {
	register_error(elgg_echo('readinglist:error:notfound'));
	forward(REFERER);
}

// Remove existing reading status
elgg_delete_annotations(array(
	'guid' => $book->guid,
	'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()),
	'annotation_names' => array('book_reading_status', 'book_complete_date'),
));

// Set status
$status_annotation = create_annotation(
	$book->guid,
	'book_reading_status',
	$status,
	"",
	elgg_get_logged_in_user_guid(),
	$book->access_id
);

// Remove reading list relationship(s)
$relationships = array(
	READING_LIST_RELATIONSHIP_QUEUED,
	READING_LIST_RELATIONSHIP_READING,
	READING_LIST_RELATIONSHIP_COMPLETE,
);

foreach ($relationships as $relationship) {
	remove_entity_relationship($book->guid, $relationship, elgg_get_logged_in_user_guid());
}

// Set reading status relationship as well (this is a bit redundant, but it makes things easier)
switch ($status) {
	case BOOK_READING_STATUS_QUEUED:
		add_entity_relationship($book->guid, READING_LIST_RELATIONSHIP_QUEUED, elgg_get_logged_in_user_guid());
		break;
	case BOOK_READING_STATUS_READING:
		add_entity_relationship($book->guid, READING_LIST_RELATIONSHIP_READING, elgg_get_logged_in_user_guid());
		break;
	case BOOK_READING_STATUS_COMPLETE:
		add_entity_relationship($book->guid, READING_LIST_RELATIONSHIP_COMPLETE, elgg_get_logged_in_user_guid());
		break;
}

// If we have a complete status and a date, set the complete annotation
if ($status == BOOK_READING_STATUS_COMPLETE && readinglist_is_valid_timestamp($complete)) {
	$complete_annotation = create_annotation(
		$book->guid,
		'book_complete_date',
		$complete,
		"",
		elgg_get_logged_in_user_guid(),
		$book->access_id
	);
}

system_message(elgg_echo('readinglist:success:statuschanged'));
forward(REFERER);