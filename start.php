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
elgg_register_event_handler('init', 'system', 'readinglist_init');

function readinglist_init() {

	// Define Google Application Info
	define(GOOGLE_DEV_KEY, elgg_get_plugin_setting('devkey', 'readinglist'));
	define(GOOGLE_APP_NAME, elgg_get_plugin_setting('appname', 'readinglist'));

	// Define relationships
	define(BOOK_REVIEW_RELATIONSHIP, 'book_review_of');
	define(READING_LIST_RELATIONSHIP, 'on_reading_list_of');
	define(READING_LIST_RELATIONSHIP_QUEUED, 'reading_list_queued');
	define(READING_LIST_RELATIONSHIP_COMPLETE, 'reading_list_complete');
	define(READING_LIST_RELATIONSHIP_READING, 'reading_list_reading');

	// Define reading status's
	define(BOOK_READING_STATUS_QUEUED, -1);
	define(BOOK_READING_STATUS_READING, 0);
	define(BOOK_READING_STATUS_COMPLETE, 1);

	// Add a site navigation item for logged in users 
	if (elgg_is_logged_in()) {
		$item = new ElggMenuItem('books', elgg_echo('books'), 'books/all');
		elgg_register_menu_item('site', $item);
	} else {
		$item = new ElggMenuItem('books', elgg_echo('books'), 'books/reading');
		elgg_register_menu_item('site', $item);
	}

	// Register and load library
	elgg_register_library('elgg:readinglist', elgg_get_plugins_path() . 'readinglist/lib/readinglist.php');

	// Register star rating JS/CSS
	$sr_js = elgg_get_simplecache_url('js', 'starrating');
	$sr_css = elgg_get_simplecache_url('css', 'starrating');

	elgg_register_js('jquery.starrating', $sr_js);
	elgg_register_css('jquery.starrating', $sr_css);

	// Register tiptip JS/CSS
	$t_js = elgg_get_simplecache_url('js', 'tiptip');
	$t_css = elgg_get_simplecache_url('css', 'tiptip');

	elgg_register_js('jquery.tiptip', $t_js, 'head', 501);
	elgg_register_css('jquery.tiptip', $t_css);

	// Register CSS
	$r_css = elgg_get_simplecache_url('css', 'readinglist/css');
	elgg_register_css('elgg.readinglist', $r_css);

	// Register JS libraries
	$r_js = elgg_get_simplecache_url('js', 'readinglist/readinglist');
	$b_js = elgg_get_simplecache_url('js', 'readinglist/bookrating');

	elgg_register_js('elgg.readinglist', $r_js);
	elgg_register_js('elgg.readinglist.bookrating', $b_js);

	// Register page handler
	elgg_register_page_handler('books','reading_list_page_handler');

	// Handler to register reading list filter menu items
	elgg_register_plugin_hook_handler('register', 'menu:reading-list-filter', 'reading_list_filter_menu_setup');

	// Register menu items for book reviews
	elgg_register_plugin_hook_handler('register', 'menu:book-review', 'readinglist_book_review_menu_setup');

	// Add stuff to the book entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'readinglist_book_menu_setup');

	// Add a new tab to the tabbed profile
	elgg_register_plugin_hook_handler('tabs', 'profile', 'readinglist_profile_tab_hander');

	// Handler to a group books link the owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'readinglist_owner_block_menu');

	// Remove comments/likes from book related river entries
	elgg_register_plugin_hook_handler('register', 'menu:river', 'readinglist_river_menu_setup');

	// EXPERIMENTAL: Register readinglist achievement class path
	elgg_register_plugin_hook_handler('get_achievement_class_paths', 'achievement', 'readinglist_achievement_hook');

	// Add the group books tool option
	add_group_tool_option('books', elgg_echo('groups:enablebooks'), TRUE);

	// Entity url handler for books
	elgg_register_entity_url_handler('object', 'book', 'readinglist_book_url_handler');

	// Entiry url handler for book reviews
	elgg_register_entity_url_handler('object', 'book_review', 'readinglist_review_url_handler');

	// Extend public dashboard sidebar
	elgg_extend_view('publicdashboard/sidebar', 'readinglist/publicreading', 500);

	// Whitelist ajax views
	elgg_register_ajax_view('books/existing');
	elgg_register_ajax_view('books/duplicate');
	elgg_register_ajax_view('readinglist/status'); 
	elgg_register_ajax_view('readinglist/completed');

	// Genricmodule views (ugh)
	elgg_register_ajax_view('books/modules/list');
	elgg_register_ajax_view('books/modules/readinglist');
	elgg_register_ajax_view('books/modules/groupreadinglist');
	elgg_register_ajax_view('books/modules/reading');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'readinglist/actions';
	elgg_register_action('books/save', "$action_base/books/save.php");
	elgg_register_action('books/delete', "$action_base/books/delete.php", 'admin');
	elgg_register_action('books/rate', "$action_base/books/rate.php");
	elgg_register_action('books/check', "$action_base/books/check.php");
	elgg_register_action('books/review/add', "$action_base/books/review/add.php");
	elgg_register_action('books/review/delete', "$action_base/books/review/delete.php");
	elgg_register_action('readinglist/add', "$action_base/readinglist/add.php");
	elgg_register_action('readinglist/addgroup', "$action_base/readinglist/addgroup.php");
	elgg_register_action('readinglist/remove', "$action_base/readinglist/remove.php");
	elgg_register_action('readinglist/removegroup', "$action_base/readinglist/removegroup.php");
	elgg_register_action('readinglist/status', "$action_base/readinglist/status.php");

	return TRUE;
}

