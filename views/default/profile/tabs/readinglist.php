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

// Load Libraries
elgg_load_library('elgg:readinglist');
elgg_load_css('elgg.readinglist');
elgg_load_js('elgg.readinglist');
elgg_load_js('jquery.starrating');
elgg_load_js('elgg.readinglist.bookrating');
elgg_load_css('jquery.starrating');

$page_owner = elgg_get_page_owner_entity();

$options = array(
	'type' => 'object',
	'subtype' => 'book',
	'full_view' => false,
	'relationship' => READING_LIST_RELATIONSHIP,
	'relationship_guid' => $page_owner->guid,
	'inverse_relationship' => TRUE,
	'profile_reading_list' => TRUE,
);

elgg_push_context('profile_reading_list');
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
