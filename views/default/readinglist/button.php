<?php
/**
 * Reading List Add/Remove Button View
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user']  User to display reviews for
 * @uses $vars['book']  The Book
 */

$book = $vars['book'];

if (isset($vars['user'])) {
	$user = $vars['user'];
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!check_entity_relationship($book->guid, READING_LIST_RELATIONSHIP, $user->guid)) {
	$class = 'readinglist-add-button';
	$button_icon = elgg_view_icon('round-plus');
} else {
	$class = 'readinglist-remove-button';
	$button_icon = elgg_view_icon('round-minus');
}

$button_text = elgg_echo('readinglist');

$content = <<<HTML
	<div id='{$book->guid}' class='$class elgg-button elgg-button-delete'>
		$button_icon
		<span class='readinglist-button-text'>$button_text</span>
	</div>
HTML;

echo $content;