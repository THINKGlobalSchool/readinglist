<?php
/**
 * Reading List Book Gallery View
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$books = elgg_extract('books', $vars);

$content = '';

foreach ($books['items'] as $book) {
	$volumeInfo = $book['volumeInfo'];

	$title = $volumeInfo['title'];
	$preview = $volumeInfo['previewLink'];

	if (isset($volumeInfo['imageLinks']['smallThumbnail'])) {
		$thumbnail = $volumeInfo['imageLinks']['smallThumbnail'];
	} else {
		$thumbnail = null;
	}
	
	if (isset($volumeInfo['authors'])) {
		$creators = implode(", ", $volumeInfo['authors']);
		if ($creators) {
			$creators = "by " . $creators;
		}
	}
	
	if ($thumbnail) {
		$thumbnail = <<<HTML
			<div class='book-thumbnail'>
				<a href='${preview}'>
					<img alt='$title' src='${thumbnail}'/>
				</a>
			</div>
HTML;
	}

	$content .= <<<HTML
		<div class='book-listing'>
			$thumbnail
			<div class='book-title'><a href='{$preview}'>$title</a></div>
			<div class='book-authors'>$creators</div>
		</div>
HTML;
}

$content .= "<div class='clearfix'></div>";

echo $content;