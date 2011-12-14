<?php
/**
 * Reading List Remove Action
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

if (!elgg_instanceof($book, 'object', 'book')) {
	register_error(elgg_echo('readinglist:error:notfound'));
	forward(REFERER);
}

// Remove reading list relationship(s)
$relationships = array(
	READING_LIST_RELATIONSHIP,
	READING_LIST_RELATIONSHIP_QUEUED,
	READING_LIST_RELATIONSHIP_READING,
	READING_LIST_RELATIONSHIP_COMPLETE,
);

foreach ($relationships as $relationship) {
	remove_entity_relationship($book->guid, $relationship, elgg_get_logged_in_user_guid());
}

// Remove any reading status
elgg_delete_annotations(array(
	'guid' => $book->guid,
	'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()),
	'annotation_names' => 'book_reading_status',
));

// Remove river entry
elgg_delete_river(array(
	'object_guid' => $book->guid,
	'subject_guid' => elgg_get_logged_in_user_guid(),
	'action_type' => 'readinglist',
));

// Set popularity
elgg_load_library('elgg:readinglist');
elgg_set_ignore_access(TRUE);
$book->popularity = readinglist_calculate_popularity($book);
elgg_set_ignore_access(FALSE);

system_message(elgg_echo('readinglist:success:readinglistremove'));
forward(REFERER);