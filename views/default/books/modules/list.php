<?php
/**
 * Reading List Book List Module (For use with genricmodules)
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['container_guid'] Container guid of user/group
 * @uses $vars['category']  Category of books to display
 */

$category = strtolower($vars['category']);

if (empty($category)) {
	$category = 'any';
}

$options = array(
	'type' => 'object', 
	'subtype' => 'book', 
	'full_view' => false, 
	'container_guid' => $vars['container_guid'],
);

if ($category != 'any') {
	$options['metadata_names'] = array('categories');
	$options['metadata_values'] = $category;
	$options['metadata_case_sensitive'] = FALSE;

	$content = elgg_list_entities_from_metadata($options);	
} else {
	$content = elgg_list_entities($options);	
}

// If theres no content, display a nice message
if (!$content) {
	$content = "<h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
}

echo $content;