<?php

class QuestionModel extends CI_Model
{

	/**
	 * Retrieves all questions from the database.
	 */
	public function getAllQuestions()
	{
		// Retrieve questions from the database
		$question = $this->db->get("Questions");
		if ($question->num_rows() > 0) {
			// If questions exist
			$questionsArray = $question->result();
			foreach ($questionsArray as $question) {
				$question_id = $question->questionid;
				// Retrieve tags for each question
				$tagsQuery = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tagsQuery->result();
				// Assign tags to the question object
				$question->tags = array_column($tags, 'tags');
			}
			return $questionsArray;
		} else {
			// Return false if no questions exist
			return false;
		}
	}


	/**
	 * Retrieves a specific question from the database based on the question ID.
	 *
	 * @param int $question_id The ID of the question to retrieve.
	 * @return mixed Returns the question data if found, otherwise returns false.
	 */
	public function getQuestion($question_id)
	{
		// Retrieve the question from the database based on the question ID
		$question = $this->db->get_where("Questions", array('questionid' => $question_id))->row();

		if ($question) {
			// If the question exists
			// Retrieve tags for the question
			$tagsQuery = $this->db->select('tags')
				->from('Tags')
				->where('questionid', $question->questionid)
				->get();
			$tags = $tagsQuery->result();
			// Assign tags to the question object
			$question->tags = array_column($tags, 'tags');

			return $question;
		} else {
			// Return false if the question does not exist
			return false;
		}
	}


	/**
	 * Retrieves all distinct categories from the questions in the database.
	 *
	 * @return mixed Returns an array of categories if found, otherwise returns false.
	 */
	public function getAllCategories()
	{
		// Select distinct categories from the Questions table
		$this->db->distinct();
		$this->db->select('category');
		$categories = $this->db->get("Questions");

		if ($categories->num_rows() > 0) {
			// If categories exist, return them
			return $categories->result();
		} else {
			// Return false if no categories exist
			return false;
		}
	}


	/**
	 * Retrieves all distinct tags from the Tags table in the database.
	 *
	 * @return mixed Returns an array of tags if found, otherwise returns false.
	 */
	public function getAllTags()
	{
		// Select distinct tags from the Tags table
		$this->db->distinct();
		$this->db->select('tags');
		$tags = $this->db->get("Tags");

		if ($tags->num_rows() > 0) {
			// If tags exist, return them
			return $tags->result();
		} else {
			// Return false if no tags exist
			return false;
		}
	}


	/**
	 * Retrieves questions based on the provided search value.
	 *
	 * @param string $searchValue The value to search for in question titles, descriptions, expectations, and tags.
	 * @return mixed Returns an array of questions matching the search criteria if found, otherwise returns false.
	 */
	public function getSearchQuestions($searchValue)
	{
		// Select distinct question fields from the Questions table
		$this->db->distinct();
		$this->db->select('Questions.questionid, Questions.title, Questions.description, Questions.expectation');

		// Search for the provided value in question titles, descriptions, and expectations
		$this->db->like('Questions.title', $searchValue);
		$this->db->or_like('Questions.description', $searchValue);
		$this->db->or_like('Questions.expectation', $searchValue);

		// Left join with the Tags table and search for the provided value in tags
		$this->db->join('Tags', 'Questions.questionid = Tags.questionid', 'left');
		$this->db->or_like('Tags.tags', $searchValue);

		// Group results by question ID to avoid duplicate questions
		$this->db->group_by('Questions.questionid');

		// Execute the query
		$question = $this->db->get("Questions");

		if ($question->num_rows() > 0) {
			// If questions are found
			$questionsArray = $question->result();
			foreach ($questionsArray as $question) {
				$question_id = $question->questionid;
				// Retrieve tags for each question
				$tagsQuery = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tagsQuery->result();
				// Assign tags to the question object
				$question->tags = array_column($tags, 'tags');
			}
			return $questionsArray;
		} else {
			// Return false if no questions match the search criteria
			return false;
		}
	}


