<?php
/**
 * Reading List Controls View
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user']           User (optional)
 * @uses $vars['user_controls']  Display all user controls (true/false)
 * @uses $vars['book']           The Book
 * @uses $vars['class']          Optional class
 */

$book = $vars['book'];
$class = $vars['class'];

if (isset($vars['user'])) {
	$user = $vars['user'];
} else {
	$user = elgg_get_logged_in_user_entity();
}

// If book is on user's reading list..
if (check_entity_relationship($book->guid, READING_LIST_RELATIONSHIP, $user->guid) && $vars['user_controls']) {
	// Create status input
	$status_input = elgg_view('readinglist/status', array(
		'user_guid' => $user->guid,
		'book_guid' => $book->guid, 
	));

	$status_content = "<label>" . elgg_echo('readinglist:label:status') . ": </label>" . $status_input;

	$completed_info = elgg_view('readinglist/completed', array(
		'book_guid' => $book->guid,
		'user_guid' => $user->guid
	));
}


$button = elgg_view('readinglist/button', array('book' => $book));

$content = <<<HTML
	<div class='readinglist-listing-control {$class}'>
		$status_content
		$button
		$completed_info
	</div>
HTML;

echo $content;