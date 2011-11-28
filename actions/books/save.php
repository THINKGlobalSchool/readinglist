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
