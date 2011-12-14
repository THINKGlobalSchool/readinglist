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
	$image = "<div class='book-river-thumbnail book-thumbnail'>
				<a href='{$object->getURL()}'><img src='{$object->small_thumbnail}' alt='{$object->title}' /></a>
			</div>";
}

// Authors string
if ($object->authors) {
	$author_label = elgg_echo('readinglist:label:author');
	if (is_array($book->authors)) {
		$authors = implode(", ", $book->authors);
	} else {
		$authors = $object->authors;
	}
	$authors = "<span class='author-subtext'>". $author_label . "<strong>" . $authors . "</strong><br /></span>";
}

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'image' => $image,
	'message' => $image . $authors . $excerpt,
));