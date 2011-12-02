<?php
/**
 * Reading List Books Review Add Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$book_guid = get_input('guid', FALSE);
$description = get_input('description', FALSE);

elgg_make_sticky_form('book_add_review');

if (!$description) {
	register_error(elgg_echo('readinglist:error:requiredfields'));
	forward(REFERER);
}

$book = get_entity($book_guid);

if (!elgg_instanceof($book, 'object', 'book')) {
	register_error(elgg_echo('readinglist:error:notfound'));
	forward(REFERER);
}

$review = new ElggObject();
$review->subtype = 'book_review';
$review->access_id = $book->access_id;
$review->description = $description;
$review->book_guid = $book->guid;

// If error saving, register error and return
if (!$review->save()) {
	register_error(elgg_echo('readinglist:error:savereview'));
	forward(REFERER);
}

elgg_clear_sticky_form('book_add_review');

// Add review relationship
add_entity_relationship($review->guid, BOOK_REVIEW_RELATIONSHIP, $book->guid);

// Forward on
system_message(elgg_echo('readinglist:success:savereview'));

forward("books/view/{$book->guid}");