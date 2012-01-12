<?php
/**
 * Reading List Book Review River Create View
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
$subject = $vars['item']->getSubjectEntity();

$book = get_entity($object->book_guid);

$book_link = elgg_view('output/url', array(
	'href' => $book->getURL(),
	'text' => $book->title,
	'class' => 'elgg-river-object',
));

// If we have a small thumbnail, use it
if ($book->small_thumbnail) {
	$image = "<div class='book-river-thumbnail book-thumbnail'>
				<a href='{$book->getURL()}'><img src='{$book->small_thumbnail}' alt='{$book->title}' /></a>
			</div>";
}


$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
));

$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);


echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'image' => $image,
	'summary' => elgg_echo('river:create:object:book_review', array($subject_link, $book_link)),
	'message' => $excerpt,
));