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

elgg_load_library('elgg:readinglist');

// Get inputs
$guid 				= get_input('guid');
$tags 				= string_to_tag_array(get_input('tags'));
$title 				= get_input('title');
$container_guid 	= get_input('container_guid', NULL);
$readinglist_add    = get_input('readinglist_add', FALSE);

// Book info
$google_id       = get_input('google_id');
$small_thumbnail = get_input('small_thumbnail', NULL);
$large_thumbnail = get_input('large_thumbnail', NULL);
$identifiers     = get_input('identifiers', NULL);

// Preset fields to grab (leaving out description)
$simple_fields = array(
	'title', 'authors', 'canonicalVolumeLink', 'pageCount',
	'categories', 'publisher', 'publishedDate', 'printType'
);

// Sticky form
elgg_make_sticky_form('book-save-form');

// Editing
if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'book') && $entity->canEdit()) {
		$book = $entity;
	} else {
		register_error(elgg_echo('readinglist:error:savebook'));
		forward(REFERER);
	}

	// Not saving anything other than tags
	$book->tags = $tags;

} else { // New 
	if (!$title) {
		register_error(elgg_echo('readinglist:error:requiredfields'));
		forward(REFERER);
	}

	$book = new ElggObject();
	$book->subtype = 'book';
	$book->container_guid = $container_guid;
	$book->tags = $tags;
	$book->access_id = ACCESS_LOGGED_IN; // All books are set to logged in users
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

	// Try and set full description (need a seperate api call for this, boooo)
	$description = google_books_get_volume_full_description($book->google_id);

	// If we've got it, set it
	if ($description) {
		$book->description = $description;
	} else {
		// Otherwise use whatever was passed in from the volume info list
		$book->description = get_input('description', '');
	}
}

// If error saving, register error and return
if (!$book->save()) {
	register_error(elgg_echo('readinglist:error:savebook'));
	forward(REFERER);
}

// Add a river entry
if (!$guid) {
	add_to_river('river/object/book/create', 'create', elgg_get_logged_in_user_guid(), $book->getGUID());
}

// Clear sticky form
elgg_clear_sticky_form('book-save-form');

// Add to user's book list if option is set
if (!empty($readinglist_add)) {
	add_to_user_readinglist($book->guid, elgg_get_logged_in_user_guid());
}

// Forward to book view
system_message(elgg_echo('readinglist:success:savebook'));
forward("books/view/{$book->guid}");
