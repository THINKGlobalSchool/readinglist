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
	'readinglist:label:save' => 'Save',
	'readinglist:label:addedby' => 'Added by %s',
	'readinglist:label:author' => 'By: ',
	'readinglist:label:googlelink' => 'View on Google Books',
	'readinglist:label:yourrating' => 'Your Rating',
	'readinglist:label:averagerating' => 'Average Rating',
	'readinglist:label:reviews' => 'Reviews',
	'readinglist:label:noreviews' => 'No Reviews',
	'readinglist:label:addreview' => 'Add Review',
	'readinglist:label:addcomment' => 'Add Comment',
	'readinglist:label:titleexists' => 'A book titled \'%s\' already exists:',
	'readinglist:label:searchanyway' => 'Search Anyway',
	'readinglist:label:searchresults' => 'Results for \'%s\'',
	'readinglist:label:duplicate' => 'This book already exists!',
	'readinglist:label:duplicatedescription' => 'You cannot add a duplicate book',

	// Messages
	'readinglist:error:savebook' => 'There was an error saving the book',
	'readinglist:error:savereview' => 'There was an error saving the review',
	'readinglist:error:deletebook' => 'There was an error deleting the book',
	'readinglist:error:deletereview' => 'There was an error deleting the review',
	'readinglist:error:requiredfields' => 'One or more required fields are missing',
	'readinglist:error:notfound' => 'Book not found',
	'readinglist:error:reviewnotfound' => 'Review not found',
	'readinglist:error:rate' => 'Error saving rating',
	'readinglist:error:invalidrating' => 'Invalid Rating',
	'readinglist:success:rate' => 'Rating Saved',
	'readinglist:success:deletebook' => 'Successfully deleted book',
	'readinglist:success:deletereview' => 'Successfully deleted review',
	'readinglist:success:savebook' => 'Successfully saved book',
	'readinglist:success:savereview' => 'Successfully saved review',

	// Notifications

	// Other content
);

add_translation('en',$english);
