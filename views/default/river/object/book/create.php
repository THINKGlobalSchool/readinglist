<?php
/**
 * Reading List Book River Create View
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */
elgg_load_css('elgg.readinglist');

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

// If we have a small thumbnail, use it
if ($object->small_thumbnail) {
	$thumbnail_src = elgg_normalize_url('books/secureimg');

	$image = "<div class='book-river-thumbnail book-thumbnail'>
				<a href='{$object->getURL()}'><img src='{$thumbnail_src}/{$object->guid}?size=small' alt='{$object->title}' /></a>
			</div>";
}

// Authors string
if ($object->authors) {
	$author_label = elgg_echo('readinglist:label:author');
	if (is_array($object->authors)) {
		$authors = implode(", ", $object->authors);
	} else {
		$authors = $object->authors;
	}
	$authors = "<span class='author-subtext'>". $author_label . "<strong>" . $authors . "</strong><br /></span>";
}

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'image' => $image,
	'message' => $image . $authors . $excerpt,
));