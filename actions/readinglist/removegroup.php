<?php
/**
 * Reading List Group Remove Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$book_guid = get_input('book_guid');
$group_guid = get_input('group_guid');

$book = get_entity($book_guid);
$group = get_entity($group_guid);

if (!elgg_instanceof($book, 'object', 'book')) {
	register_error(elgg_echo('readinglist:error:notfound'));
	forward(REFERER);
}

if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('groups:featured_error'));
	forward(REFERER);
}

remove_entity_relationship($book->guid, READING_LIST_RELATIONSHIP, $group->guid);

// Set popularity
elgg_load_library('elgg:readinglist');
elgg_set_ignore_access(TRUE);
$book->popularity = readinglist_calculate_popularity($book);
elgg_set_ignore_access(FALSE);

system_message(elgg_echo('readinglist:success:groupreadinglistremove'));
forward(REFERER);