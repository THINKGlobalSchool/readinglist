<?php
/**
 * Reading List Completed Output
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

$status_info = readinglist_get_reading_status($vars['book_guid'], $vars['user_guid']);

$status = $status_info['status'];

$content = "<div class='book-completed-container elgg-subtext'>";

if ($status == BOOK_READING_STATUS_COMPLETE) {
	$annotation = $status_info['annotation'];

	$completed = date('F j, Y', $annotation->time_created);

	$completed = elgg_echo('readinglist:label:completed', array($completed));

	$content .= $completed;
}

$content .= "</div>";

echo $content;