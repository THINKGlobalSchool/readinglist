<?php
/**
 * Reading List Book Readinglist Module (For use with genricmodules)
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user_guid']      User to display readinglist for
 * @uses $vars['category']       Category of books to display
 * @uses $vars['status']         Status of books to display
 * @uses $vars['order_by']       Change how we're ordering this list
 * @uses $vars['sort_order']     Change the sort order
 * 
 */
global $CONFIG;
elgg_set_page_owner_guid($vars['user_guid']);

$status = $vars['status'];
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
	'relationship_guid' => $vars['user_guid'],
	'inverse_relationship' => TRUE,
	'wheres' => array(),
	'joins' => array(),
	'orders' => array(),
);

// Set options based on status
switch ($status) {
	case 'any':
	default:
		$options['relationship'] = READING_LIST_RELATIONSHIP;
		break;
	case BOOK_READING_STATUS_QUEUED:
		$options['relationship'] = READING_LIST_RELATIONSHIP_QUEUED;
		break;
	case BOOK_READING_STATUS_READING:
		$options['relationship'] = READING_LIST_RELATIONSHIP_READING;
		break;
	case BOOK_READING_STATUS_COMPLETE:
		$options['relationship'] = READING_LIST_RELATIONSHIP_COMPLETE;
		break;
}

switch ($order_by) {
	case 'date':
	default:
		$options['order_by'] = "e.time_created $sort_order";
		break;
	case 'popular':
		$order_by_metadata = array('name' => 'popularity', 'direction' => $sort_order, 'as' => 'integer');
		$options['order_by_metadata'] = array('name' => 'popularity', 'direction' => $sort_order, 'as' => 'integer');
		break;
	case 'rated':
		$suffix = elgg_get_access_sql_suffix("rating_table");
		$options['joins'][] = "JOIN {$CONFIG->dbprefix}annotations rating_table on e.guid = rating_table.entity_guid";
		$options['joins'][] = "JOIN {$CONFIG->dbprefix}metastrings rating_name on rating_table.name_id = rating_name.id";
		$options['joins'][] = "JOIN {$CONFIG->dbprefix}metastrings rating_value on rating_table.value_id = rating_value.id";
		$options['wheres'][] = "(rating_name.string = 'user_bookrating' AND (rating_table.owner_guid IN ({$vars['user_guid']})) AND ($suffix))";
		$options['order_by'] = "CAST(rating_value.string AS SIGNED) $sort_order";
		break;
}

if ($category != 'any') {
	$options['metadata_names'] = array('categories');
	$options['metadata_value'] = array($category);
	$options['metadata_case_sensitive'] = FALSE;
}

elgg_push_context('reading_list');
$content = elgg_list_entities_from_relationship($options);
elgg_pop_context();

// If theres no content, display a nice message
if (!$content) {
	$content = "<br /><h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
}

echo $content;