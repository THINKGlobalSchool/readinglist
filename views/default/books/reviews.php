<?php
/**
 * Reading List Book Reviews View
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['entity'] Book to display reviews of
 */

$options = array(
	'type' => 'object',
	'subtype' => 'book_review',
	'relationship' => 'book_review_of',
	'relationship_guid' => $vars['entity']->guid,
	'inverse_relationship' => TRUE,
	'limit' => 10,
);

$reviews = elgg_list_entities_from_relationship($options);

if (!$reviews) {
	$reviews = elgg_echo('readinglist:label:noreviews');
}

$reviews_header = elgg_echo('readinglist:label:reviews');

// Set up add review form
$form_vars = array('name' => 'book_add_review');
$review_form = elgg_view_form('books/review/add', $form_vars, $vars);

$content = <<<HTML
	<div class='book-reviews'>
		<h2>$reviews_header</h2>
		$reviews
		$review_form
	</div>
HTML;

echo $content;