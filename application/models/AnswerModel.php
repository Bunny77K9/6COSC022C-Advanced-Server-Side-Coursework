<?php

class AnswerModel extends CI_Model
{

	/**
	 * Retrieves answers for a specific question.
	 *
	 * @param int $questionid The ID of the question.
	 * @return mixed Returns an array of answers if found, otherwise returns null.
	 */
	public function getAnswers($questionid)
	{
		// Get answers for the specified question
		$answer = $this->db->get_where("Answers", array('questionid' => $questionid));

		// If answers are found, return them
		if ($answer->num_rows() > 0) {
			return $answer->result();
		} else {
			// Return null if no answers are found
			return null;
		}
	}


	/**
	 * Adds an answer to a specific question.
	 *
	 * @param int $questionid The ID of the question.
	 * @param int $userid The ID of the user answering the question.
	 * @param string $answer The answer provided by the user.
	 * @param string $answeraddreddate The date when the answer was added.
	 * @param string $imageurl The URL of any image attached to the answer.
	 * @return bool Returns true if the answer is successfully added, otherwise returns false.
	 */
	public function addAnswer($questionid, $userid, $answer, $answeraddreddate, $imageurl)
	{
		// Prepare data for insertion
		$answerData = array(
			'questionid' => $questionid,
			'userid' => $userid,
			'answer' => $answer,
			'answerimage' => $imageurl,
			'answereddate' => $answeraddreddate
		);

		// Insert the answer into the database
		$insertAns = $this->db->insert('Answers', $answerData);

		// Update user's answer question count if insertion is successful
		if ($insertAns) {
			// Retrieve user's past answer question count
			$currentAnswerCount = $this->db->select('answercount')
				->from('Users')
				->where('user_id', $userid)
				->get()
				->row();

			// Calculate new answer count
			$newAnswerCount = $currentAnswerCount->answercount + 1;

			// Update user's answer count in the database
			$this->db->where('user_id', $userid)
				->update('Users', array('answercount' => $newAnswerCount));
		}

		// Return the result of the insertion
		return $insertAns;
	}


	/**
	 * Increases the upvotes count for a specific answer.
	 *
	 * @param int $answerid The ID of the answer.
	 * @return bool Returns true if the upvotes count is successfully updated, otherwise returns false.
	 */
	public function upvote($answerid)
	{
		// Retrieve the current upvotes count for the answer
		$currentUpvotes = $this->db->select('upwotes')
			->from('Answers')
			->where('answerid', $answerid)
			->get()
			->row()
			->upwotes;

		// Calculate the new upvotes count
		$newUpvotes = $currentUpvotes + 1;

		// Update the upvotes count in the database
		$updatedUpvotes = $this->db->where('answerid', $answerid)
			->update('Answers', array('upwotes' => $newUpvotes));

		// Return the result of the update operation
		return $updatedUpvotes;
	}


	/**
	 * Decreases the upvotes count for a specific answer.
	 *
	 * @param int $answerid The ID of the answer.
	 * @return bool Returns true if the upvotes count is successfully updated, otherwise returns false.
	 */
	public function downvote($answerid)
	{
		// Retrieve the current upvotes count for the answer
		$currentUpvotes = $this->db->select('upwotes')
			->from('Answers')
			->where('answerid', $answerid)
			->get()
			->row()
			->upwotes;

		// Calculate the new upvotes count
		$newUpvotes = $currentUpvotes - 1;

		// Update the upvotes count in the database
		$updatedUpvotes = $this->db->where('answerid', $answerid)
			->update('Answers', array('upwotes' => $newUpvotes));

		// Return the result of the update operation
		return $updatedUpvotes;
	}
}
