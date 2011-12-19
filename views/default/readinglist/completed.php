<?php
/**
 * Reading List Completed Output
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['user_guid']  User
 * @uses $vars['book_guid']  Book guid
 */

elgg_load_library('elgg:readinglist');

$status_info = readinglist_get_reading_status($vars['book_guid'], $vars['user_guid']);

$status = $status_info['status'];

$content = "<div class='book-completed-container elgg-subtext' style='clear: both;'>";

if ($status == BOOK_READING_STATUS_COMPLETE) {
	if ($date = readinglist_get_complete_date($vars['book_guid'], $vars['user_guid'])) {
		$completed = date('F j, Y', $date);

		$completed = "<div class='complete-date'>" . elgg_echo('readinglist:label:completed', array($completed)) . "</div>";

		$content .= $completed;
	} else {
		$complete_label = elgg_echo('readinglist:label:completedate');
		$complete_input = elgg_view('input/date', array(
			'name' => 'complete_date',
			'value' => $complete_label,
			'class' => 'book-date-complete',
		));

		// Hidden input to identify book
		$book_input = elgg_view('input/hidden', array(
			'value' => $vars['book_guid'],
			'class' => 'book-guid-hidden',
		));

		$date_js = <<<JAVASCRIPT
			<script type='text/javascript'>
				elgg.readinglist.initDatePicker();
			</script>
JAVASCRIPT;

		$content .= '&nbsp;' . $complete_input . $book_input . $date_js;
	}
}

$content .= "</div>";

echo $content;