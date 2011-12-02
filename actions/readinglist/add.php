<?php
/**
 * Reading List Add Action
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = get_input('guid');

$book = get_entity($guid);

if (!elgg_instanceof($book, 'object', 'book')) {
	register_error(elgg_echo('readinglist:error:notfound'));
	forward(REFERER);
}

// Add reading list relationship
add_entity_relationship($book->guid, READING_LIST_RELATIONSHIP, elgg_get_logged_in_user_guid());

system_message(elgg_echo('readinglist:success:readinglistadd'));
forward(REFERER);