	/**
	 * Retrieves questions belonging to a specific category.
	 *
	 * @param string $category The category to retrieve questions for.
	 * @return mixed Returns an array of questions belonging to the specified category if found, otherwise returns false.
	 */
	public function getCategoryQuestions($category)
	{
		// Retrieve questions from the Questions table where category matches the provided category
		$questions = $this->db->get_where("Questions", array('category' => $category));

		if ($questions->num_rows() > 0) {
			// If questions are found
			$questionsArray = $questions->result();
			foreach ($questionsArray as $question) {
				$question_id = $question->questionid;
				// Retrieve tags for each question
				$tagsQuery = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tagsQuery->result();
				// Assign tags to the question object
				$question->tags = array_column($tags, 'tags');
			}
			return $questionsArray;
		} else {
			// Return false if no questions belong to the specified category
			return false;
		}
	}


	/**
	 * Retrieves questions associated with a specific tag.
	 *
	 * @param string $tag The tag to retrieve questions for.
	 * @return mixed Returns an array of questions associated with the specified tag if found, otherwise returns false.
	 */
	public function getTagsQuestions($tag)
	{
		// Select question IDs from the Tags table where the tag matches the provided tag
		$this->db->select('questionid');
		$this->db->from('Tags');
		$this->db->where('tags', $tag);
		$tagsQuery = $this->db->get();

		if ($tagsQuery->num_rows() > 0) {
			// If question IDs are found
			$question_ids = array_column($tagsQuery->result(), 'questionid');

			// Retrieve questions from the Questions table where the question ID is in the retrieved question IDs
			$this->db->where_in('questionid', $question_ids);
			$questions = $this->db->get('Questions');

			if ($questions->num_rows() > 0) {
				// If questions are found
				$questionsArray = $questions->result();
				foreach ($questionsArray as $question) {
					$question_id = $question->questionid;
					// Retrieve tags for each question
					$tagsQuery = $this->db->select('tags')
						->from('Tags')
						->where('questionid', $question_id)
						->get();
					$tags = $tagsQuery->result();
					// Assign tags to the question object
					$question->tags = array_column($tags, 'tags');
				}
				return $questionsArray;
			} else {
				// Return false if no questions are associated with the tag
				return false;
			}
		} else {
			// Return false if no question IDs are found for the tag
			return false;
		}
	}


	/**
	 * Adds a new question to the database.
	 *
	 * @param int $userid The ID of the user who is adding the question.
	 * @param string $title The title of the question.
	 * @param string $description The description of the question.
	 * @param string $expectation The expectation of the question.
	 * @param string $images The images associated with the question.
	 * @param string $category The category of the question.
	 * @param string $date The date when the question was added.
	 * @param array $tagArray An array containing tags associated with the question.
	 * @param string $imageurl The URL of the image associated with the question.
	 * @return bool Returns true if the question is successfully added, otherwise returns false.
	 */
	public function addQuestion($userid, $title, $description, $expectation, $images, $category, $date, $tagArray, $imageurl)
	{
		// Start transaction
		$this->db->trans_start();

		// Prepare question data
		$questionData = array(
			'userid' => $userid,
			'title' => $title,
			'description' => $description,
			'expectation' => $expectation,
			'images' => $imageurl,
			'category' => $category,
			'date' => $date,
			'views' => 0
		);

		// Insert question details into the Questions table
		$insertDetails = $this->db->insert('Questions', $questionData);

		// If question details are inserted successfully
		if ($insertDetails) {
			$questionId = $this->db->insert_id();

			// Insert tags associated with the question into the Tags table
			foreach ($tagArray as $tag) {
				$tagData = array(
					'questionid' => $questionId,
					'tags' => trim($tag)
				);
				$this->db->insert('Tags', $tagData);
			}
		}

		// If question details are inserted successfully, update question count for the user
		if ($insertDetails) {
			$currentQuestionCount = $this->db->select('questioncount')
				->from('Users')
				->where('user_id', $userid)
				->get()
				->row();

			$newQuestionCount = $currentQuestionCount->questioncount + 1;

			$this->db->where('user_id', $userid)
				->update('Users', array('questioncount' => $newQuestionCount));
		}

		// Complete transaction
		$this->db->trans_complete();

		// Return true if transaction is successful, otherwise false
		return $insertDetails && $this->db->trans_status();
	}


	/**
	 * Increases the upvote count for a question.
	 *
	 * @param int $questionid The ID of the question to upvote.
	 * @return bool Returns true if the upvote count is successfully updated, otherwise returns false.
	 */
	public function upvote($questionid)
	{
		// Retrieve current upvotes count for the question
		$currentUpvotes = $this->db->select('upvotes')
			->from('Questions')
			->where('questionid', $questionid)
			->get()
			->row()
			->upvotes;

		// Calculate new upvotes count
		$newUpvotes = $currentUpvotes + 1;

		// Update the upvotes count for the question
		$updatedUpvotes = $this->db->where('questionid', $questionid)
			->update('Questions', array('upvotes' => $newUpvotes));

		// Return true if the upvotes count is successfully updated, otherwise false
		return $updatedUpvotes;
	}


