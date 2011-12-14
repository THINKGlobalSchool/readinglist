<?php
/**
 * Reading List "Who's reading this?" Module (for use with genericmodules)
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['book_guid'] Book guid
 * @uses $vars['status']    Which status to display
 */

$status = elgg_extract('status', $vars, 'any');

$options = array(
	'type' => 'user',
	'relationship_guid' => $vars['book_guid'],
	'inverse_relationship' => FALSE,
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

elgg_push_context('owner_block');
$content = elgg_list_entities_from_relationship($options);
elgg_pop_context();

// If theres no content, display a nice message
if (!$content) {
	$content = "<br /><h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
}

echo $content . "<br />";