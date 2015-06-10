<?php
/**
 * Reading List Books Review Object View
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$review = elgg_extract('entity', $vars, FALSE);

if (!$review) {
	return TRUE;
}

// Neat trick to redirect to book view if viewing this object via site/view/guid
if (elgg_in_context('view')) {
	$book = get_entity($review->book_guid);
	if (elgg_instanceof($book, 'object', 'book')) {
		forward($book->getURL());
	}
}

$reviewer = get_user($review->owner_guid);
if (!$reviewer) {
	return true;
}

$friendlytime = elgg_view_friendly_time($review->time_created);

$reviewer_icon = elgg_view_entity_icon($reviewer, 'tiny');
$reviewer_link = "<a href=\"{$reviewer->getURL()}\">$reviewer->name</a>";

$menu = elgg_view_menu('book-review', array(
	'review' => $review,
	'reviewer' => $reviewer,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz float-right',
));

$review_text = elgg_view("output/longtext", array("value" => $review->description));

// Comment toggler
$comment_toggle .= elgg_view('output/url', array(
	'text' => elgg_view_icon('speech-bubble') . elgg_echo("readinglist:label:addcomment"), 
	'href' => "#review-comments-{$review->guid}",
	'class' => 'review-comment-button',
	'rel' => 'toggle',
));

$comments = elgg_view_comments($review, FALSE, array('class' => 'book-review-comments'));

$form_vars = array('name' => 'elgg_add_comment');
$comment_form = elgg_view_form('comment/save', $form_vars, $vars);

$body = <<<HTML
	<div class="mbn">
		$menu
		$reviewer_link
		<span class="elgg-subtext">
			$friendlytime
		</span>
		$review_text<br />
		$comments<br />
		$comment_toggle<br /><br />
		<div class='clearfix'></div>
		<div style='display: none;' id='review-comments-{$review->guid}'>
			$comment_form
		</div>
	</div>
HTML;

echo elgg_view_image_block($reviewer_icon, $body);