	/**
	 * Decreases the upvote count for a question.
	 *
	 * @param int $questionid The ID of the question to downvote.
	 * @return bool Returns true if the upvote count is successfully updated, otherwise returns false.
	 */
	public function downvote($questionid)
	{
		// Retrieve current upvotes count for the question
		$currentUpvotes = $this->db->select('upvotes')
			->from('Questions')
			->where('questionid', $questionid)
			->get()
			->row()
			->upvotes;

		// Calculate new upvotes count
		$newUpvotes = $currentUpvotes - 1;

		// Update the upvotes count for the question
		$updatedUpvotes = $this->db->where('questionid', $questionid)
			->update('Questions', array('upvotes' => $newUpvotes));

		// Return true if the upvotes count is successfully updated, otherwise false
		return $updatedUpvotes;
	}


	/**
	 * Checks if a question is bookmarked by a user.
	 *
	 * @param int $questionid The ID of the question.
	 * @param int $userid The ID of the user.
	 * @return bool Returns true if the question is bookmarked by the user, otherwise returns false.
	 */
	public function getBookmark($questionid, $userid)
	{
		// Check if there is a bookmark entry for the given question and user
		$bookmark = $this->db->get_where("Bookmarks", array('questionid' => $questionid, 'userid' => $userid));

		// Return true if a bookmark entry exists, otherwise false
		return ($bookmark->num_rows() > 0);
	}


	/**
	 * Removes a bookmark for a question by a user.
	 *
	 * @param int $questionid The ID of the question.
	 * @param int $userid The ID of the user.
	 * @return bool Returns true if the bookmark is successfully removed, otherwise returns false.
	 */
	public function removeBookmark($questionid, $userid)
	{
		// Delete the bookmark entry for the given question and user
		$bookmark = $this->db->delete("Bookmarks", array('questionid' => $questionid, 'userid' => $userid));

		// Return true if the bookmark is successfully removed, otherwise false
		return $bookmark;
	}


	/**
	 * Adds a bookmark for a question by a user.
	 *
	 * @param int $questionid The ID of the question.
	 * @param int $userid The ID of the user.
	 * @return bool Returns true if the bookmark is successfully added, otherwise returns false.
	 */
	public function addBookmark($questionid, $userid)
	{
		// Check if the bookmark already exists
		$this->db->where('questionid', $questionid);
		$this->db->where('userid', $userid);

		// Retrieve the bookmark
		$existingBookmark = $this->db->get('Bookmarks')->row();

		// If the bookmark already exists, return false
		if ($existingBookmark) {
			return false;
		}

		// Create bookmark data
		$bookmarkInfo = array(
			'questionid' => $questionid,
			'userid' => $userid
		);

		// Insert the bookmark into the database
		$bookmark = $this->db->insert('Bookmarks', $bookmarkInfo);

		// Return true if the bookmark is successfully added, otherwise false
		return $bookmark;
	}


	/**
	 * Retrieves bookmarked questions for a specific user.
	 *
	 * @param int $userid The ID of the user.
	 * @return mixed Returns an array of bookmarked questions if found, otherwise returns false.
	 */
	public function getBookmarkQuestions($userid)
	{
		// Select questions associated with bookmarks for the given user
		$this->db->select('Questions.*');
		$this->db->from('Questions');
		$this->db->join('Bookmarks', 'Questions.questionid = Bookmarks.questionid');
		$this->db->where('Bookmarks.userid', $userid);

		// Execute the query
		$questions = $this->db->get();

		// If bookmarked questions are found, process and return them
		if ($questions->num_rows() > 0) {
			$questionsArray = $questions->result();
			foreach ($questionsArray as $questions) {
				$question_id = $questions->questionid;
				// Retrieve tags associated with each question
				$tagsQuery = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tagsQuery->result();
				// Add tags to the question object
				$questions->tags = array_column($tags, 'tags');
			}
			// Return the array of bookmarked questions
			return $questionsArray;
		} else {
			// Return false if no bookmarked questions are found
			return false;
		}
	}
}
