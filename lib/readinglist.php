<?php
/**
 * Reading List Helper Functions
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

/**
 * Get books list content
 */
function readinglist_get_page_content_list($container_guid = null) {
	$logged_in_user_guid = elgg_get_logged_in_user_guid();
	
	if ($container_guid) {
		$entity = get_entity($container_guid);

		// do not show button or select a tab when viewing someone else's posts
		if ($container_guid == $logged_in_user_guid) {
			elgg_register_title_button();
		}

		$params['title'] = elgg_echo('readinglist:title:ownedbooks', array($entity->name));

		elgg_push_breadcrumb($params['title']);
	} else {
		elgg_register_menu_item('title', array(
			'name' => 'add',
			'href' => "books/add/" . elgg_get_logged_in_user_guid(),
			'text' => elgg_echo("books:add"),
			'link_class' => 'elgg-button elgg-button-action',
		));

		$params['title'] = elgg_echo('readinglist:title:allbooks');
	}

	$content = elgg_view('readinglist/filter', array(
		'status' => FALSE,
		'category' => TRUE,
		'order_by' => TRUE,
	));

	// See if we have a group page owner
	$page_owner = elgg_get_page_owner_entity();
	if (elgg_instanceof($page_owner, 'group')) {
		$group_guid = $page_owner->guid;
	}

	// Book list Module
	$content .= elgg_view('modules/genericmodule', array(
		'view' => 'books/modules/list',
		'module_id' => 'readinglist-books-list-module',
		'module_class' => 'readinglist-module',
		'view_vars' => array(
			'container_guid' => $container_guid,
			'category' => '',
			'order_by' => '',
			'sort_order' => '',
			'group_guid' => $group_guid,
		),
	));

	$params['content'] = $content;

	$options = array(
		'type' => 'object',
		'subtype' => 'book',
		'full_view' => FALSE,
		'limit' => 5,
		'pagination' => FALSE,
	);

	$pop_options = array(
		'order_by_metadata' => array('name' => 'popularity', 'direction' => 'desc', 'as' => 'integer'),
	);

	$rated_options = array(
		'order_by_metadata' => array('name' => 'average_rating', 'direction' => 'desc', 'as' => 'integer'),
	);

	elgg_set_context('book_sidebar');
	$popular_books = elgg_list_entities_from_relationship($options + $pop_options);
	$rated_books = elgg_list_entities_from_relationship($options + $rated_options);
	elgg_pop_context();

	$params['sidebar'] = elgg_view_module('aside', elgg_echo('readinglist:label:mostpopular'), $popular_books, array(
		'class' => 'book-sidebar-module'
	));

	$params['sidebar'] .= elgg_view_module('aside', elgg_echo('readinglist:label:highestrated'), $rated_books, array(
		'class' => 'book-sidebar-module'
	));

	return $params;
}

/**
 * Build content for editing/adding a book
 */
function readinglist_get_page_content_edit($page, $guid) { 
	$params['filter'] = FALSE;
	$params['layout'] = 'one_sidebar';
	
	// General form vars
	$form_vars = array(
		'id' => 'book-save-form', 
		'name' => 'book-save-form'
	);
		
	if ($page == 'edit') {
		$book = get_entity($guid);
		
		$params['title'] = elgg_echo('readinglist:title:editbook');
		
		if (elgg_instanceof($book, 'object', 'book') && $book->canEdit()) {
			$owner = get_entity($book->container_guid);
			
			elgg_set_page_owner_guid($owner->getGUID());
			
			elgg_push_breadcrumb($book->title, $book->getURL());
			elgg_push_breadcrumb('edit');

			$body_vars = readinglist_prepare_form_vars($book);

			$params['content'] .= elgg_view_form('books/save', $form_vars, $body_vars);
		} else {
			register_error(elgg_echo('readinglist:error:notfound'));
			forward(REFERER);
		}
	} else {
		if (!$guid) {
			$container = elgg_get_logged_in_user_entity();
		} else {
			$container = get_entity($guid);
		}
		
		elgg_push_breadcrumb(elgg_echo('add'));
		
		$params['title'] = elgg_echo('readinglist:title:findbook');
		
		$body_vars = readinglist_prepare_form_vars();

		$params['content'] .= elgg_view_form('books/save', $form_vars, $body_vars);
	}	
	return $params;
}

/**
 * View a book
 */
