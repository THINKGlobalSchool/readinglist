<?php
/**
 * Reading List Public Sidebar Module
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user_guid']  User
 * @uses $vars['book_guid']  Book guid
 */

elgg_load_library('elgg:readinglist');
elgg_load_css('elgg.readinglist');
elgg_load_js('elgg.readinglist');

$public_header = elgg_echo('readinglist:title:publicreading');

$content = '';

elgg_push_context('public_reading');

/* These options will grab any book that is on a users readinglist
and is grouped by the entity guid, to prevent dupes */
$options = array(
	'type' => 'object',
	'subtype' => 'book',
	'full_view' => false,
	'relationship' => READING_LIST_RELATIONSHIP,
	'relationship_guid' => ELGG_ENTITIES_ANY_VALUE,
	'inverse_relationship' => TRUE,
	'group_by' => 'e.guid',
	'pagination' => FALSE,
	'limit' => 5,
);

// Ignore access so the public can take a peek
$ia = elgg_get_ignore_access();
elgg_set_ignore_access(TRUE);
$content = elgg_list_entities_from_relationship($options);
$options['count'] = TRUE;
$count = elgg_get_entities_from_relationship($options);
elgg_set_ignore_access($ia);

// If theres no content, display a nice message
if (!$content) {
	$content = "<h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
}

if ($count > 5) {
	$content .= "<div class='elgg-subtext float-right'>" . elgg_view('output/url', array(
		'text' => elgg_echo('readinglist:label:viewall'), 
		'href'=> 'books/reading',
	)) . "</div>";
}

elgg_pop_context();

echo elgg_view_module('aside', $public_header, $content);