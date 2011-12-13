<?php
/**
 * Reading List Books Rate Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$book_guid = get_input('guid', FALSE);
$rating = (int)get_input('rating', 0);

$book = get_entity($book_guid);

if (!elgg_instanceof($book, 'object', 'book')) {
	register_error(elgg_echo('readinglist:error:notfound'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

// Delete existing annotations
elgg_delete_annotations(array(
	'guid' => array($book->guid),
	'annotation_owner_guids' => array($user->guid),
	'annotation_names' => array('bookrating', 'user_bookrating'),
));

if ($rating >= 0 && $rating <= 5) {
	// If we're passed a 0, don't do anything, someone removed their rating
	if ($rating != 0) {
		$rating_annotation = create_annotation(
			$book->guid,
			'bookrating',
			$rating,
			"",
			$user->guid,
			$book->access_id
		);
	}

	// We'll set this either way
	$user_rating_annotation = create_annotation(
		$book->guid,
		'user_bookrating',
		$rating,
		"",
		$user->guid,
		$book->access_id
	);

	// Check if the user has already rated this book
	$options = array(
		'guid' => $book->guid,
		'annotation_names' => array('user_bookrating'),
		'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()),
	);

	$ratings = elgg_get_annotations($options);

} else {
	register_error(elgg_echo('readinglist:error:invalidrating'));
	forward(REFERER);
}

// Set average rating
elgg_load_library('elgg:readinglist');
elgg_set_ignore_access(TRUE);
$book->average_rating = readinglist_calculate_average_rating($book);
elgg_set_ignore_access(FALSE);

// Success, say so
system_message(elgg_echo("readinglist:success:rate"));
forward(REFERER);