<?php
/**
 * Reading List Book Categories Output
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['book']        Book to display categories for
 * @uses $vars['make_links']  Make Categories active links for navigation 
 */

$book = elgg_extract('book', $vars, FALSE);
$links = elgg_extract('make_links', $vars, FALSE);

if (!elgg_instanceof($book, 'object', 'book')) {
	return TRUE;
}

// Get book categories
if ($book->categories) {
	if (is_array($book->categories)) {
		$categories_array = $book->categories;
		if ($links) {
			// Add link to each category
			foreach ($categories_array as $idx => $category) {
				$categories_array[$idx] = "<a title='{$category}' class='readinglist-category-link'>{$category}</a>"; 
			}
			$categories = implode(", ", $categories_array);
		} else {
			$categories = implode(", ", $book->categories);
		}
	} else {
		if ($link) {
			// Add link to string
			$categories = "<a href='#' title='{$book->categories}' class='readinglist-category-link'>{$book->categories}</a>";
		} else {
			$categories = $book->categories;
		}
	}
	echo $categories;
}

return TRUE;