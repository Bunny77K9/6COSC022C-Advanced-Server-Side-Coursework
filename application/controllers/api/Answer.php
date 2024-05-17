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


	/**
	 * Retrieves answers for a specific question.
	 *
	 * @param int $questionid The ID of the question.
	 * @return void Returns a JSON response containing the answers for the question.
	 */
	public function display_question_answers_get($questionid)
	{
		// Retrieve answers for the specified question ID
		$answers = $this->AnswerModel->getAnswers($questionid);

		// Send response based on whether answers are found or not
		if (!empty($answers)) {
			// If answers are found, send them in the response
			$this->response($answers, REST_Controller::HTTP_OK);
		} else {
			// If no answers are found, send an empty response with OK status
			$this->response(array(), REST_Controller::HTTP_OK);
		}
	}


	/**
	 * Stores an image uploaded for an answer.
	 *
	 * @return void Sends a JSON response containing the uploaded image path or an error message.
	 */
	public function store_answer_image_post()
	{
		// Check if image file is uploaded and has size greater than 0
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
			// Set upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/answer/';

			// Log upload directory for debugging
			log_message('debug', 'uploadDir: ' . $uploadDir);

			// Set upload configuration
			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10; // 10 MB

			// Load upload library with configuration
			$this->load->library('upload', $config);

			// Perform the upload
			if ($this->upload->do_upload('image')) {
				// If upload is successful, get upload data
				$uploadData = $this->upload->data();
				$imagePath = '../../assets/images/answer/' . $uploadData['file_name'];

				// Send the image path in the response
				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} else {
				// If upload fails, send error message in the response
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			// If no image is uploaded, send an empty image path in the response
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}


	/**
	 * Adds a new answer to a question.
	 *
	 * @return void Sends a JSON response indicating success or failure.
	 */
	public function add_new_question_answer_post()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);

		// Extract input data from POST request
		$questionid = strip_tags($this->post('questionid'));
		$userid = strip_tags($this->post('userid'));
		$answer = $this->post('answer');
		$imageurl = strip_tags($this->post('answerimage'));
		$answeraddreddate = strip_tags($this->post('answereddate'));

		$answerimage = '';

		// Check if an image is uploaded
		if (!empty($_FILES['image']['name'])) {
			// Set upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/answer/';
			$uploadFile = $uploadDir . basename($_FILES['image']['name']);

			// Move the uploaded file to the upload directory
			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
				$answerimage = $uploadFile;
			}
		}

		// Check if all required fields are provided
		if (!empty($questionid) && !empty($userid) && !empty($answer) && !empty($answeraddreddate)) {
			// Add the answer to the database
			$result = $this->AnswerModel->addAnswer($questionid, $userid, $answer, $answeraddreddate, $imageurl);

			// Check if the answer was added successfully
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


	/**
	 * Upvotes an answer.
	 *
	 * @param int $answerid The ID of the answer to upvote.
	 * @return void Sends a JSON response indicating success or failure.
	 */
	public function upvote_get($answerid)
	{
		// Upvote the answer
		$upvote = $this->AnswerModel->upvote($answerid);

		// Check if the upvote was successful
		if ($upvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Answer upvoted successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to upvote answer!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Downvotes an answer.
	 *
	 * @param int $answerid The ID of the answer to downvote.
	 * @return void Sends a JSON response indicating success or failure.
	 */
	public function downvote_get($answerid)
	{
		// Downvote the answer
		$downvote = $this->AnswerModel->downvote($answerid);

		// Check if the downvote was successful
		if ($downvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Answer downvoted successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to downvote answer!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