/**
 * Dispatcher for books
 *
 * URLs take the form of
 *  All books:       books/all
 *  User's books:    books/owner/<username>
 *  Public List		 books/reading
 *  View book:       books/view/<guid>/<title>
 *  New book:        books/add/<guid> (container: user, group, parent)
 *  Group books      books/group/<guid>/all
 *  Group browse     books/group/<guid>/browse
 *
 * Title is ignored
 *
 * @param array $page
 */
function reading_list_page_handler($page) {
	elgg_load_library('elgg:readinglist');
	elgg_load_css('elgg.readinglist');
	elgg_load_js('elgg.readinglist');

	// Load google libs
	elgg_load_library('gapc:Client'); // Main client
	elgg_load_library('gapc:Books');  // Books service

	if (elgg_is_xhr()) {
		switch($page[0]) {
			case 'search':
			 	$term = get_input('term');
				$limit = get_input('limit');
				$offset = get_input('offset');
				$results = google_books_title_search($term, $limit, $offset);
				echo elgg_view('books/listvolumes', array(
					'books' => $results,
					'limit' => $limit,
					'offset' => $offset,
					'term' => $term,
				));
				break;
			default:
				// ..
				break;
		}
	} else {
		elgg_load_js('jquery.starrating');
		elgg_load_js('jquery.tiptip');
		elgg_load_css('jquery.tiptip');
		elgg_load_js('elgg.readinglist.bookrating');
		elgg_load_css('jquery.starrating');

		elgg_push_breadcrumb(elgg_echo('books'), 'books/all');
		switch($page[0]) {
			case 'all':
			default:
				gatekeeper();
				$params = readinglist_get_page_content_list();
				break;
			case 'owner':
				gatekeeper();
				$user = get_user_by_username($page[1]);
				if (!elgg_instanceof($user, 'user')) {
					$user = elgg_get_logged_in_user_entity();
				}
				$params = readinglist_get_page_content_list($user->guid);
				break;
			case 'reading':
				$params = readinglist_get_page_content_public_reading();
				break;
			case 'view':
				$params = readinglist_get_page_content_view($page[1]);
			 	break;
			case 'add':
				gatekeeper();
				elgg_load_css('lightbox');
				elgg_load_js('lightbox');
				$params = readinglist_get_page_content_edit($page[0], $page[1]);
				break;
			case 'edit':
				gatekeeper();
				$params = readinglist_get_page_content_edit($page[0], $page[1]);
				break;
			case 'group':
				group_gatekeeper();
				if ($page[2] == 'all') {
					$params = readinglist_get_page_content_group_list($page[1]);
				} else if ($page[2] == 'browse') {
					// Browse mode, allows group owners/admins to add books to group list
					$group = get_entity($page[1]);
					if ($group->canEdit()) {
						elgg_set_page_owner_guid($page[1]);
						$title = elgg_echo('readinglist:title:groupbooks', array($group->name));
						elgg_push_breadcrumb($title);
						$params = readinglist_get_page_content_list();
						$params['title'] = $title;
						elgg_push_breadcrumb(elgg_echo('readinglist:label:browse'));
					} else {
						forward();
					}
				}
				break;
			// Super simple image proxy
			case 'secureimg':
				header('Content-type: image/jpeg;');
			
				// Ignore access for public view, this is iffy but safe by obscurity	
				$ia = elgg_get_ignore_access();
				elgg_set_ignore_access(TRUE);

				$book = get_entity($page[1]);

				if (elgg_instanceof($book, 'object', 'book')) {
					header('Content-type: image/jpeg;');
					header("Content-type: image/jpeg");
					header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
					header("Pragma: public");
					header("Cache-Control: public");

					$thumb_size = get_input('size', 'small') . "_thumbnail";
				
					$thumb = $book->$thumb_size;

					readfile(html_entity_decode($thumb));
					
					elgg_set_ignore_access($ia);
					return TRUE;
				} else {
					elgg_set_ignore_access($ia);
					return FALSE;
				}

				break;
		}

		if (!$params['filter']) {
			$params['filter'] = elgg_view_menu('reading-list-filter', array(
				'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default',
				'sort_by' => 'priority',
			));
		}

		$body = elgg_view_layout($params['layout'] ? $params['layout'] : 'content', $params);

		echo elgg_view_page($params['title'], $body);
	}

	return TRUE;
}


