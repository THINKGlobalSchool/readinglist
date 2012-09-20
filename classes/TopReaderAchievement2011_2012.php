<?php
/**
 * Top Reader 2011 - 2012 Achievement Class
 * 
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

class TopReaderAchievement2011_2012 extends AchievementBase {

	// Award names
	const TOP_READER_2011_2012 = 'TOP_READER_2011_2012';
	
	// Achievement end date
	const TOP_READER_2011_2012_CREATED_TIME_LOWER = 1314878400; // Thu, 01 Sep 2011 12:00:00 GMT
	const TOP_READER_2011_2012_CREATED_TIME_UPPER = 1346500800; // Sat, 01 Sep 2012 12:00:00 GMT

	/**
	 * Construct the Achievement
	 * 
	 * @param ElggUser $user
	 */
	public function __construct($user = NULL) {
		parent::__construct($user);
		
		$this->awards = array(
			self::TOP_READER_2011_2012
		);
		
		$this->type = "top";
		$this->event_type = "create";
		$this->object_type = "relationship";
		$this->object_subtype = READING_LIST_RELATIONSHIP_COMPLETE;
	}

	public function execute($data = NULL) {
		$this->attempt_award($this->is_top_reader_2011_2012());
	}

	protected function attempt_award($result) {
		if ($result) {
			// Not sure if this is the best place for this functionality..
			$users = achievements_get_users_with_achievement(TopReaderAchievement2011_2012::TOP_READER_2011_2012);

			$this->award(self::TOP_READER_2011_2012);

			$ia = elgg_get_ignore_access();
			elgg_set_ignore_access(TRUE);
			if ($users && count($users)) {
				foreach ($users as $user) {
					if ($user->guid == $this->user->guid) {
						continue;
					}
					remove_user_achievement($user, TopReaderAchievement2011_2012::TOP_READER_2011_2012);
				}
			}
			elgg_set_ignore_access($ia);

		}
	}

	/** 
	 * Determine if user is the top reader (most complete books)
	 */
	private function is_top_reader_2011_2012() {
		return achievements_user_has_most_annotations(array(
			'user' => $this->user, 
			'annotation_name' => 'book_complete_date',
			'annotation_value_where_sql' => "
				AND v.string > " . self::TOP_READER_2011_2012_CREATED_TIME_LOWER . "
				AND v.string < " . self::TOP_READER_2011_2012_CREATED_TIME_UPPER,
		));
	}

	/**
	 * Logic for populating a leaderboard for this achievement
	 */
	public function get_leaderboard() {
		return array(
			'count_column_title' => 'Books Completed',
			'count_column_field' => 'a_count',
			'annotation_name' => 'book_complete_date',
			'annotation_value_where_sql' => "
				AND v.string > " . self::TOP_READER_2011_2012_CREATED_TIME_LOWER . "
				AND v.string < " . self::TOP_READER_2011_2012_CREATED_TIME_UPPER,
			'achievement_name' => TOP_READER_2011_2012,
			'getter' => 'achievements_get_users_order_by_annotation_count',
		);
	}
}