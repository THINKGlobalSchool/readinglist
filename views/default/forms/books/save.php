<?php
/**
 * Reading List Books Save Form
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

//elgg_load_css('elgg.readinglist');
//elgg_load_js('elgg.readinglist');

// Get values/sticky values
$tags 			= elgg_extract('tags', $vars);
$access_id 		= elgg_extract('access_id', $vars);
$url			= elgg_extract('search', $vars);
$container_guid = elgg_extract('container_guid', $vars, elgg_get_page_owner_guid());
$guid 		 	= elgg_extract('guid', $vars);
$title          = elgg_extract('title', $vars);

// If we have an entity, we're editing
if ($guid) {
	$book = get_entity($guid);

	// Hidden field to identify book
	$book_guid = elgg_view('input/hidden', array(
		'id' => 'book-guid', 
		'name' => 'guid',
		'value' => $guid,
	));	
} else { // Creating a new book
	$access_id = ACCESS_LOGGED_IN;	
}

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'id' => 'book-search-title',
	'name' => 'title',
	'value' => $title,
));

$tags_label =  elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
	'id' => 'video-tags',
	'name' => 'tags',
	'value' => $tags
));

$access_label =  elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'id' => 'video-access',
	'name' => 'access_id',
	'value' => $access_id
));

$container_guid_input = elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $container_guid,
));

$save_input = elgg_view('input/submit', array(
	'id' => 'book-save-input',
	'name' => 'book_save_input',
	'value' => elgg_echo('readinglist:label:save')
));

$content = <<<HTML
	<div class='book-form'>
		<div>
			<label>$title_label</label>
			$title_input
		</div><br />
		<div>
			<label>$tags_label</label>
			$tags_input
		</div><br />
		<div>
			<label>$access_label</label>
			$access_input
		</div><br />
		<div class="elgg-foot">
			$save_input
		</div>
		$container_guid_input
		$book_guid
	</div>
HTML;

echo $content;