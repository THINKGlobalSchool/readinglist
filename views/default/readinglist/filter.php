<?php
/**
 * Reading List Filter Menu
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
 * @uses $vars['order_by']   Display order by control
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
	
	$text = "<label>$category_label:</label>$category_input";

	elgg_register_menu_item('readinglist-filter-menu', array(
		'name' => 'readinglist_filter_category',
		'text' => $text,
		'href' => FALSE,
		'priority' => 100,
	));
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

	$text = "<label>$status_label:</label>$status_input";

	elgg_register_menu_item('readinglist-filter-menu', array(
		'name' => 'readinglist_filter_status',
		'text' => $text,
		'href' => FALSE,
		'priority' => 200,
	));
}

if ($vars['order_by']) {
	$order_label = elgg_echo('readinglist:label:order');

	$order_input = elgg_view('input/dropdown', array(
		'id' => 'readinglist-filter-orderby',
		'class' => 'readinglist-filter',
		'options_values' => array(
			'date' => elgg_echo('readinglist:label:date'),
			'popular' => elgg_echo('readinglist:label:popular'),
			'rated' => elgg_echo('readinglist:label:rated'),
		),
	));

	$text = "<label>$order_label:</label>$order_input";

	elgg_register_menu_item('readinglist-filter-menu', array(
		'name' => 'readinglist_order_by',
		'text' => $text,
		'href' => FALSE,
		'priority' => 300,
	));

	elgg_register_menu_item('readinglist-filter-menu', array(
		'name' => 'readinglist_order',
		'text' => elgg_echo('readinglist:label:sortasc'),
		'id' => 'readinglist-filter-sort-order',
		'title' => 'asc',
		'priority' => 400,
	));
}

$filter_menu = elgg_view_menu('readinglist-filter-menu', array(
	'class' => 'elgg-menu-hz elgg-menu-readinglist-filter',
	'sort_by' => 'priority',
));

$content = <<<HTML
	<div class='readinglist-filter-container'>
		$filter_menu
	</div>
HTML;

echo $content;