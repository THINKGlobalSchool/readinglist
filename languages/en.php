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
	'item:object:book_review' => 'Book Reviews',
	'books:add' => 'Find Book',
	'readinglist' => 'Reading List',
	'profile:readinglist' => 'Reading List',
	
	// Page titles 
	'readinglist:title:ownedbooks' => 'Books Added By %s',
	'readinglist:title:findbook' => 'Find a Book',
	'readinglist:title:editbook' => 'Edit Book',
	'readinglist:title:userreadinglist' => '%s\'s Reading List',
	'readinglist:title:allbooks' => 'All Books',
	'readinglist:title:publicreading' => 'What is TGS Reading?',
	'readinglist:title:groupbooks' => '%s\'s Book List',

	// Labels
	'readinglist:label:readinglist' => 'Reading List',
	'readinglist:label:noresults' => 'No Results',
	'readinglist:label:addbook' => 'Add Book to Spot',
	'readinglist:label:save' => 'Save',
	'readinglist:label:addedby' => 'Added by %s',
	'readinglist:label:author' => 'By: ',
	'readinglist:label:googlelink' => 'View on Google Books',
	'readinglist:label:yourrating' => 'Your Rating',
	'readinglist:label:averagerating' => 'Average Rating',
	'readinglist:label:reviews' => 'Reviews',
	'readinglist:label:ownerreviews' => '%s\'s Reviews',
	'readinglist:label:noreviews' => 'No Reviews',
	'readinglist:label:addreview' => 'Add Review',
	'readinglist:label:addcomment' => 'Add Comment',
	'readinglist:label:titleexists' => '%s has already added this book!',
	'readinglist:label:searchanyway' => 'Search Anyway',
	'readinglist:label:searchresults' => 'Results for \'%s\'',
	'readinglist:label:duplicate' => 'This book already exists!',
	'readinglist:label:duplicatedescription' => 'You cannot add a duplicate book',
	'readinglist:label:status:queued' => 'Want to read',
	'readinglist:label:status:reading' => 'Currently Reading',
	'readinglist:label:status:complete' => 'Completed',
	'readinglist:label:status' => 'Status',
	'readinglist:label:completed' => 'Completed: %s',
	'readinglist:label:readreviews' => 'Read %s\'s Review',
	'readinglist:label:viewall' => 'View All',
	'readinglist:label:any' => 'Any',
	'readinglist:label:category' => 'Category',
	'readinglist:label:mine' => 'Books I\'ve Added',
	'readinglist:label:order' => 'Order By',
	'readinglist:label:date' => 'Date Added',
	'readinglist:label:popular' => 'Popularity',
	'readinglist:label:rated' => 'Rating',
	'readinglist:label:sortasc' => 'Sort Ascending &#9650;',
	'readinglist:label:sortdesc' => 'Sort Descending &#9660;',
	'readinglist:label:rating' => 'Rating',
	'readinglist:label:ratingcount' => 'Based on %s rating(s)',
	'readinglist:label:submitreview' => 'Submit Review',
	'readinglist:label:togglereviews' => 'Add a Review',
	'readinglist:label:hidereviewform' => 'Hide Review Form',
	'readinglist:label:showless' => '« Less',
	'readinglist:label:showmore' => 'More »',
	'readinglist:label:findanother' => 'Find Another Book',
	'readinglist:label:addtoreadinglist' => 'Add to Reading List',
	'readinglist:label:completedate' => 'When was this book completed?',
	'readinglist:label:highestrated' => 'Highest Rated',
	'readinglist:label:mostpopular' => 'Most Popular',
	'readinglist:label:whoreading' => 'Who\'s reading this?',
	'readinglist:label:show' => 'Show',
	'readinglist:label:all' => 'All',
	'readinglist:label:groupbooks' => 'Group books',
	'readinglist:label:browse' => 'Browse',
	'readinglist:label:addbooks' => 'Add Books',
	'readinglist:label:browseallbooks' => 'Browse All Books',
	'readinglist:label:booklist' => 'Book List',
	'readinglist:label:devkey' => 'Google Developer Key',
	'readinglist:label:appname' => 'Application Name',
	'readinglist:label:deleteconfirm' => '!!!!!!!!!!!!!!!! WARNING !!!!!!!!!!!!!!!! Deleting this book will remove it from all reading lists! Proceed?',

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
	'readinglist:error:permission' => 'You do not have permission to view that item',
	'readinglist:error:ratingrequired' => '*** You must supply a rating in order to submit a review',
	'readinglist:success:statuschanged' => 'Reading status updated',
	'readinglist:success:rate' => 'Rating Saved',
	'readinglist:success:readinglistadd' => 'Book successfully added to your reading list',
	'readinglist:success:readinglistremove' => 'Book successfully removed from your reading list',
	'readinglist:success:groupreadinglistadd' => 'Book successfully added to the group reading list',
	'readinglist:success:groupreadinglistremove' => 'Book successfully removed from the group reading list',
	'readinglist:success:deletebook' => 'Successfully deleted book',
	'readinglist:success:deletereview' => 'Successfully deleted review',
	'readinglist:success:savebook' => 'Successfully saved book',
	'readinglist:success:savereview' => 'Successfully saved review',

	// River
	'river:create:object:book' => '%s added a new book titled %s',
	'river:create:object:book_review' => '%s added a review for the book %s',
	'river:comment:object:book_review' => '%s commented on a review for the book %s',
	'river:readinglist:object:book' => '%s added the book %s to their reading list',

	// Widgets
	'readinglist:widget:books' => 'User\'s reading list',
	'readinglist:widget:user_books' => '%s\'s Reading List',
	'readinglist:widget:books_desc' => 'Display user\'s reading list',

	// Notifications

	// Other content
	'groups:enablebooks' => 'Enable group books',

	// Achievement Example
	'achievements:book_pages_500:title' => 'Read 500 Pages!',
	'achievements:book_pages_500:description' => 'You have read 500 pages! WOW!',

	'achievements:book_pages_2000:title' => 'Read 2000 Pages!',
	'achievements:book_pages_2000:description' => 'You have read 2000 pages! WOW!',

	'achievements:book_pages_5000:title' => 'Read 5000 Pages!',
	'achievements:book_pages_5000:description' => 'You have read 5000 pages! WOW!',

	'achievements:book_pages_15000:title' => 'Read 15000 Pages!',
	'achievements:book_pages_15000:description' => 'You have read 15000 pages! WOW!',

	'achievements:book_pages_30000:title' => 'Read 30000 Pages!',
	'achievements:book_pages_30000:description' => 'You have read 30000 pages! WOW!',
	
	// Achievements
	'achievements:top_reader_2011_2012:title' => 'Top Reader 2011-2012',
	'achievements:top_reader_2011_2012:description' => 'You are the top reader for the 2011-2012 school year!',
	
	// Achievements
	'achievements:top_reader_2012_2013:title' => 'Top Reader 2012-2013',
	'achievements:top_reader_2012_2013:description' => 'You are the top reader for the 2012-2013 school year!',
	
	// Achievements labels
	'achievements:subtype:reading_list_complete' => 'Reading List Achievements',
);

add_translation('en',$english);
