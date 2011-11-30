<?php
/**
 * Reading List Book Rating Input
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['name']   Name of input 
 * @uses $vars['entity'] Book entity 
 *
 */

$user = elgg_get_logged_in_user_entity();

// Only display for logged in users
if ($user) {
	elgg_load_js('jquery.starrating');
	elgg_load_js('elgg.readinglist.bookrating');
	elgg_load_css('jquery.starrating');

	$rating_label = elgg_echo('readinglist:label:yourrating');

	$name = $vars['name'] ? $vars['name'] : 'rating';
	$guid = $vars['entity']->guid;

	// Options to grab the current users rating
	$options = array(
		'guid' => $guid,
		'annotation_names' => array('bookrating'),
		'annotation_owner_guids' => array($user->guid),
	);

	$rating = elgg_get_annotations($options);

	// Check that we have a rating
	if ($rating && is_array($rating)) {
		$rating = $rating[0]->value;
	} else {
		$rating = 0;
	}

	$inputs = '';

	// Create 5 inputs (5 stars)
	for ($i = 1; $i <= 5; $i++) {
		$checked = '';
		if ($i == $rating) {
			$checked = "checked='checked'"; // Checked attr
		}
		$inputs .= "<input name='$name' type='radio' value='$i' class='bookrating-radio-in' $checked />";
	}
	
	// Entity guid input
	$inputs .= elgg_view('input/hidden', array(
		'name' => 'book_guid',
		'id' => 'bookrating-input-guid',
		'value' => $guid,
	));

	$content = <<<HTML
		<div class='bookrating-container'>
			<label>$rating_label</label>
			<div class='bookrating-input'>
				$inputs
			</div>
		</div>
HTML;

	echo $content;
}