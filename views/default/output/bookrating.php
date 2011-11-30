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
 */
elgg_load_js('jquery.starrating');
elgg_load_js('elgg.readinglist.bookrating');
elgg_load_css('jquery.starrating');

$rating_label = elgg_echo('readinglist:label:averagerating');

$content = <<<HTML
	<div class='bookrating-container'>
		<label>$rating_label</label>
		<div class='bookrating-output'>
			<input name="star1" type="radio" class="bookrating-radio-out" disabed="disabled" />
			<input name="star1" type="radio" class="bookrating-radio-out" disabed="disabled" />
			<input name="star1" type="radio" class="bookrating-radio-out" disabed="disabled" />
			<input name="star1" type="radio" class="bookrating-radio-out" disabed="disabled" />
			<input name="star1" type="radio" class="bookrating-radio-out" disabed="disabled" />
		</div>
	</div>
HTML;

echo $content;