<?php

class QuestionModel extends CI_Model{

	public function getAllQuestions(){
		log_message('debug', 'getAllQuestions() called');
//		$this->logger->debug('getAllQuestions() called');

		$question = $this->db->get("Questions");
		if($question->num_rows() > 0){
			$question_array = $question->result();
			foreach ($question_array as $question) {
				$question_id = $question->questionid;
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');
			}
			return $question_array;
		}else{
			return false;
		}
	}

	public function getQuestion($question_id)
	{
		{
			$question = $this->db->get_where("Questions", array('questionid' => $question_id))->row();

			if ($question) {
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question->questionid)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');

				return $question;
			} else {
				return false;
			}
		}
//		$question = $this->db->get_where("Questions", array('questionid' => $question_id));
//		if ($question->num_rows() > 0) {
//			$question_array = $question->result();
//			foreach ($question_array as $question) {
//				$question_id = $question->questionid;
//				$tag_query = $this->db->select('tags')
//					->from('Tags')
//					->where('questionid', $question_id)
//					->get();
//				$tags = $tag_query->result();
//				$question->tags = array_column($tags, 'tags');
//			}
//			return $question_array;
//		} else {
//			return new stdClass();
//		}
	}

	// getAllCategories from Questions table with distinct values. only the array with category values will be returned
	public function getAllCategories(){
		$this->db->distinct();
		$this->db->select('category');
		$categories = $this->db->get("Questions");

		if($categories->num_rows() > 0){
			return $categories->result();
		}else{
			return false;
		}
	}

	public function getSearchQuestions($searchWord) {
		$this->db->distinct();
		$this->db->select('Questions.questionid, Questions.title, Questions.description, Questions.expectation');
		$this->db->like('Questions.title', $searchWord);
		$this->db->or_like('Questions.description', $searchWord);
		$this->db->or_like('Questions.expectation', $searchWord);
		$this->db->join('Tags', 'Questions.questionid = Tags.questionid', 'left');
		$this->db->or_like('Tags.tags', $searchWord);
		$this->db->group_by('Questions.questionid');

		$question = $this->db->get("Questions");

		if ($question->num_rows() > 0) {
			$question_array = $question->result();
			foreach ($question_array as $question) {
				$question_id = $question->questionid;
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');
			}
			return $question_array;
		} else {
			return false;
		}
	}

	// getCategoryQuestions

	public function getCategoryQuestions($category) {
		// $question = $this->db->get_where("Questions", array('questionid' => $question_id))->row();

		$questions = $this->db->get_where("Questions", array('category' => $category));

		if ($questions->num_rows() > 0) {
			$question_array = $questions->result();
			foreach ($question_array as $question) {
				$question_id = $question->questionid;
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');
			}
			return $question_array;
		} else {
			return false;
		}
	}

	public function addQuestion($userid, $title, $description, $expectation, $images, $category, $date, $tagArray, $imageurl) {
		$this->db->trans_start(); // Start transaction

//		$questionData = array(
//			'userid' => $userid,
//			'title' => $title,
//			'question' => $question,
//			'expectationQ' => $expectationQ,
//			'questionimage' => $imageurl, // Ensure that the questionimage field is correctly set here
//			'category' => $category,
//			'qaddeddate' => $qaddeddate,
//		);

		$questionData = array(
			'userid' => $userid,
			'title' => $title,
			'description' => $description,
			'expectation' => $expectation,
			'images' => $imageurl, // Ensure that the questionimage field is correctly set here
			'category' => $category,
			'date' => $date,
			'views' => 0
		);

		// Insert into 'Questions' table
		$insertDetails = $this->db->insert('Questions', $questionData);

		// Check if the insertion was successful
		if ($insertDetails) {
			// Get the last inserted question ID
			$questionId = $this->db->insert_id();

			// Insert into 'Tags' table
			foreach ($tagArray as $tag) {
				$tagData = array(
					'questionid' => $questionId, // Use the retrieved question ID
					'tags' => trim($tag)
				);
				$this->db->insert('Tags', $tagData);
			}
		}

		if ($insertDetails){
			$pastaskquestioncnt = $this->db->select('askquestioncnt')
				->from('Users')
				->where('user_id', $userid)
				->get()
				->row(); // Fetch the result as a single row

			$askquestioncnt = $pastaskquestioncnt->askquestioncnt + 1;

			$this->db->where('user_id', $userid)
				->update('Users', array('askquestioncnt' => $askquestioncnt));
		}

		$this->db->trans_complete(); // Complete transaction

		return $insertDetails && $this->db->trans_status(); // Return transaction status
	}

	public function upvote($questionid){

		$currentUpwotes = $this->db->select('upwotes')
			->from('Questions')
			->where('questionid', $questionid)
			->get()
			->row()
			->upwotes;

		$newUpwotes = $currentUpwotes+ 1;

		$updatedUpwotes = $this->db->where('questionid', $questionid)
			->update('Questions', array('upwotes' => $newUpwotes));

		return $updatedUpwotes;
	}

	public function downvote($questionid){
		$currentUpwotes = $this->db->select('upwotes')
			->from('Questions')
			->where('questionid', $questionid)
			->get()
			->row()
			->upwotes;

		$newUpwotes = $currentUpwotes - 1;

		$updatedUpwotes= $this->db->where('questionid', $questionid)
			->update('Questions', array('upwotes' => $newUpwotes));

		return $updatedUpwotes;

	}

	public function getBookmark($questionid, $userid){
		$bookmark = $this->db->get_where("BookmarkQue", array('questionid' => $questionid, 'userid' => $userid));
		if($bookmark->num_rows() > 0){

			return true;
		}else{
			return false;
		}
	}

	public function removeBookmark($questionid, $userid){
		$bookmark = $this->db->delete("BookmarkQue", array('questionid' => $questionid, 'userid' => $userid));
		return $bookmark;
	}

	public function addBookmark($questionid, $userid){
		// Check if the combination of questionid and userid already exists in the database
		$this->db->where('questionid', $questionid);
		$this->db->where('userid', $userid);
		$existingBookmark = $this->db->get('BookmarkQue')->row();

		// If the combination already exists, return false to indicate that the bookmark was not added
		if($existingBookmark) {
			return false;
		}

		// If the combination does not exist, add the new bookmark to the database
		$bookmarkData = array(
			'questionid' => $questionid,
			'userid' => $userid
		);
		$bookmark = $this->db->insert('BookmarkQue', $bookmarkData);

		// Return true to indicate that the bookmark was successfully added
		return $bookmark;
	}

//	public function addBookmark($questionid, $userid){
//		$bookmarkData = array(
//			'questionid' => $questionid,
//			'userid' => $userid
//		);
//		$bookmark = $this->db->insert('BookmarkQue', $bookmarkData);
//		return $bookmark;
//	}

	public function getBookmarkQuestions($userid){
		$this->db->select('Questions.*');
		$this->db->from('Questions');
		$this->db->join('BookmarkQue', 'Questions.questionid = BookmarkQue.questionid');
		$this->db->where('BookmarkQue.userid', $userid);
		$question = $this->db->get();
		if($question->num_rows() > 0){
			$question_array = $question->result();
			foreach ($question_array as $question) {
				$question_id = $question->questionid;
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');
			}
			return $question_array;
		}else{
			return false;
		}
	}
}
