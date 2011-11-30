<?php
/**
 * Reading List Books Save Form
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */
$full = elgg_extract('full_view', $vars, FALSE);
$book = elgg_extract('entity', $vars, FALSE);

if (!$book) {
	return TRUE;
}

$owner = $book->getOwnerEntity();
$container = $book->getContainerEntity();
$excerpt = $book->excerpt;

$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array(
	'href' => "books/owner/$owner->username",
	'text' => $owner->name,
));
$added_text = elgg_echo('readinglist:label:addedby', array($owner_link));
$tags = elgg_view('output/tags', array('tags' => $book->tags));
$date = elgg_view_friendly_time($book->time_created);

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'books',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

// Authors string
if ($book->authors) {
	$author_label = elgg_echo('readinglist:label:author');
	if (is_array($book->authors)) {
		$authors = implode(", ", $book->authors);
	} else {
		$authors = $book->authors;
	}
	$authors = $author_label . "<strong>" . $authors . "</strong><br />";
}

// Categories string
if ($book->categories) {
	if (is_array($book->categories)) {
		$categories = implode(", ", $book->categories);
	} else {
		$categories = $book->categories;
	}
}

// Page count string
$page_count = isset($book->pageCount) ? $book->pageCount . ' pages' : '';

if ($full) {
	$subtitle = "<p>$added_text $date</p>";

	// If we have a thumbnail, use it
	if ($book->large_thumbnail) {
		$body = "<div class='book-thumbnail'>
					<img src='{$book->large_thumbnail}' alt='{$book->title}' /></a>
				</div>";
	}

	$categories = $categories ? $categories . ' - ' : '';

	$body .= $authors . $categories . $page_count;

	$body .= "<br />" . elgg_view('output/url', array(
		'href' => $book->canonicalVolumeLink,
		'text' => elgg_echo('readinglist:label:googlelink'),
	));

	$body .= elgg_view('output/longtext', array(
		'value' => $book->description,
		'class' => 'book-description',
	));

	$body .= "<br />" . elgg_view('input/bookrating', array(
		'entity' => $book,
	));

	$body .= "<br />" . elgg_view('output/averagebookrating', array(
		'entity' => $book,
	));

	$params = array(
		'entity' => $book,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$book_info = elgg_view_image_block($owner_icon, $list_body);

	echo <<<HTML
<div class='clearfix book-full-view'>
	$book_info
	$body
</div>
HTML;

} else {
	// brief view
	$categories = $categories ? '' . $categories : '';
	$page_count = $page_count ? '' . $page_count . '<br />': '';

	$subtitle = "<p>$authors $categories $page_count $added_text $date</p>";

	$params = array(
		'entity' => $book,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	// If we have a small thumbnail, use it
	if ($book->small_thumbnail) {
		$icon = "<div class='book-thumbnail'>
					<a href='{$book->getURL()}'><img src='{$book->small_thumbnail}' alt='{$book->title}' /></a>
				</div>";
	} else {
		$icon = $owner_icon;
	}

	echo elgg_view_image_block($icon, $list_body);
}
