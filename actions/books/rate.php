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

if ($rating > 0 && $rating <= 5) {
	// Delete existing annotations
	elgg_delete_annotations(array(
		'entity_guids' => array($book->guid),
		'annotation_owner_guids' => array($user->guid),
		'annotation_names' => array('bookrating'),
	));

	$rating = create_annotation(
		$book->guid,
		'bookrating',
		$rating,
		"",
		$user->guid,
		$book->access_id
	);

	// Check annotations
	if (!$rating) {
		register_error(elgg_echo("readinglist:error:rate"));
		forward(REFERER);
	}

	// Success, say so
	system_message(elgg_echo("readinglist:success:rate"));
	forward(REFERER);

} else if ($rating == 0) {
	// We're here if the cancel button was clicked, we'll consider this 
	// the equivalent of clearing a users rating all-together
	elgg_delete_annotations(array(
		'entity_guids' => array($book->guid),
		'annotation_owner_guids' => array($user->guid),
		'annotation_names' => 'bookrating',
	));
	
	// Success, say so
	system_message(elgg_echo("readinglist:success:rate"));
	forward(REFERER);
} else {
	register_error(elgg_echo('readinglist:error:invalidrating'));
	forward(REFERER);
}