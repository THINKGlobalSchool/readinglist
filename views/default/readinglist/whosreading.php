<?php
/**
 * Reading List "Who's reading" sidebar module
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['guid'] Book guid
 */

$title = elgg_echo('readinglist:label:whoreading');
 
$content = "<span class='whos-reading-show'>" . elgg_echo('readinglist:label:show') . ": </span>";

$content .= elgg_view('input/dropdown', array(
	'id' => 'readinglist-filter-user-status',
	'class' => 'readinglist-filter',
	'options_values' => array(
		'any' => elgg_echo('readinglist:label:all'),
		BOOK_READING_STATUS_QUEUED => elgg_echo('readinglist:label:status:queued'),
		BOOK_READING_STATUS_READING => elgg_echo('readinglist:label:status:reading'),
		BOOK_READING_STATUS_COMPLETE => elgg_echo('readinglist:label:status:complete'),
	)
));

$content .= elgg_view('modules/genericmodule', array(
	'view' => 'books/modules/reading',
	'module_id' => 'readinglist-whos-reading-module',
	'module_class' => 'readinglist-module',
	'view_vars' => array(
		'book_guid' => $vars['guid'],
		'status' => 'any',
	),
));

echo elgg_view_module('aside', $title, $content);