function readinglist_get_page_content_view($guid) {
	$ia = elgg_get_ignore_access();
	if (!elgg_is_logged_in()) {
		elgg_set_ignore_access(TRUE);
	}

	$book = get_entity($guid);

	elgg_set_ignore_access($ia);

	$container = get_entity($book->container_guid);
	elgg_set_page_owner_guid($container->guid);


	if (!$book) {
		register_error(elgg_echo('readinglist:error:permission'));
		forward(REFERER);
	}

	elgg_push_breadcrumb($container->name, elgg_get_site_url() . 'books/owner/' . $container->username);
	elgg_push_breadcrumb($book->title, $book->getURL());
	$params['title'] = $book->title;
	$params['content'] .= elgg_view_entity($book, array('full_view' => TRUE));	
	$params['layout'] = 'one_sidebar';


	if (elgg_is_logged_in()) {
		// Add a sidebar button to add a new book
		$params['sidebar'] = elgg_view('output/url', array(
			'text' => elgg_echo('readinglist:label:findanother'),
			'class' => 'elgg-button elgg-button-submit',
			'href' => 'books/add/' . elgg_get_logged_in_user_guid(),
		)) . "<br /><br />";

		$params['sidebar'] .= elgg_view('readinglist/whosreading', array('guid' => $guid));
	}

	return $params;
}

/**
 * View a user's reading list
 */
function readinglist_get_page_content_readinglist($guid) {

	$entity = get_entity($guid);

	$title = elgg_echo('readinglist:title:userreadinglist', array($entity->name));

	elgg_push_breadcrumb($entity->name, "books/owner/" . $entity->username);
	elgg_push_breadcrumb(elgg_echo('readinglist'));

	$params['title'] = $title;

	$content = elgg_view('readinglist/filter', array(
		'status' => TRUE,
		'category' => TRUE,
		'order_by' => TRUE,
	));

	// Book list Module
	$content .= elgg_view('modules/genericmodule', array(
		'view' => 'books/modules/readinglist',
		'module_id' => 'readinglist-books-readinglist-module',
		'module_class' => 'readinglist-module',
		'view_vars' => array(
			'user_guid' => $guid,
			'status' => 'any',
			'category' => 'any',
			'order_by' => '',
			'sort_order' => '',
		),
	));

	$params['content'] = $content;
	return $params;
}

/**
 * Public 'What's TGS Reading' content
 */
function readinglist_get_page_content_public_reading() {
 	$title = elgg_echo('readinglist:title:publicreading');
	elgg_push_breadcrumb($title);
	$params['title'] = $title;

	/* These options will grab any book that is on a users readinglist
	and is grouped by the entity guid, to prevent dupes */
	$options = array(
		'type' => 'object',
		'subtype' => 'book',
		'full_view' => false,
		'relationship' => READING_LIST_RELATIONSHIP,
		'relationship_guid' => ELGG_ENTITIES_ANY_VALUE,
		'inverse_relationship' => TRUE,
		'group_by' => 'e.guid',
	);

	elgg_push_context('public_reading');
	// Ignore access so the public can take a peek
	$ia = elgg_get_ignore_access();
	elgg_set_ignore_access(TRUE);
	$content = elgg_list_entities_from_relationship($options);
	elgg_set_ignore_access($ia);
	elgg_pop_context();

	// If theres no content, display a nice message
	if (!$content) {
		$content = "<h3 class='center'>" . elgg_echo("readinglist:label:noresults") . "</h3>";
	}

	$params['content'] = $content;

	return $params;
}

/**
 * Get group books list content
 */
function readinglist_get_page_content_group_list($container_guid = null) {
	$logged_in_user_guid = elgg_get_logged_in_user_guid();
	$group = get_entity($container_guid);

	if (!elgg_instanceof($group, 'group')) {
		forward();
	}

	$params['title'] = elgg_echo('readinglist:title:groupbooks', array($group->name));

	elgg_push_breadcrumb($params['title']);

	$content = elgg_view('readinglist/filter', array(
		'status' => FALSE,
		'category' => TRUE,
		'order_by' => TRUE,
	));

	// Book list Module
	$content .= elgg_view('modules/genericmodule', array(
		'view' => 'books/modules/groupreadinglist',
		'module_id' => 'readinglist-books-list-module',
		'module_class' => 'readinglist-module',
		'view_vars' => array(
			'category' => '',
			'order_by' => '',
			'sort_order' => '',
			'group_guid' => elgg_get_page_owner_guid(),
		),
	));

	$params['content'] = $content;

	$options = array(
		'type' => 'object',
		'subtype' => 'book',
		'full_view' => FALSE,
		'limit' => 5,
		'pagination' => FALSE,
	);

	return $params;
}

