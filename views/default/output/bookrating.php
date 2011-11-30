<?php
/**
 * Reading List Book Rating Output
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['name']
 * @uses $vars['user']
 * @uses $vars['entity']
 */
elgg_load_js('jquery.starrating');
elgg_load_js('elgg.readinglist.bookrating');
elgg_load_css('jquery.starrating');

$inputs = '';

$name = $vars['name'];

$user = $vars['user'];

if ($user) {
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

	// Create 5 inputs (5 stars)
	for ($i = 1; $i <= 5; $i++) {
		$checked = '';
		if ($i == $rating) {
			$checked = "checked='checked'"; // Checked attr
		}
		$inputs .= "<input name='$name' type='radio' value='$i' class='bookrating-radio-out' $checked />";
	}

	$content = <<<HTML
		<div class='bookrating-container'>
			<div class='bookrating-output'>
				$inputs
			</div>
		</div>
HTML;

	echo $content;
}

