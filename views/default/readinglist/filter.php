<?php
/**
 * Reading List Filters
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user_guid']  User
 * @uses $vars['book_guid']  Book guid
 * @uses $vars['category]    Display category filter
 * @uses $vars['status']     Display status filter
 */

if ($vars['category']) {
	$category_label = elgg_echo('readinglist:label:category');

	$categories_options = readinglist_get_available_categories();

	// Add 'any' option to categories
	array_unshift($categories_options, elgg_echo('readinglist:label:any'));

	$category_input = elgg_view('input/dropdown', array(
		'id' => 'readinglist-filter-category',
		'class' => 'readinglist-filter',
		'options' => $categories_options,
	));
	
	$category = <<<HTML
		<label>$category_label</label>
		$category_input
HTML;
}

if ($vars['status']) {
	$status_label = elgg_echo('readinglist:label:status');

	$status_input = elgg_view('input/dropdown', array(
		'id' => 'readinglist-filter-status',
		'class' => 'readinglist-filter',
		'options_values' => array(
			'any' => elgg_echo('readinglist:label:any'),
			BOOK_READING_STATUS_QUEUED => elgg_echo('readinglist:label:status:queued'),
			BOOK_READING_STATUS_READING => elgg_echo('readinglist:label:status:reading'),
			BOOK_READING_STATUS_COMPLETE => elgg_echo('readinglist:label:status:complete'),
		)
	));

	$status = <<<HTML
			<label>$status_label</label>
			$status_input
HTML;
}

$content = <<<HTML
	<div class='readinglist-filter-container'>
		$category
		$status
	</div>
HTML;

echo $content;