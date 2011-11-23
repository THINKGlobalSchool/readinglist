<?php
/**
 * Reading List Start.php
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
elgg_register_event_handler('init', 'system', 'reading_list_init');

function reading_list_init() {
	// Register and load library
	//elgg_register_library('elgg:readinglist', elgg_get_plugins_path() . 'readinlist/lib/readinglist.php');
	//elgg_load_library('elgg:readinglist');

	// Register CSS
	$r_css = elgg_get_simplecache_url('css', 'readinglist/css');
	elgg_register_simplecache_view('css/readinglist/css');
	elgg_register_css('elgg.readinglist', $r_css);
	//elgg_load_css('elgg.readinglist');

	// Register JS libraries
	$r_js = elgg_get_simplecache_url('js', 'readinglist/readinglist');
	elgg_register_simplecache_view('js/readinglist/readinglist');
	elgg_register_js('elgg.readinglist', $r_js);
	//elgg_load_js('elgg.readinglist');
	
	// Register page handler
	elgg_register_page_handler('readinglist','reading_list_page_handler');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'readinglist/actions/readinglist';
	//elgg_register_action('readinglist/xyz', "$action_base/xyz.php");

	return TRUE;
}

/**
 * Reading  list page handler
 */
function reading_list_page_handler($page) {
}