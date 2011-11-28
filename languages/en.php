<?php
/**
 * Reading List English Language Translation
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$english = array(
	// Generic
	'books' => 'Books',
	'item:object:book' => 'Books',
	'books:add' => 'Add Book',
	
	// Page titles 
	'readinglist:title:ownedbooks' => '%s\'s  Books',
	'readinglist:title:addbook' => 'Add a Book',
	'readinglist:title:editbook' => 'Edit Book',

	// Labels
	'readinglist:label:readinglist' => 'Reading List',
	'readinglist:label:noresults' => 'No Results',
	'readinglist:error:notfound' => 'Book not found',
	'readinglist:label:save' => 'Save',
	'readinglist:error:requiredfields' => 'One or more required fields are missing',


	// Messages
	'readinglist:error:savebook' => 'There was an error saving the book',
	'readinglist:error:deletebook' => 'There was an error deleting the book',
	'readinglist:success:deletebook' => 'Successfully deleted book',
	'readinglist:success:savebook' => 'Successfully saved book',

	// Notifications

	// Other content
);

add_translation('en',$english);
