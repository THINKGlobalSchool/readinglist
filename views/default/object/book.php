<?php
/**
 * Reading List Books Object View
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

$ia = elgg_get_ignore_access();
if (!elgg_is_logged_in()) {
	elgg_set_ignore_access(TRUE);
}

$owner = $book->getOwnerEntity();
$container = $book->getContainerEntity();

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
if (elgg_in_context('widgets') || elgg_in_context('book_existing')) {
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

// Page count string
$page_count = $book->pageCount ? $book->pageCount . ' pages' : '';

if ($full) {
	$categories = elgg_view('output/bookcategories', array('book' => $book));

	$subtitle = "<p>$added_text $date</p>";

	// If we have a thumbnail, use it
	if ($book->large_thumbnail) {
		$thumbnail = "<div class='book-thumbnail'>
					<img src='{$book->large_thumbnail}' alt='{$book->title}' /></a>
				</div>";
	}

	$categories = $categories ? $categories . ' - ' : '';

	$body .= $authors . $categories . $page_count;

	$body .= elgg_view('output/url', array(
		'href' => $book->canonicalVolumeLink,
		'text' => elgg_echo('readinglist:label:googlelink'),
		'style' => 'display: block',
	));

	// If the book description is quite a bit longer than an excerpt, display both
	if (strlen($book->description) > 350) {
		// Show more link for description excerpt
		$show_more = "<br />" . elgg_view('output/url', array(
			'text' => elgg_echo('readinglist:label:showmore'),
			'href' => '#book-description-full',
			'id' => 'book-description-showmore' ,
		));

		// Excerpt
		$description_excerpt = elgg_view('output/longtext', array(
			'value' => readinglist_get_excerpt($book->description) . $show_more,
			'class' => 'book-description',
			'id' => 'book-description-excerpt',
		));

		// Show less link for description full
		$show_less = "<br />" . elgg_view('output/url', array(
			'text' => elgg_echo('readinglist:label:showless'),
			'href' => '#book-description-excerpt',
			'id' => 'book-description-showless' ,
		));

		$hide = 'display: none;';
	}

	// Full description
	$description_full = elgg_view('output/longtext', array(
		'value' => $book->description . $show_less,
		'class' => 'book-description',
		'id' => 'book-description-full',
		'style' => $hide,
	));

	$body .= $description_excerpt . $description_full;

	$body .= "<br /><div class='book-full-button-container'>" . elgg_view('readinglist/button', array('book' => $book)) . '</div>';

	$body .= "<div class='book-full-status-container'>";

	// If book is on user's reading list..
	if (check_entity_relationship($book->guid, READING_LIST_RELATIONSHIP, elgg_get_logged_in_user_guid())) {

		// Create status input
		$body .= "<label>" . elgg_echo('readinglist:label:status') . ": </label>" . elgg_view('readinglist/status', array(
			'user_guid' => elgg_get_logged_in_user_guid(),
			'book_guid' => $book->guid,
		));

		$body .= $completed_info = elgg_view('readinglist/completed', array(
			'book_guid' => $book->guid,
			'user_guid' => elgg_get_logged_in_user_guid()
		));
	}

	$body .= "</div><div class='clearfix'></div>";

	$params = array(
		'entity' => $book,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$book_header = elgg_view_image_block($owner_icon, $list_body);
	$book_body = elgg_view_image_block($thumbnail, $body);

	if (elgg_is_logged_in()) {
		$book_reviews = elgg_view('books/reviews', array('entity' => $book));
	}

	echo <<<HTML
<div class='clearfix book book-full-view'>
	$book_header
	$book_body
	$book_reviews
</div>
HTML;
	elgg_set_ignore_access($ia);
} else {
	elgg_set_ignore_access($ia);
	// brief view
	echo "<div class='book'>";

	$categories = elgg_view('output/bookcategories', array('book' => $book, 'make_links' => TRUE));

	$categories = $categories ? '' . $categories : '';
	$page_count = $page_count ? '' . $page_count . '<br />': '';

	$subtitle = "<p>$authors $categories $page_count $added_text $date</p>";

	// Set up controls
	$control_params = array('book' => $book);
	if (elgg_in_context('reading_list')) {
		$control_params['user_controls'] = TRUE;
	} else {
		$control_params['class'] = 'book-regular-listing';
	}

	$controls = elgg_view('readinglist/controls', $control_params);

	// If we have a small thumbnail, use it
	if ($book->small_thumbnail) {
		$icon = "<span class='book-thumbnail'>
					<a href='{$book->getURL()}'><img src='{$book->small_thumbnail}' alt='{$book->title}' /></a>
				</span>";
	}

	if (elgg_in_context('reading_list')) {
		// We're viewing a book listing in profile mode
		$subtitle .= "<a href='#readinglist-user-reviews-{$book->guid}' rel='toggle'>" . elgg_echo('readinglist:label:readreviews', array(elgg_get_page_owner_entity()->name)) . "</a>";
		$owner_guid = elgg_get_page_owner_guid();
		$owner = get_entity($owner_guid);
		$book_reviews = "<div style='display: none;' id='readinglist-user-reviews-{$book->guid}'>" .
							elgg_view('books/reviews', array(
								'entity' => $book,
								'user_guid' => $owner_guid,
								'show_form' => FALSE,
								'title' => "<h3>" . elgg_echo('readinglist:label:ownerreviews', array($owner->name)) . "</h3>",
							)) .
						"</div>";

		// No controls if not viewing our own list
		if (elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
			$controls = '';
		}
	} else if (elgg_in_context('book_existing')) {
		$controls = '';
	}

	// Determine if in sidebar view
	if (elgg_in_context('book_sidebar') || elgg_in_context('public_reading')) {
		$params = array(
			'entity' => $book,
			'metadata' => FALSE,
			'subtitle' => "<p>$authors</p>",
			'tags' => FALSE,
		);

		$params = $params + $vars;
		$list_body = elgg_view('object/elements/summary', $params);

		$title = "<a href='{$book->getURL()}'><span class='book-sidebar-title'>{$book->title}</span></a>";
		echo "<div class='book-sidebar'>" . elgg_view_image_block($icon, $list_body) . "</div>";
	} else {
		$params = array(
			'title' => $title,
			'entity' => $book,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'tags' => $tags,
			'content' => $book_reviews . $avg_rating,
		);
		$params = $params + $vars;
		$list_body = elgg_view('object/elements/summary', $params);

		echo elgg_view_image_block($icon, $list_body);
		echo $controls;
	}

	echo "</div>";
}

