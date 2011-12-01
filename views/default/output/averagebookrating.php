<?php
/**
 * Reading List Average Book Rating Output
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['name']   Name of inputs
 * @uses $vars['entity'] Book entity
 * @uses $vars['class']  Classname of inputs (important!)
 */
elgg_load_js('jquery.starrating');
elgg_load_js('elgg.readinglist.bookrating');
elgg_load_css('jquery.starrating');

$rating_label = elgg_echo('readinglist:label:averagerating');

$inputs = '';

$name = $vars['name'];

if (isset($vars['class'])) {
	$class = $vars['class'] . ' ';
}

// Make sure theres a unique class
$unique_class = 'bookrating-radio-avg-out-' . uniqid();
$class .= $unique_class;

// Grab the average rating
$options = array(
	'guid' => $vars['entity']->guid,
	'annotation_names' => array('bookrating'),
	'annotation_calculation' => 'avg',
);

$rating = elgg_get_annotations($options);

$rating = round($rating);

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
		<label>$rating_label</label>
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