<?php
/**
 * Reading List Profile Tab
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 */

if (!elgg_is_logged_in()) {
	forward(REFERER);
}

// Load Libraries
elgg_load_library('elgg:readinglist');
elgg_load_css('elgg.readinglist');
elgg_load_js('elgg.readinglist');
elgg_load_js('jquery.starrating');
elgg_load_js('elgg.readinglist.bookrating');
elgg_load_css('jquery.starrating');

$page_owner = elgg_get_page_owner_entity();

echo elgg_view('output/url', array(
	'text' => elgg_echo('readinglist:label:browseallbooks'),
	'value' => 'books/all',
	'class' => 'elgg-button elgg-button-action',
)) . "</br></br>";

elgg_push_context('reading_list');

$content_info = readinglist_get_page_content_readinglist($page_owner->guid);

echo $content_info['content'];

elgg_pop_context();

