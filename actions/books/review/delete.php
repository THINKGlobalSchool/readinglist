<?php
/**
 * Reading List Books Review Delete Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = get_input('guid', null);
$review = get_entity($guid);

if (elgg_instanceof($review, 'object', 'book_review') && $review->canEdit()) {
	if ($review->delete()) {
		system_message(elgg_echo('readinglist:success:deletereview'));
		forward(REFERER);
	} else {
		register_error(elgg_echo('readinglist:error:deletereview'));
	}
} else {
	register_error(elgg_echo('readinglist:error:reviewnotfound'));
}

forward(REFERER);