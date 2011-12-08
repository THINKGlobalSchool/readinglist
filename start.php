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
 * - Admin area for dev key
 */
elgg_register_event_handler('init', 'system', 'readinglist_init');

function readinglist_init() {

	// Define relationships
	define(BOOK_REVIEW_RELATIONSHIP, 'book_review_of');
	define(READING_LIST_RELATIONSHIP, 'on_reading_list_of');

	// Define reading status's
	define(BOOK_READING_STATUS_QUEUED, -1);
	define(BOOK_READING_STATUS_READING, 0);
	define(BOOK_READING_STATUS_COMPLETE, 1);

	// Add a site navigation item
	$item = new ElggMenuItem('books', elgg_echo('books'), 'books/all');
	elgg_register_menu_item('site', $item);

	// Register and load library
	elgg_register_library('elgg:readinglist', elgg_get_plugins_path() . 'readinglist/lib/readinglist.php');

	// Register star rating JS/CSS
	$sr_js = elgg_get_simplecache_url('js', 'starrating');
	$sr_css = elgg_get_simplecache_url('css', 'starrating');

	elgg_register_simplecache_view('js/starrating');
	elgg_register_simplecache_view('css/starrating');

	elgg_register_js('jquery.starrating', $sr_js);
	elgg_register_css('jquery.starrating', $sr_css);

	// Register CSS
	$r_css = elgg_get_simplecache_url('css', 'readinglist/css');
	elgg_register_simplecache_view('css/readinglist/css');
	elgg_register_css('elgg.readinglist', $r_css);

	// Register JS libraries
	$r_js = elgg_get_simplecache_url('js', 'readinglist/readinglist');
	$b_js = elgg_get_simplecache_url('js', 'readinglist/bookrating');

	elgg_register_simplecache_view('js/readinglist/readinglist');
	elgg_register_simplecache_view('js/readinglist/bookrating');

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

	// Entity url handler
	register_entity_url_handler('readinglist_url_handler', 'object', 'book');

	// Extend public dashboard sidebar
	elgg_extend_view('publicdashboard/sidebar', 'readinglist/publicreading', 500);

	// Register actions
	$action_base = elgg_get_plugins_path() . 'readinglist/actions';
	elgg_register_action('books/save', "$action_base/books/save.php");
	elgg_register_action('books/delete', "$action_base/books/delete.php");
	elgg_register_action('books/rate', "$action_base/books/rate.php");
	elgg_register_action('books/check', "$action_base/books/check.php");
	elgg_register_action('books/review/add', "$action_base/books/review/add.php");
	elgg_register_action('books/review/delete', "$action_base/books/review/delete.php");
	elgg_register_action('readinglist/add', "$action_base/readinglist/add.php");
	elgg_register_action('readinglist/remove', "$action_base/readinglist/remove.php");
	elgg_register_action('readinglist/status', "$action_base/readinglist/status.php");

	// Load google libs
	elgg_load_library('gapc:apiClient');       // Main client
 	elgg_load_library('gapc:apiBooksService'); // Books service

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
	elgg_load_css('elgg.readinglist');
	elgg_load_js('elgg.readinglist');

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
		elgg_load_js('elgg.readinglist.bookrating');
		elgg_load_css('jquery.starrating');

		elgg_push_breadcrumb(elgg_echo('books'), 'books/all');
		switch($page[0]) {
			case 'all':
			default:
				$params = readinglist_get_page_content_list();
				break;
			case 'owner':
				$user = get_user_by_username($page[1]);
				if (!elgg_instanceof($user, 'user')) {
					$user = elgg_get_logged_in_user_entity();
				}
				$params = readinglist_get_page_content_list($user->guid);
				break;
			case 'readinglist':
				$user = get_user_by_username($page[1]);
				if (!elgg_instanceof($user, 'user')) {
					$user = elgg_get_logged_in_user_entity();
				}
				$params = readinglist_get_page_content_readinglist($user->guid);
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

	return TRUE;
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

	if ($user) {
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
			'href' => "books/readinglist/$user->username",
			'priority' => 300,
		);

		$return[] = ElggMenuItem::factory($options);
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
		return FALSE;
	}

	$entity = $params['entity'];

	if (elgg_instanceof($entity, 'object', 'book') && elgg_is_logged_in()) {
		if (elgg_in_context('profile_reading_list')) {
			// Display user rating if viewing a user's reading list
			$page_owner = elgg_get_page_owner_entity();
			$rating = elgg_view('output/bookrating', array(
				'entity' => $entity,
				'user' => $page_owner,
			));

			foreach ($return as $idx => $item) {
				// Nuke access display and likes
				if ($item->getName() == 'access' || $item->getName() == 'likes') {
					unset($return[$idx]);
				}
			}

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

		} else {
			// Display average rating elsewhere
			$rating = elgg_view('output/averagebookrating', array(
				'entity' => $entity,
			));
		}


		$options = array(
			'name' => 'book-rating',
			'href' => FALSE,
			'text' => $rating,
			'priority' => 25,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Handler to add a reading list tab to the tabbed profile
 */
function readinglist_profile_tab_hander($hook, $type, $value, $params) {
	$value[] = 'readinglist';
	return $value;
}