/**
 * Remove comment or likes button from book objects
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown
 */
function readinglist_river_menu_setup($hook, $type, $value, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];
		$object = $item->getObjectEntity();

		$remove = array();

		switch ($object->getSubtype()) {
			case 'book':
				$remove[] = 'likes';
				$remove[] = 'comment';
				break;
			case 'book_review':
				$remove[] = 'likes';
				break;
		}

		if (is_array($value)) {
			foreach ($value as $idx => $item) {
				if (in_array($item->getName(), $remove)) {
					unset($value[$idx]);
				}
			}
		}
	}
	return $value;
}

/**
 * Populates the ->getUrl() method for a book
 *
 * @param ElggEntity entity
 * @return string request url
 */
function readinglist_book_url_handler($entity) {
	return elgg_get_site_url() . "books/view/{$entity->guid}/";
}

/**
 * Populates the ->getUrl() method for a book
 *
 * @param ElggEntity entity
 * @return string request url
 */
function readinglist_review_url_handler($entity) {
	return elgg_get_site_url() . "books/view/{$entity->book_guid}/";
}


/**
 * Reading list filter menu setup
 */
function reading_list_filter_menu_setup($hook, $type, $return, $params) {
	$user = elgg_get_logged_in_user_entity();

	$page_owner = elgg_get_page_owner_entity();

	if ($user) {
		if (elgg_instanceof($page_owner, 'group')) {
			// Group owners/admins can add books to the group list
			if ($page_owner->canEdit()) {
				$options = array(
					'name' => 'books-browse',
					'text' => elgg_echo('readinglist:label:addbooks'),
					'href' => 'books/group/' . $page_owner->guid . '/browse',
					'priority' => 200,
				);

				$return[] = ElggMenuItem::factory($options);
			} 

			// Group readinglist tab
			$options = array(
				'name' => 'books-group',
				'text' => elgg_echo('readinglist:label:booklist'),
				'href' => 'books/group/' . $page_owner->guid . '/all',
				'priority' => 100,
			);

			$return[] = ElggMenuItem::factory($options);
		} else {
			$options = array(
				'name' => 'books-all',
				'text' => elgg_echo('all'),
				'href' => 'books/all',
				'priority' => 100,
			);

			$return[] = ElggMenuItem::factory($options);

			$options = array(
				'name' => 'books-mine',
				'text' => elgg_echo('readinglist:label:mine'),
				'href' => "books/owner/$user->username",
				'priority' => 200,
			);

			$return[] = ElggMenuItem::factory($options);

			$options = array(
				'name' => 'books-readinglist',
				'text' => elgg_echo('readinglist:label:readinglist'),
				'href' => "profile/$user->username/readinglist",
				'priority' => 300,
			);

			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Adds a delete link to the book review menu
 */
function readinglist_book_review_menu_setup($hook, $type, $return, $params) {
	$review = $params['review'];

	$reviewer = $params['reviewer'];

	if (elgg_instanceof($review, 'object', 'book_review') && elgg_instanceof($reviewer, 'user')) {

		$book = get_entity($review->book_guid);

		if (elgg_instanceof($book, 'object', 'book')) {
			$rating = elgg_view('output/bookrating', array(
				'entity' => $book,
				'user' => $reviewer,
			));

			$options = array(
				'name' => 'rating',
				'href' => FALSE,
				'text' => $rating,
				'priority' => 100,
			);
			$return[] = ElggMenuItem::factory($options);
		}

		if ($review->canEdit()) {
			$url = elgg_http_add_url_query_elements('action/books/review/delete', array(
				'guid' => $review->guid,
			));

			$options = array(
				'name' => 'delete',
				'href' => $url,
				'text' => "<span class=\"elgg-icon elgg-icon-delete\"></span>",
				'confirm' => elgg_echo('deleteconfirm'),
				'text_encode' => false,
				'priority' => 200,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Modify the entity menu for books
 */
function readinglist_book_menu_setup($hook, $type, $return, $params) {

	if (!elgg_is_logged_in()) {
		return array();
	}

	$entity = $params['entity'];

	if (elgg_instanceof($entity, 'object', 'book') && elgg_is_logged_in()) {
		// Will remove these items
		$remove = array('access', 'likes', 'delete');

		// Remove items from entity menu
		foreach ($return as $idx => $item) {
			if (in_array($item->getName(), $remove)) {
				unset($return[$idx]);
			}
		}

		// Admin only delete
		if (elgg_is_admin_logged_in() && !elgg_in_context('book_sidebar')) {
			$options = array(
				'name' => 'delete',
				'text' => elgg_view_icon('delete'),
				'title' => elgg_echo('delete:this'),
				'href' => "action/books/delete?guid={$entity->getGUID()}",
				'confirm' => elgg_echo('readinglist:label:deleteconfirm'),
				'priority' => 300,
			);
			$return[] = ElggMenuItem::factory($options);
		}

		if (elgg_in_context('reading_list')) {
			// Display user rating if viewing a user's reading list
			$page_owner = elgg_get_page_owner_entity();
			$rating = elgg_view('output/bookrating', array(
				'entity' => $entity,
				'user' => $page_owner,
			));

			if (elgg_get_logged_in_user_guid() != $page_owner->guid) {
				elgg_load_library('elgg:readinglist');

				// Add user's reading status
				$status_info = readinglist_get_reading_status($entity->guid, $page_owner->guid);

				$status = $status_info['status'];

				switch ($status) {
					case BOOK_READING_STATUS_COMPLETE:
						$annotation = $status_info['annotation'];
						$completed = date('F j, Y', $annotation->time_created);
						$status_label = elgg_echo('readinglist:label:completed', array($completed));
						break;
					case BOOK_READING_STATUS_QUEUED:
						$status_label = elgg_echo('readinglist:label:status:queued');
						break;
					case BOOK_READING_STATUS_READING:
						$status_label = elgg_echo('readinglist:label:status:reading');
						break;
				}

				$options = array(
					'name' => 'reading-status',
					'href' => FALSE,
					'text' => $status_label,
					'priority' => 15,
				);

				$return[] = ElggMenuItem::factory($options);
			}

		} else {
			if (!elgg_in_context('book_sidebar')) {
				// Display average rating elsewhere
				$rating = elgg_view('output/averagebookrating', array(
					'entity' => $entity,
				));
			}
		}

		if ($rating) {
			$options = array(
				'name' => 'book-rating',
				'href' => FALSE,
				'text' => $rating,
				'priority' => 25,
				'section' => 'info',
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Handler to add a reading list tab to the tabbed profile
 */
function readinglist_profile_tab_hander($hook, $type, $value, $params) {
	if (elgg_is_logged_in()) {
		$value[] = 'readinglist';
	}
	return $value;
}


/**
 * Plugin hook to add books to the group profile block
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown
 */
function readinglist_owner_block_menu($hook, $type, $value, $params) {
	if (elgg_instanceof($params['entity'], 'group')) {
		if ($params['entity']->books_enable == 'yes') {
			$url = "books/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('books', elgg_echo('readinglist:label:groupbooks'), $url);
			$value[] = $item;
		}
	}
	return $value;
}

/**
 * Register the readinglist achievements class path
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown
 */
function readinglist_achievement_hook($hook, $type, $value, $params) {
	$value[] = elgg_get_plugins_path() . 'readinglist/classes/';
	return $value;
}
