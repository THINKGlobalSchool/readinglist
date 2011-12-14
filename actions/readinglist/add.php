<?php
/**
 * Reading List Add Action
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

// Add reading list relationship
add_entity_relationship($book->guid, READING_LIST_RELATIONSHIP, elgg_get_logged_in_user_guid());

// Add queued relationship
add_entity_relationship($book->guid, READING_LIST_RELATIONSHIP_QUEUED, elgg_get_logged_in_user_guid());

// Add status annotation
$status = create_annotation(
	$book->guid,
	'book_reading_status',
	BOOK_READING_STATUS_QUEUED,
	"",
	elgg_get_logged_in_user_guid(),
	$book->access_id
);

// Check if the user has already rated this book
$options = array(
	'guid' => $book->guid,
	'annotation_names' => array('bookrating'),
	'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()),
	'count' => TRUE,
);

$ratings = elgg_get_annotations($options);

// If we have a rating, we'll set the user rating to the existing value
if ($ratings) {
	unset($options['count']);
	$ratings = elgg_get_annotations($options);
	$rating = $ratings[0];
	$value = $rating->value;
} else {
	// Set it to 0 otherwise
	$value = 0;
}

// Delete existing user_bookratings
elgg_delete_annotations(array(
	'guid' => $book->guid,
	'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()),
	'annotation_names' => 'user_bookrating',
));

// Create a new user_bookrating
$user_rating = create_annotation(
	$book->guid,
	'user_bookrating',
	$value,
	"",
	elgg_get_logged_in_user_guid(),
	$book->access_id
);

// Add a river entry (not sure what action type to go with here, so using a new one called 'readinglist')
add_to_river('river/relationship/readinglist/add', 'readinglist', elgg_get_logged_in_user_guid(), $book->guid);

// Set popularity
elgg_load_library('elgg:readinglist');
elgg_set_ignore_access(TRUE);
$book->popularity = readinglist_calculate_popularity($book);
elgg_set_ignore_access(FALSE);

system_message(elgg_echo('readinglist:success:readinglistadd'));
forward(REFERER);