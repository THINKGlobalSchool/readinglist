<?php
/**
 * Reading List Add To Readinglist River View
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */
$subject = $vars['item']->getSubjectEntity();
$object = $vars['item']->getObjectEntity();


echo elgg_view('river/item', array(
	'item' => $vars['item'],
));
