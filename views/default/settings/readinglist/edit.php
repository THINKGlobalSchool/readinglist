<?php
/**
 * Reading List Admin Settings
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * 
 */
?>
<br />
<div>
    <label><?php echo elgg_echo('readinglist:label:devkey'); ?></label><br />
    <?php 
		echo elgg_view('input/text', array(
			'name' => 'params[devkey]', 
			'value' => $vars['entity']->devkey)
		); 
	?>
</div>
<div>
    <label><?php echo elgg_echo('readinglist:label:appname'); ?></label><br />
    <?php 
		echo elgg_view('input/text', array(
			'name' => 'params[appname]', 
			'value' => $vars['entity']->appname)
		); 
	?>
</div>
