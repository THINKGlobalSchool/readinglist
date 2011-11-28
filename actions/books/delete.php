<?php
/**
 * Reading List Books Delete Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = get_input('guid', null);
$book = get_entity($guid);

if (elgg_instanceof($book, 'object', 'book') && $book->canEdit()) {
	if ($book->delete()) {
		system_message(elgg_echo('readinglist:success:deletebook'));
		forward("books/all");
	} else {
		register_error(elgg_echo('readinglist:error:deletebook'));
	}
} else {
	register_error(elgg_echo('readinglist:error:notfound'));
}

forward(REFERER);