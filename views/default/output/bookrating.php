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
 * @uses $vars['name']   Name of inputs
 * @uses $vars['user']   User to display reviews for
 * @uses $vars['class']  Classname of inputs (important!)
 * @uses $vars['entity'] Book entity
 */
elgg_load_js('jquery.starrating');
elgg_load_js('elgg.readinglist.bookrating');
elgg_load_css('jquery.starrating');

$inputs = '';

$name = $vars['name'];

if (isset($vars['user'])) {
	$user = $vars['user'];
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (isset($vars['class'])) {
	$class = $vars['class'] . ' ';
}

// Make sure theres a unique class
$unique_class = 'bookrating-radio-out-' . uniqid();
$class .= $unique_class;

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
		$inputs .= "<input name='$name' type='radio' value='$i' class='$class' $checked />";
	}

	$content = <<<HTML
		<div class='bookrating-container'>
			<div class='bookrating-output'>
				$inputs
			</div>
		</div>
HTML;

	echo $content;

	$script = <<<JAVASCRIPT
		<script type='text/javascript'>
			$(document).ready(function() {
				// Init read-only output
				$('.$unique_class').rating();
				$('.$unique_class').rating('disable');
			})
		</script>
JAVASCRIPT;

	echo $script;
}
