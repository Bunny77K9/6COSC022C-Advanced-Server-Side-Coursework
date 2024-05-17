<?php

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

class Answer extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('AnswerModel');
	}

	public function display_question_answers_get($questionid)
	{
		$answers = $this->AnswerModel->getAnswers($questionid);

		if (!empty($answers)) {
			$this->response($answers, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(), REST_Controller::HTTP_OK);
		}
	}

	public function store_answer_image_post()
	{
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {

			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/answer/';

			log_message('debug', 'uploadDir: ' . $uploadDir);

			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('image')) {

				$uploadData = $this->upload->data();
				$imagePath = '../../assets/images/answer/' . $uploadData['file_name'];

				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} else {
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}

	public function add_new_question_answer_post()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);

		$questionid = strip_tags($this->post('questionid'));
		$userid = strip_tags($this->post('userid'));

		$answer = $this->post('answer');

		$imageurl = strip_tags($this->post('answerimage'));
		$answeraddreddate = strip_tags($this->post('answeraddeddate'));

		$answerimage = '';

		if (!empty($_FILES['image']['name'])) {
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/answer/';
			$uploadFile = $uploadDir . basename($_FILES['image']['name']);

			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
				$answerimage = $uploadFile;
			}
		}

		if (!empty($questionid) && !empty($userid) && !empty($answer) && !empty($answeraddreddate)) {

			$result = $this->AnswerModel->addAnswer($questionid, $userid, $answer, $answeraddreddate, $imageurl);

			if ($result) {
				$this->response(array(
					'status' => TRUE,
					'message' => 'Answer added successfully!'
				), REST_Controller::HTTP_OK);
			} else {
				$this->response("Failed to add answer!", REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}

	public function upvote_get($answerid)
	{
		$upvote = $this->AnswerModel->upvote($answerid);
		if ($upvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Answer upvoted successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to upvote question!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function downvote_get($answerid)
	{
		$upvote = $this->AnswerModel->downvote($answerid);
		if ($upvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Answer downvoted successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to downvote question!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
