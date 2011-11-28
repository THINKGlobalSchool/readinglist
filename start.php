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
 * @TODO:
 * - River
 * - Just about everything else.. :D
 */
elgg_register_event_handler('init', 'system', 'readinglist_init');

function readinglist_init() {
	// Register and load library
	elgg_register_library('elgg:readinglist', elgg_get_plugins_path() . 'readinglist/lib/readinglist.php');

	// Register CSS
	$r_css = elgg_get_simplecache_url('css', 'readinglist/css');
	elgg_register_simplecache_view('css/readinglist/css');
	elgg_register_css('elgg.readinglist', $r_css);
	elgg_load_css('elgg.readinglist');

	// Register JS libraries
	$r_js = elgg_get_simplecache_url('js', 'readinglist/readinglist');
	elgg_register_simplecache_view('js/readinglist/readinglist');
	elgg_register_js('elgg.readinglist', $r_js);
	elgg_load_js('elgg.readinglist');
	
	// Register page handler
	elgg_register_page_handler('books','reading_list_page_handler');

	// Handler to register reading list filter menu items
	elgg_register_plugin_hook_handler('register', 'menu:reading-list-filter', 'reading_list_filter_menu_setup');

	// Entity url handler
	register_entity_url_handler('readinglist_url_handler', 'object', 'book');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'readinglist/actions';
	elgg_register_action('books/save', "$action_base/books/save.php");
	elgg_register_action('books/delete', "$action_base/books/delete.php");
	elgg_register_action('readinglist/add', "$action_base/readinglist/add.php");
	elgg_register_action('readinglist/remove', "$action_base/readinglist/remove.php");

	// Load google libs
	elgg_load_library('gapc:apiClient');       // Main client
 	elgg_load_library('gapc:apiBooksService'); // Books service

	/* BOOKS API EXAMPLE
	$client = new apiClient();
	$client->setDeveloperKey('AIzaSyCPsvFIGl7b13H_KcJgAopdfHjDqGeR0Rg');
	$client->setApplicationName("spot_books");

	$service = new apiBooksService($client);

	$volumes = $service->volumes;

	$options = array(
		'filter' => 'full', // All books
	);

	$search = 'Book name';

	$results = $volumes->listVolumes($search, $options);
	*/

	return TRUE;
}

/**
 * Dispatcher for books
 *
 * URLs take the form of
 *  All books:       books/all
 *  User's books:    books/owner/<username>
 *  Reading list:    books/readinglist/<username>
 *  Public List		 books/reading
 *  View book:       books/view/<guid>/<title>
 *  New book:        books/add/<guid> (container: user, group, parent)
 *  Edit book:       books/edit/<guid>
 *
 * Title is ignored
 *
 * @param array $page
 */
function reading_list_page_handler($page) {
	elgg_load_library('elgg:readinglist');

	elgg_push_breadcrumb(elgg_echo('books'), 'books/all');

	switch($page[0]) {
		case 'all':
		default:
			$params = readinglist_get_page_content_list();
			break;
		case 'owner':
			$user = get_user_by_username($page[1]);
			$params = readinglist_get_page_content_list($user->guid);
			break;
		case 'readinglist':
			// @TODO - User reading list
			break;
		case 'reading':
			// @TODO - Public: what's tgs reading?
			break;
		case 'view':
			$params = readinglist_get_page_content_view($page[1]);
		 	break;
		case 'add':
			$params = readinglist_get_page_content_edit($page[0], $page[1]);
			break;
		case 'edit':
			$params = readinglist_get_page_content_edit($page[0], $page[1]);
			break;
	}

	$params['filter'] = elgg_view_menu('reading-list-filter', array(
		'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default',
		'sort_by' => 'priority',
	));

	$body = elgg_view_layout($params['layout'] ? $params['layout'] : 'content', $params);

	echo elgg_view_page($params['title'], $body);
}

/**
 * Populates the ->getUrl() method for a book
 *
 * @param ElggEntity entity
 * @return string request url
 */
function readinglist_url_handler($entity) {
	return elgg_get_site_url() . "books/view/{$entity->guid}/";
}

/**
 * Reading list filter menu setup
 */
function reading_list_filter_menu_setup($hook, $type, $return, $params) {
	$user = elgg_get_logged_in_user_entity();

 	$options = array(
		'name' => 'books-all',
		'text' => elgg_echo('all'),
		'href' => 'books/all',
		'priority' => 100,
	);

	$return[] = ElggMenuItem::factory($options);

	$options = array(
		'name' => 'books-mine',
		'text' => elgg_echo('mine'),
		'href' => "books/owner/$user->username",
		'priority' => 200,
	);

	$return[] = ElggMenuItem::factory($options);

	$options = array(
		'name' => 'books-readinglist',
		'text' => elgg_echo('readinglist:label:readinglist'),
		'href' => "books/readinglist/$user->username",
		'priority' => 300,
	);

	$return[] = ElggMenuItem::factory($options);

	return $return;
}