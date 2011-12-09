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

$container_guid_input = elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $container_guid,
));

$search_input = elgg_view('input/submit', array(
	'id' => 'book-search-submit',
	'name' => 'book_search_submit',
	'value' => elgg_echo('search'),
));

$save_input = elgg_view('input/submit', array(
	'id' => 'book-save-input',
	'name' => 'book_save_input',
	'value' => elgg_echo('readinglist:label:save'),
	'disabled' => 'DISABLED',
	'class' => 'elgg-state-disabled elgg-button elgg-button-submit',
));

$content = <<<HTML
	<div class='book-form'>
		<div>
			<table class='book-search-table'>
				<tbody>
					<tr>
						<td class='book-search-left'>
							$title_input
						</td>
						<td class='book-search-right'>
							$search_input
						</td>
					</tr>
				</tbody>
			</table>
		</div><br />
		<div id='books-selected-item'></div>
		<div style='display:none;'>
			<a href='#books-search-results' id='trigger-book-results'></a>
			<div id='books-search-results'>
			</div>
		</div><br />
		<div>
			<label>$tags_label</label>
			$tags_input
		</div><br />
		<div class="elgg-foot">
			$save_input
		</div>
		$container_guid_input
		$book_guid
	</div>
HTML;

echo $content;