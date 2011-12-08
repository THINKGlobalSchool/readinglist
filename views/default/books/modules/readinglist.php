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
 * Note: This is a ton of logic for a view.. Not sure if its necessary here but we'll see
 */

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

$valid_status = array(
	(string)BOOK_READING_STATUS_QUEUED, 
	(string)BOOK_READING_STATUS_READING, 
	(string)BOOK_READING_STATUS_COMPLETE
);

$options = array(
	'type' => 'object',
	'subtype' => 'book',
	'full_view' => false,
	'relationship' => READING_LIST_RELATIONSHIP,
	'relationship_guid' => $vars['user_guid'],
	'inverse_relationship' => TRUE,
	'wheres' => array(),
	'joins' => array(),
	'orders' => array(),
);

switch ($order_by) {
	case 'date':
	default:
		$options['order_by'] = "e.time_created $sort_order";
		break;
	case 'popular':
		$order_by_metadata = array('name' => 'popularity', 'direction' => $sort_order, 'as' => 'integer');
		break;
	case 'rated':
		$order_by_metadata = array('name' => 'average_rating', 'direction' => $sort_order, 'as' => 'integer');
		break;
}

elgg_push_context('reading_list');

if ($category != 'any') {
	
	// Not setting these in options
	$metadata_names = array('categories');
	$metadata_values = array($category);
	
	// Because elgg does a horrible job of managing table name collisions, I'm 
	// manually setting up the metadata clauses
	$clauses = elgg_get_entity_metadata_where_sql('e', 'metadata', $metadata_names,
		$metadata_values, $options["metadata_name_value_pairs"],
		$options["metadata_name_value_pairs_operator"], FALSE,
		$order_by_metadata, $options["metadata_owner_guids"]);

	// This fixes non unique tables
	$clauses['wheres'] = str_replace(array('n_table', 'msn', 'msv'), array('nm_table', 'msnm', 'msvm'), $clauses['wheres']);
	$clauses['joins'] = str_replace(array('n_table', 'msn', 'msv'), array('nm_table', 'msnm', 'msvm'), $clauses['joins']);

	// Merge in the new options
	$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);
	$options['joins'] = array_merge($options['joins'], $clauses['joins']);
	
	// If we're ordering by metadata, set a special order_by option here, matching the new unique tables
	if ($order_by != 'date') {
		$options['order_by'] = "CAST(msvm1.string AS SIGNED) $sort_order";
	}
} else {
	// Category is any
	if ($order_by != 'date') {
		// We're ordering by metadata, so set up clauses manually (arghhhh)
		$clauses = elgg_get_entity_metadata_where_sql('e', 'metadata', NULL,
			NULL, $options["metadata_name_value_pairs"],
			$options["metadata_name_value_pairs_operator"], FALSE,
			$order_by_metadata, $options["metadata_owner_guids"]);

		// This fixes non unique tables
		$clauses['wheres'] = str_replace(array('n_table', 'msn', 'msv'), array('nm_table', 'msnm', 'msvm'), $clauses['wheres']);
		$clauses['joins'] = str_replace(array('n_table', 'msn', 'msv'), array('nm_table', 'msnm', 'msvm'), $clauses['joins']);

		// Merge in the new options
		$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);
		$options['joins'] = array_merge($options['joins'], $clauses['joins']);

		// Set order by
		$options['order_by'] = "CAST(msvm1.string AS SIGNED) $sort_order";
	}
}

if ($status != 'Any' && in_array($status, $valid_status, TRUE)) {

	// Build relationship SQL
	$clauses = elgg_get_entity_relationship_where_sql(
		'e.guid', 
		$options['relationship'],
		$options['relationship_guid'], 
		$options['inverse_relationship']
	);

	// Set annotaion options
	$options['annotation_name'] = 'book_reading_status';
	$options['annotation_value'] = $status;
	$options['annotation_owner_guid'] = $vars['user_guid'];

	// Merge in relationship and annotation options
	$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);
	$options['joins'] = array_merge($options['joins'], $clauses['joins']);

	// Phew..
	$content = elgg_list_entities_from_annotations($options);
} else {
	// Don't nother with any fancy status stuff, just grab related
	$content = elgg_list_entities_from_relationship($options);
}

elgg_pop_context();

// If theres no content, display a nice message
if (!$content) {
	$content = "<br /><h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
}

echo $content;