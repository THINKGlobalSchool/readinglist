<?php
/**
 * Reading List Books Delete Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */
$guid = get_input('guid', null);
$book = get_entity($guid);

if (elgg_instanceof($book, 'object', 'book') && $book->canEdit()) {
	// Grab reviews and delete them as well
	$options = array(
		'type' => 'object',
		'subtype' => 'book_review',
		'relationship' => BOOK_REVIEW_RELATIONSHIP,
		'relationship_guid' => $guid,
		'inverse_relationship' => TRUE,
		'limit' => 0,
	);

	$reviews = new ElggBatch('elgg_get_entities_from_relationship', $options);

	$success = 1;

	// Only admins can delete books, but ignore access anyway
	$ia = elgg_get_ignore_access();
	elgg_set_ignore_access(TRUE);
	foreach ($reviews as $review) {
		$success &= $review->delete();
	}
	elgg_set_ignore_access($ia);

	if ($success && $book->delete()) {
		system_message(elgg_echo('readinglist:success:deletebook'));
		forward("books/all");
	} else {
		register_error(elgg_echo('readinglist:error:deletebook'));
	}
} else {
	register_error(elgg_echo('readinglist:error:notfound'));
}

forward(REFERER);