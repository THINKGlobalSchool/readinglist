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

elgg_push_context('reading_list');

$content_info = readinglist_get_page_content_readinglist($page_owner->guid);

echo $content_info['content'];

elgg_pop_context();

return;



$options = array(
	'type' => 'object',
	'subtype' => 'book',
	'full_view' => false,
	'relationship' => READING_LIST_RELATIONSHIP,
	'relationship_guid' => $page_owner->guid,
	'inverse_relationship' => TRUE,
	'reading_list' => TRUE,
);

elgg_push_context('reading_list');
$readinglist = elgg_list_entities_from_relationship($options);
elgg_pop_context();

// If theres no content, display a nice message
if (!$readinglist) {
	$readinglist = "<h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
}

$content = elgg_view_title(elgg_echo('profile:readinglist'));

$content .= <<<HTML
	<br />
	<div class='readinglist-profile-container'>
		$readinglist
	</div>
HTML;

echo $content;
