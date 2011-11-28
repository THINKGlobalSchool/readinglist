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
$categories = elgg_view('output/categories', $vars);
$excerpt = $book->excerpt;

$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array(
	'href' => "books/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));
$tags = elgg_view('output/tags', array('tags' => $book->tags));
$date = elgg_view_friendly_time($book->time_created);

$comments_count = $book->countComments();

//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $book->getURL() . '#book-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}


$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'books',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "<p>$author_text $date $comments_link</p>";
$subtitle .= $categories;

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {
	$body = elgg_view('output/longtext', array(
		'value' => $book->description,
		'class' => 'book-description',
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
$book_info
$body
HTML;

} else {
	// brief view

	$params = array(
		'entity' => $book,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($owner_icon, $list_body);
}
