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
$limit = elgg_extract('limit', $vars);
$offset = elgg_extract('offset', $vars);
$count = $books['totalItems'];

$content = '';

foreach ($books['items'] as $book) {
	$volumeInfo = '';
	$volumeInfo = $book['volumeInfo'];

	$google_id = $book['id'];

	echo "<pre>";
	var_dump($volumeInfo);
	echo "</pre>";

	// Preset fields to grab
	$simple_fields = array(
		'title', 'description', 'authors', 'canonicalVolumeLink', 'pageCount',
		'categories', 'publisher', 'publishedDate', 'printType'
	);

	// Create id hidden input
	$hidden_inputs = elgg_view('input/hidden', array(
		'name' => 'google_id',
		'value' => $google_id,
	));

	// Small thumbnail
	$hidden_inputs .= elgg_view('input/hidden', array(
		'name' => 'small_thumbnail',
		'value' => $volumeInfo['imageLinks']['smallThumbnail'],
	));

	//  Large thumbnail
	$hidden_inputs .= elgg_view('input/hidden', array(
		'name' => 'large_thumbnail',
		'value' => $volumeInfo['imageLinks']['thumbnail'],
	));

	$hidden_inputs .= elgg_view('input/hidden', array(
		'name' => 'identifiers',
		'value' => serialize($volumeInfo['industryIdentifiers']),
	));

	// Create rest of the simple fields
	foreach ($simple_fields as $field) {
		if (is_array($volumeInfo[$field])) {
			foreach ($volumeInfo[$field] as $multi_field) {
				$hidden_inputs .= elgg_view('input/hidden', array(
					'name' => $field . '[]',
					'value' => $multi_field,
				));
			}
		} else {
			$hidden_inputs .= elgg_view('input/hidden', array(
				'name' => $field,
				'value' => $volumeInfo[$field],
			));
		}
	}

	$title = $volumeInfo['title'];
	$link = $volumeInfo['canonicalVolumeLink'];

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

	$categories = isset($volumeInfo['categories']) ? $categories = implode(", ", $volumeInfo['categories']) : '';
	
	$page_count = isset($volumeInfo['pageCount']) ? $volumeInfo['pageCount'] . ' pages' : '';

	if ($thumbnail) {
		$thumbnail = <<<HTML
			<div class='book-thumbnail'>
				<a href='${preview}'>
					<img alt='$title' src='${thumbnail}'/>
				</a>
			</div>
HTML;
	}

	$book_select = elgg_view('input/submit', array(
		'id' => $google_id,
		'class' => 'book-select-submit elgg-button elgg-button-submit',
		'name' => 'book_select',
		'value' => elgg_echo('select'),
	));

	$content .= <<<HTML
		<div class='book-listing clearfix'>
			$thumbnail
			<div class='book-title'><a href='{$link}'>$title</a></div>
			<div class='book-authors'>$creators</div>
			<div class='book-subtext elgg-subtext'>$categories $page_count</div>
			<div class='book-select-input'>$book_select</div>
			$hidden_inputs
		</div>
HTML;
}

$content .= "<div class='clearfix'></div>";

$nav = elgg_view('navigation/pagination', array(
	'baseurl' => $base_url,
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
	'offset_key' => $offset_key,
));

echo $content . $nav;