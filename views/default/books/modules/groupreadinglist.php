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
 * @uses $vars['category']       Category of books to display
 * @uses $vars['order_by']       Change how we're ordering this list
 * @uses $vars['sort_order']     Change the sort order
 * @uses $vars['group_guid']     Optional group guid
 */

$group = get_entity($vars['group_guid']);

// Check for a group, set page owner
elgg_set_page_owner_guid($group->guid);


$category = strtolower($vars['category']);
$order_by = strtolower($vars['order_by']);
$sort_order = strtolower($vars['sort_order']);

if (empty($category)) {
	$category = 'any';
}

if (empty($order_by)) {
	$order_by = 'date';
}

if (empty($sort_order)) {
	$sort_order = 'desc';
}

$options = array(
	'type' => 'object', 
	'subtype' => 'book', 
	'full_view' => false, 
	'relationship' => READING_LIST_RELATIONSHIP,
	'relationship_guid' => $group->guid,
	'inverse_relationship' => TRUE,
);

switch ($order_by) {
	case 'date':
	default:
		$options['order_by'] = "e.time_created $sort_order";
		break;
	case 'popular':
		$options['order_by_metadata'] = array('name' => 'popularity', 'direction' => $sort_order, 'as' => 'integer');
		break;
	case 'rated':
		$options['order_by_metadata'] = array('name' => 'average_rating', 'direction' => $sort_order, 'as' => 'integer');
		break;
}

if ($category != 'any') {
	$options['metadata_names'] = array('categories');
	$options['metadata_values'] = $category;
	$options['metadata_case_sensitive'] = FALSE;
}

elgg_set_context('group_reading_list');
$content = elgg_list_entities_from_relationship($options);
elgg_pop_context();

// If theres no content, display a nice message
if (!$content) {
	$content = "<br /><h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
}

echo $content;