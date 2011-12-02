<?php
/**
 * Reading List Book Duplicate view
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = elgg_extract('guid', $vars);

$book = get_entity($guid);

if (!elgg_instanceof($book, 'object', 'book')) {
	return TRUE;
}

$title_label = elgg_echo('readinglist:label:duplicate', array($book->title));
$desc_label = elgg_echo('readinglist:label:duplicatedescription');

elgg_push_context('book_existing');
$book_info = elgg_view_entity($book, array('full_view' => FALSE));
elgg_pop_context();

$content = <<<HTML
	<h3>$title_label</h3>
	<p>$desc_label</p>
	<div id='book-duplicate-$guid' class='book-listing'>
		$book_info
	</div><br />
HTML;

echo $content;