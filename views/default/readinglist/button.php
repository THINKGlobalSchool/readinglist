<?php
/**
 * Reading List Add/Remove Button View
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 * @uses $vars['book']  The Book
 */

if (!elgg_is_logged_in()) {
	return TRUE;
}

$book = $vars['book'];

// Check for a group
$entity = get_entity(elgg_get_page_owner_guid());
if (!elgg_instanceof($entity, 'group') || !$entity->canEdit()) {
	$entity = elgg_get_logged_in_user_entity();
	$button_text = elgg_echo('readinglist');
} else {
	$button_text = elgg_echo('readinglist:label:groupbooks');
	$class = 'group-';
	$group_data = "<span class='readinglist-group-data' style='display: none;'>{$entity->guid}</span>";
}

// Automatically determine if book is or isn't on user/groups reading list 
if (!check_entity_relationship($book->guid, READING_LIST_RELATIONSHIP, $entity->guid)) {
	$class .= 'readinglist-add-button elgg-button-submit';
	$button_icon = elgg_view_icon('round-plus');
} else {
	$class .= 'readinglist-remove-button elgg-button-delete';
	if (elgg_in_context('reading_list') || elgg_in_context('group_reading_list')) {
		$class .= ' readinglist-fade'; // Class to control wether listing is removed from the DOM
	}
	$button_icon = elgg_view_icon('round-minus');
}

$class .= " readinglist-button";

$content = <<<HTML
	<div id='{$book->guid}' class='$class elgg-button'>
		$button_icon
		<span class='readinglist-button-text'>$button_text</span>
		$group_data
	</div>
HTML;

echo $content;