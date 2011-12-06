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

// Categories string
if ($book->categories) {
	if (is_array($book->categories)) {
		$categories = implode(", ", $book->categories);
	} else {
		$categories = $book->categories;
	}
}

// Page count string
$page_count = $book->pageCount ? $book->pageCount . ' pages' : '';

if ($full) {
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

	$body .= elgg_view('output/longtext', array(
		'value' => $book->description,
		'class' => 'book-description',
	));

	$body .= "<br /><label>" . elgg_echo('readinglist:label:yourrating') . "</label><br />";
	$body .= elgg_view('input/bookrating', array(
		'entity' => $book,
	));

	$body .= "<br /><br />" . elgg_view('readinglist/button', array('book' => $book));

	$body .= "<div class='book-full-status-container'>";

	// If book is on user's reading list..
	if (check_entity_relationship($book->guid, READING_LIST_RELATIONSHIP, elgg_get_logged_in_user_guid())) {
		// Create status input
		$body .= "<br /><label>" . elgg_echo('readinglist:label:status') . "</label>" . elgg_view('readinglist/status', array(
			'user_guid' => elgg_get_logged_in_user_guid(),
			'book_guid' => $book->guid,
		));

		$body .= $completed_info = elgg_view('readinglist/completed', array(
			'book_guid' => $book->guid,
			'user_guid' => $user->guid
		));
	}

	$body .= "</div>";

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
	$book_reviews = elgg_view('books/reviews', array('entity' => $book));

	echo <<<HTML
<div class='clearfix book book-full-view'>
	$book_header
	$book_body
	$book_reviews
</div>
HTML;

} else {
	// brief view
	echo "<div class='book'>";

	$categories = $categories ? '' . $categories : '';
	$page_count = $page_count ? '' . $page_count . '<br />': '';

	$subtitle = "<p>$authors $categories $page_count $added_text $date</p>";

	if (!elgg_in_context('widgets') && !elgg_in_context('book_existing') && !elgg_in_context('profile_reading_list')) {
		// Check if we're in the reading list context, if so display additional user controls
		$control_params = array('book' => $book);
		if (elgg_in_context('reading_list')) {
			$control_params['user_controls'] = TRUE;
		}

		$controls = elgg_view('readinglist/controls', $control_params);
	} else if (elgg_in_context('profile_reading_list')) {
		// We're viewing a book listing in profile mode
		$subtitle = "<p>$authors $categories $page_count</p>";
		$subtitle .= "<a href='#readinglist-user-reviews-{$book->guid}' rel='toggle'>" . elgg_echo('readinglist:label:readreviews') . "</a>";
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
	}

	$params = array(
		'entity' => $book,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $book_reviews,
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

	echo $controls;

	echo "</div>";
}