/**
 * Prepare form vars for book save form
 *
 * @param ElggObject $book
 * @return array
 */
function readinglist_prepare_form_vars($book = NULL) {
	// input names => defaults
	$values = array(
		'tags' => NULL,
		'container_guid' => NULL,
		'guid' => NULL,
		'title' => NULL,
	);
	
	if ($book) {
		foreach (array_keys($values) as $field) {
			if (isset($book->$field)) {
				$values[$field] = $book->$field;
			}
		}
	}

	if (elgg_is_sticky_form('book-save-form')) {
		$sticky_values = elgg_get_sticky_values('book-save-form');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('book-save-form');

	return $values;
}

/**
 * Helper function to add a book to a user's readinglist
 *
 * @param $book_guid  Guid of the book
 * @param $user_guid  Guid of the user
 * @return void
 */
function add_to_user_readinglist($book_guid, $user_guid) {
	// Add reading list relationship
	add_entity_relationship($book_guid, READING_LIST_RELATIONSHIP, $user_guid);

	// Add queued relationship
	add_entity_relationship($book_guid, READING_LIST_RELATIONSHIP_QUEUED, $user_guid);

	// Add status annotation
	$status = create_annotation(
		$book_guid,
		'book_reading_status',
		BOOK_READING_STATUS_QUEUED,
		"",
		$user_guid,
		ACCESS_LOGGED_IN
	);

	// Check if the user has already rated this book
	$options = array(
		'guid' => $book_guid,
		'annotation_names' => array('bookrating'),
		'annotation_owner_guids' => array($user_guid),
		'count' => TRUE,
	);

	$ratings = elgg_get_annotations($options);

	// If we have a rating, we'll set the user rating to the existing value
	if ($ratings) {
		unset($options['count']);
		$ratings = elgg_get_annotations($options);
		$rating = $ratings[0];
		$value = $rating->value;
	} else {
		// Set it to 0 otherwise
		$value = 0;
	}

	// Delete existing user_bookratings
	elgg_delete_annotations(array(
		'guid' => $book_guid,
		'annotation_owner_guids' => array($user_guid),
		'annotation_names' => 'user_bookrating',
	));

	// Create a new user_bookrating
	$user_rating = create_annotation(
		$book_guid,
		'user_bookrating',
		$value,
		"",
		$user_guid,
		ACCESS_LOGGED_IN
	);

	// Add a river entry (not sure what action type to go with here, so using a new one called 'readinglist')
	add_to_river('river/relationship/readinglist/add', 'readinglist', $user_guid, $book_guid);
}

/**
 * Helper function to perform a google books search
 *
 * @param  string $search - Title of book to search
 * @return mixed
 */
function google_books_title_search($search = '', $limit = 10, $offset = 0) {
	if (!is_string($search) || empty($search)) {
		// Bail out if we don't have a search term
		return FALSE;
	}

	// Create client
	$client = new apiClient();
	$client->setDeveloperKey(GOOGLE_DEV_KEY);
	$client->setApplicationName(GOOGLE_APP_NAME);

	// Create books service
	$service = new apiBooksService($client);

	// Set volumes
	$volumes = $service->volumes;

	// Search options
	$options = array(
		'maxResults' => $limit,
		'startIndex' => $offset,
		'printType' => 'books',
		'country' => 'us', // override IP location
	);

	$results = $volumes->listVolumes($search, $options);

	return $results;
}

/**
 * Helper function to get a given volume's full descriptio
 *
 * @param  string $volume_id - The google volume id
 * @return string
 */
function google_books_get_volume_full_description($volume_id) {
	if (!is_string($volume_id) || empty($volume_id)) {
		// Bail out if we don't have a volume id
		return FALSE;
	}

	// Create client
	$client = new apiClient();
	$client->setDeveloperKey(GOOGLE_DEV_KEY);
	$client->setApplicationName(GOOGLE_APP_NAME);

	// Create books service
	$service = new apiBooksService($client);

	// Set volumes
	$volumes = $service->volumes;

	// Get params (override IP location)
	$options = array(
		'country' => 'us',
	);

	// Get the specified volume
	$result = $volumes->get($volume_id, $options);

	$volumeInfo = $result['volumeInfo'];

	$description = $volumeInfo['description'];

	return $description;
}

/**
 * Helper function to grab a users reading status
 *
 * @param int $book_guid The guid of the book
 * @param int $user_guid The guid of the user
 * @return array         Status array
 */
function readinglist_get_reading_status($book_guid, $user_guid) {
	// Options to grab the current users reading status
	$options = array(
		'guid' => $book_guid,
		'annotation_names' => array('book_reading_status'),
		'annotation_owner_guids' => array($user_guid),
	);

	$status_annotations = elgg_get_annotations($options);

	$annotation = NULL;

	if ($status_annotations[0] && $status_annotations[0]->value !== NULL) {
		$status = $status_annotations[0]->value;
		$annotation = $status_annotations[0];
	} else {
		// Shouldn't be here, but just in case
		$status = BOOK_READING_STATUS_QUEUED;
	}

	return array(
		'status' => $status,
		'annotation' => $annotation,
	);
}

/**
 * Helper function to grab a user's complete date annotation
 *
 * @param int $book_guid The guid of the book
 * @param int $user_guid The guid of the user
 * @return int|null      Complete date
 */
function readinglist_get_complete_date($book_guid, $user_guid) {
	// Options to grab the current users complete date
	$options = array(
		'guid' => $book_guid,
		'annotation_names' => array('book_complete_date'),
		'annotation_owner_guids' => array($user_guid),
	);

	$complete_annotations = elgg_get_annotations($options);

	if ($complete_annotations[0]) {
		return $complete_annotations[0]->value;
	} else {
		return NULL;
	}
}

/**
 * Helper function to find all available book categories
 */
function readinglist_get_available_categories() {
	// Batch options
	$options = array(
		'type' => 'object',
		'subtype' => 'book',
		'limit' => 0,
	);

	$books = new ElggBatch('elgg_get_entities', $options);

	$categories = array();

	foreach ($books as $book) {
		$categories[] = $book->categories;
	}

	// Flatten the array (could have multiple categories)
	// http://php.net/manual/en/function.array-values.php
	$tmp = (object) array('flattened' => array());

	array_walk_recursive($categories, create_function('&$v, $k, &$t', '$t->flattened[] = $v;'), $tmp);

	$flattened_categories = $tmp->flattened;

	// Remove empty categories
	$filtered_categories = array_filter($flattened_categories);

	// Unique categories
	$unique_categories = array_unique($filtered_categories);

	// Re-index the array and return it
	return array_values($unique_categories);
}

/**
 * Helper function to calculate the average rating for a book
 *
 * @param ElggEntity $book
 * @return int
 */
function readinglist_calculate_average_rating($book) {
	// Grab the average rating
	$options = array(
		'guid' => $book->guid,
		'annotation_names' => array('bookrating'),
		'annotation_calculation' => 'avg',
	);

	$rating = elgg_get_annotations($options);

	$rating = round($rating);

	// Make sure we're at least setting 0 if there no rating
	if (!$rating) {
		$rating = 0;
	}

	return $rating;
}

/**
 * Helper function to calculate the popularity of a book
 *
 * @param ElggEntity $book
 * @return int
 */
function readinglist_calculate_popularity($book) {
	$options = array(
		'type' => 'user',
		'relationship' => READING_LIST_RELATIONSHIP,
		'relationship_guid' => $book->guid,
		'inverse_relationship' => FALSE,
		'count' => TRUE,
	);

	$lists = elgg_get_entities_from_relationship($options);

	// Make sure we return at least 0
	if (!$lists) {
		$lists = 0;
	}

	return $lists;
}

/**
 * Modified version of the elgg get excerpt function
 * that doesn't butcher tags
 *
 * @param string $text      The full text to excerpt
 * @param int    $num_chars Return a string up to $num_chars long
 *
 * @return string
 */
function readinglist_get_excerpt($text, $num_chars = 250) {
	$text = trim($text);
	$string_length = elgg_strlen($text);

	if ($string_length <= $num_chars) {
		return $text;
	}

	// handle cases
	$excerpt = elgg_substr($text, 0, $num_chars);
	$space = elgg_strrpos($excerpt, ' ', 0);

	// don't crop if can't find a space.
	if ($space === FALSE) {
		$space = $num_chars;
	}
	$excerpt = trim(elgg_substr($excerpt, 0, $space));

	if ($string_length != elgg_strlen($excerpt)) {
		$excerpt .= '...';
	}

	return $excerpt;
}

/**
 * Helper function to determine if string is a valid timestamp
 *
 * @param string $timestamp
 * @return bool
 */
function readinglist_is_valid_timestamp($timestamp) {
    return ((string) (int) $timestamp === $timestamp)
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX);
}