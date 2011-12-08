<?php
/**
 * Reading List Books Save Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get inputs
$guid 				= get_input('guid');
$tags 				= string_to_tag_array(get_input('tags'));
$access 			= get_input('access_id');
$title 				= get_input('title');
$container_guid 	= get_input('container_guid', NULL);

// Book info
$google_id       = get_input('google_id');
$small_thumbnail = get_input('small_thumbnail', NULL);
$large_thumbnail = get_input('large_thumbnail', NULL);
$identifiers     = get_input('identifiers', NULL);

// Preset fields to grab
$simple_fields = array(
	'title', 'description', 'authors', 'canonicalVolumeLink', 'pageCount',
	'categories', 'publisher', 'publishedDate', 'printType'
);

// Sticky form
elgg_make_sticky_form('book-save-form');
if (!$title) {
	register_error(elgg_echo('readinglist:error:requiredfields'));
	forward(REFERER);
}

// Editing
if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'book') && $entity->canEdit()) {
		$book = $entity;
	} else {
		register_error(elgg_echo('readinglist:error:savebook'));
		forward(REFERER);
	}
} else { // New 
	$book = new ElggObject();
	$book->subtype = 'book';
	$book->container_guid = $container_guid;
}

$book->tags = $tags;
$book->access_id = $access;
$book->title = $title;
$book->google_id = $google_id;
$book->small_thumbnail = $small_thumbnail;
$book->large_thumbnail = $large_thumbnail;
$book->identifiers = $identifiers;

// These two sets of metadata will help to 'de-normalize' the books so to speak..
$book->average_rating = 0; // Maintain a tally of the average book rating
$book->popularity = 0;     // Intended to count the number of reading list this book appears in

// Set the rest of the book fields
foreach ($simple_fields as $field) {
	$book->$field = get_input($field);
}

// @TODO REMOVE THIS - FOR TESTING MULTIPLE CATEGORIES
//$book->categories = array('Reference', 'Art', 'Education');

// If error saving, register error and return
if (!$book->save()) {
	register_error(elgg_echo('readinglist:error:savebook'));
	forward(REFERER);
}

// Clear sticky form
elgg_clear_sticky_form('book-save-form');

// Add to river
add_to_river('river/object/book/create', 'create', get_loggedin_userid(), $book->getGUID());

// Forward on
system_message(elgg_echo('readinglist:success:savebook'));

forward("books/all");
