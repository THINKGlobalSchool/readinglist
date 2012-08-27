<?php
/**
 * Reading List Page Count Achievement
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

class ReadinglistPagesAchievement extends AchievementBase {

	// Award names
	const BOOK_PAGES_500 = 'BOOK_PAGES_500';
	const BOOK_PAGES_2000 = 'BOOK_PAGES_2000';
	const BOOK_PAGES_5000 = 'BOOK_PAGES_5000';
	const BOOK_PAGES_15000 = 'BOOK_PAGES_15000';
	const BOOK_PAGES_30000 = 'BOOK_PAGES_30000';
	
	/**
	 * Construct the Achievement
	 * 
	 * @param ElggUser $user
	 */
	public function __construct($user = NULL) {
		parent::__construct($user);
		
		$this->awards = array(
			self::BOOK_PAGES_500,
			self::BOOK_PAGES_2000,
			self::BOOK_PAGES_5000,
			self::BOOK_PAGES_15000,
			self::BOOK_PAGES_30000
		);
		
		$this->event_type = "create";
		$this->object_type = "relationship";
		$this->object_subtype = READING_LIST_RELATIONSHIP_COMPLETE;
	}

	public function execute($data = NULL) {
		$count = $this->count_book_pages_read();
		$this->attempt_award($count);
	}

	protected function attempt_award($count) {
		$counts = array(
			self::BOOK_PAGES_30000 => 30000, 
			self::BOOK_PAGES_15000 => 15000, 
			self::BOOK_PAGES_5000 => 5000,
			self::BOOK_PAGES_2000 => 2000,
			self::BOOK_PAGES_500 => 500,
		);

		foreach ($counts as $name => $c) {
			if ($count >= $c) {
				$result = $this->award($name);
				$this->shouldNotify = !$result; // Only notify on first occurance
			}
		}
	}

	/** Count pages read **/
	private function count_book_pages_read() {
		// Options for grabbing a users completed books
		$options = array(
			'type' => 'object',
			'subtype' => 'book',
			'full_view' => false,
			'relationship_guid' => $this->user->guid,
			'inverse_relationship' => TRUE,
			'relationship' => READING_LIST_RELATIONSHIP_COMPLETE,
		);

		// Batch 'em
		$books = new ElggBatch('elgg_get_entities_from_relationship', $options);

		$count = 0;

		// Count pages and total them
		foreach($books as $book) {
			if ($book->pageCount) {
				$count += (int)$book->pageCount;
			}
		}

		return $count;
	}
}