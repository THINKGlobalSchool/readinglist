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
 * @uses $vars['user_guid'] User to display readinglist for
 * @uses $vars['category']  Category of books to display
 * @uses $vars['status']    Status of books to display
 * 
 * Note: This is a ton of logic for a view.. Not sure if its necessary here but we'll see
 */

$status = $vars['status'];
$category = strtolower($vars['category']);

if (empty($category)) {
	$category = 'any';
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
);

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
		$options["order_by_metadata"], $options["metadata_owner_guids"]);

	// This fixes non unique tables
	$clauses['wheres'] = str_replace(array('n_table', 'msn', 'msv'), array('nm_table', 'msnm', 'msvm'), $clauses['wheres']);
	$clauses['joins'] = str_replace(array('n_table', 'msn', 'msv'), array('nm_table', 'msnm', 'msvm'), $clauses['joins']);
	
	// Merge in the new options
	$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);
	$options['joins'] = array_merge($options['joins'], $clauses['joins']);
	
	echo $category;
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
	
	echo $status;
} else {
	// Don't nother with any fancy status stuff, just grab related
	$content = elgg_list_entities_from_relationship($options);
}

elgg_pop_context();

// If theres no content, display a nice message
if (!$content) {
	$content = "<h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
}

echo $content;