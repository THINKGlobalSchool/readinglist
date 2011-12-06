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
 * @uses $vars['entity']    Book to display reviews of
 * @uses $vars['user_guid'] (Optional) Grab reviews for given user
 * @uses $vars['show_form'] (Optional) Show the add review form (default true)
 * @uses $vars['title']     (Optional) Custom title (default 'reviews')
 */

$reviews_header = elgg_echo('readinglist:label:reviews');

$show_form = elgg_extract('show_form', $vars, TRUE);
$title = elgg_extract('title', $vars, "<h2>$reviews_header</h2>");

$options = array(
	'type' => 'object',
	'subtype' => 'book_review',
	'relationship' => BOOK_REVIEW_RELATIONSHIP,
	'relationship_guid' => $vars['entity']->guid,
	'inverse_relationship' => TRUE,
	'owner_guid' => $vars['user_guid'],
	'limit' => 10,
);

$reviews = elgg_list_entities_from_relationship($options);

if (!$reviews) {
	$reviews = elgg_echo('readinglist:label:noreviews');
}

// Set up add review form
if ($show_form) {
	$form_vars = array('name' => 'book_add_review');
	$review_form = elgg_view_form('books/review/add', $form_vars, $vars);
}

$content = <<<HTML
	<div class='book-reviews'>
		$title
		$reviews
		$review_form
	</div>
HTML;

echo $content;