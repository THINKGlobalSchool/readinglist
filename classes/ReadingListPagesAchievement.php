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

	// Points
	protected static $awards = array(
		BOOK_PAGES_500 => '10',
		BOOK_PAGES_2000 => '10',
		BOOK_PAGES_5000 => '15',
		BOOK_PAGES_15000 => '20',
		BOOK_PAGES_30000 => '30',
	);

	protected static $event_type = "create";
	protected static $object_type = 'relationship';
	protected static $object_subtype = READING_LIST_RELATIONSHIP_COMPLETE;

	public function action($data) {
		$count = $this->count_book_pages_read();
		$this->attempt_award($count);
	}

	public static function getAchievementNames() {
		return array_keys(self::$awards);
	}

	public static function getAchievementPoints($name) {
		return (int)self::$awards[$name];
	}

	public static function getElggEventType() {
		return self::$event_type;
	}

	public static function getElggObjectSubtype() {
		return self::$object_subtype;
	}

	public static function getElggObjectType() {
		return self::$object_type;
	}

	protected function attempt_award($count) {
		if ($count >= 30000) {
			$this->award(self::BOOK_PAGES_30000, self::getAchievementPoints(self::BOOK_PAGES_30000));
		} else if ($count >= 15000) {
			$this->award(self::BOOK_PAGES_15000, self::getAchievementPoints(self::BOOK_PAGES_15000));
		} else if ($count >= 5000) {
			$this->award(self::BOOK_PAGES_5000, self::getAchievementPoints(self::BOOK_PAGES_5000));
		} else if ($count >= 2000) {
			$this->award(self::BOOK_PAGES_2000, self::getAchievementPoints(self::BOOK_PAGES_2000));
		} else if ($count >= 500) {
			$this->award(self::BOOK_PAGES_500, self::getAchievementPoints(self::BOOK_PAGES_500));
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