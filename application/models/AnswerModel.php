<?php


class AnswerModel extends CI_Model
{

	public function getAnswers($questionid)
	{

		$answer = $this->db->get_where("Answers", array('questionid' => $questionid));

		if ($answer->num_rows() > 0) {
			return $answer->result();
		} else {
//			$answer = array(
//				'questionid' => $questionid,
//				'answer' => '',
//				'answerimage' => '',
//				'answeraddeddate' => ''
//			);
//			return $answer;
			return null;
		}
	}

	public function addAnswer($questionid, $userid, $answer, $answeraddreddate, $imageurl)
	{
		$answerData = array(
			'questionid' => $questionid,
			'userid' => $userid,
			'answer' => $answer,
			'answerimage' => $imageurl,
			'answeraddeddate' => $answeraddreddate
		);

		$insertAns = $this->db->insert('Answers', $answerData);

		if ($insertAns) {
			$pastanswerquestioncnt = $this->db->select('answerquestioncnt')
				->from('Users')
				->where('user_id', $userid)
				->get()
				->row(); // Fetch the result as a single row

			$answerquestioncnt = $pastanswerquestioncnt->answerquestioncnt + 1;

			$this->db->where('user_id', $userid)
				->update('Users', array('answerquestioncnt' => $answerquestioncnt));
		}
		return $insertAns;
	}

	public function upvote($answerid){

		$currentUpwotes = $this->db->select('upwotes')
			->from('Answers')
			->where('answerid', $answerid)
			->get()
			->row()
			->upwotes;

		$newUpwotes = $currentUpwotes+ 1;

		$updatedUpwotes = $this->db->where('answerid', $answerid)
			->update('Answers', array('upwotes' => $newUpwotes));

		return $updatedUpwotes;
	}

	public function downvote($answerid){
		$currentUpwotes = $this->db->select('upwotes')
			->from('Answers')
			->where('answerid', $answerid)
			->get()
			->row()
			->upwotes;

		$newUpwotes = $currentUpwotes - 1;

		$updatedUpwotes= $this->db->where('answerid', $answerid)
			->update('Answers', array('upwotes' => $newUpwotes));

		return $updatedUpwotes;

	}
}
