<?php
/**
 * Reading List Books Add Review Form
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

if (isset($vars['entity']) && elgg_is_logged_in()) {

	// Check for sticky form
	if (elgg_is_sticky_form('book_add_review')) {
		extract(elgg_get_sticky_values('book_add_review'));
		elgg_clear_sticky_form('book_add_review');
	}

	$add_label = elgg_echo('readinglist:label:addreview');

	$review_input = elgg_view('input/longtext', array(
		'name' => 'description',
		'value' => $description,
	));

	$rating_label = elgg_echo('readinglist:label:rating');

	$rating_input = elgg_view('input/bookrating', array(
		'entity' => $vars['entity'],
	));

	$review_submit = elgg_view('input/submit', array(
		'value' => elgg_echo('readinglist:label:submitreview'),
		'id' => 'review-submit',
	));

	$book_hidden = elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $vars['entity']->guid,
	));

	$content = <<<HTML
		<div class='book-review-add'>
			<label>$add_label</label>
			$review_input
		</div>
		<div class='book-rating-add'>
			<label>$rating_label</label>
			$rating_input
			<span id='rating-error'></span>
		</div>
		<div style='clear: both;'></div>
		<div class='elgg-foot'>
			$review_submit
		</div>
		$book_hidden
HTML;

	echo $content;

}