<?php

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

class Question extends REST_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('QuestionModel');
	}

	public function display_all_questions_get($question_id = FALSE){
		log_message('debug', 'Question::display_all_questions_get() - $question_id: ' . $question_id);

		if ($question_id === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			$questions = $this->QuestionModel->getQuestion($question_id);
		}

		// Check if the user data exists
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found.'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

//	public function display_all_questions_get($question_id = FALSE){
//		// Set session timeout to 60 seconds if session is not active
//		if (!session_id()) {
//			ini_set('session.cookie_lifetime', 60);
//		}
//
//		// Start session
////		session_start();
//
//		// Check if session is active and not expired
//		if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
//			// Session has expired, destroy session and redirect to login page
//			session_unset();
//			session_destroy();
//			redirect('http://localhost/DevForum/index.php/usershow/'); // Assuming 'login' is the route to your login page
//		} else {
//			// Session is active or has not expired, update last activity time
//			$_SESSION['last_activity'] = time();
//
//			log_message('debug', 'Question::display_all_questions_get() - $question_id: ' . $question_id);
//
//			if ($question_id === FALSE) {
//				$questions = $this->QuestionModel->getAllQuestions();
//			} else {
//				$questions = $this->QuestionModel->getQuestion($question_id);
//			}
//
//			// Check if the user data exists
//			if (!empty($questions)) {
//				$this->response($questions, REST_Controller::HTTP_OK);
//			} else {
//				$this->response(array(
//					'status' => FALSE,
//					'message' => 'No questions found.'
//				), REST_Controller::HTTP_NOT_FOUND);
//			}
//		}
//	}

	public function bookmarkQuestions_get($userid){
		$questions = $this->QuestionModel->getBookmarkQuestions($userid);
		if($questions) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No bookmarked questions found.'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	public function search_questions_get($searchValue = FALSE){
		log_message('debug', 'Question::search_questions_get() - $searchValue: ' . $searchValue);

		if ($searchValue === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			$questions = $this->QuestionModel->getSearchQuestions($searchValue);
		}

		// Check if the user data exists
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	public function displayCategoryQuestions_get($category = FALSE){
		log_message('debug', 'Question::displayCategoryQuestions_get() - $category: ' . $category);

		if ($category === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			$questions = $this->QuestionModel->getCategoryQuestions($category);
		}

		// Check if the user data exists
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	// display_all_categories

	public function display_all_categories_get(){
		$categories = $this->QuestionModel->getAllCategories();
		if($categories) {
			$this->response($categories, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No categories found.'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	public function upvote_get($questionid){
		$upvote = $this->QuestionModel->upvote($questionid);
		if($upvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question upvoted successfully.'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to upvote question.", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function downvote_get($questionid){
		$upvote = $this->QuestionModel->downvote($questionid);
		if($upvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question downvoted successfully.'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to downvote question.", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function addquestion_post() {
		$_POST = json_decode(file_get_contents("php://input"), true);
		$this->form_validation->set_rules('title', 'checkTitle', 'required');
		$this->form_validation->set_rules('question', 'checkQuestion', 'required');
		$this->form_validation->set_rules('expectationQ', 'checkExpectationQ', 'required');
		$this->form_validation->set_rules('tags', 'checkTags', 'required');
		$this->form_validation->set_rules('category', 'checkCategory', 'required');
		$this->form_validation->set_rules('difficulty', 'checkDifficulty', 'required');

		$userid = strip_tags($this->post('user_id'));
		$title = strip_tags($this->post('title'));
		$description = $this->post('description');
		$expectation = $this->post('expectation');
		$tags = strip_tags($this->post('tags'));
		$category = strip_tags($this->post('category'));
		$date = strip_tags($this->post('date'));
		$imageurl = strip_tags($this->post('images'));

		// Initialize questionimage variable
		$images = '';

		// Check if an image file is uploaded
		if (!empty($_FILES['image']['name'])) {
			// Define upload directory and file name
//			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/';
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/question/';
			$uploadFile = $uploadDir . basename($_FILES['image']['name']);

			// Attempt to move uploaded file to specified directory
			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
				// File uploaded successfully, update image path
				$images = $uploadFile;
			}
		}

		if (!empty($userid) && !empty($title) && !empty($description) && !empty($expectation) && !empty($tags) && !empty($category) && !empty($date)) {
			$tagArray = explode(',', $tags);

			// Pass the updated $questionimage variable to the addQuestion function
//			$result = $this->QuestionModel->addQuestion($userid, $title, $question, $expectationQ, $category, $qaddeddate, $tagArray, $imageurl);
			$result = $this->QuestionModel->addQuestion($userid, $title, $description, $expectation, $images, $category, $date, $tagArray, $imageurl);
			if ($result) {
				$this->response(array(
					'status' => TRUE,
					'message' => 'Question added successfully.'
				), REST_Controller::HTTP_OK);
			} else {
				$this->response("Failed to add question.", REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}

	public function getBookmark_post(){

		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->getBookmark($questionid, $userid);
		if($bookmark) {
			$this->response(array(
				'is_bookmark' => TRUE,
				'status' => TRUE,
				'message' => 'Question bookmarked successfully.'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'is_bookmark' => FALSE,
				'status' => TRUE,
				'message' => 'Question bookmarked successfully.'
			), REST_Controller::HTTP_OK);
		}
	}

	public function remove_bookmark_post(){

		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->removeBookmark($questionid, $userid);
		if($bookmark) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question removed from bookmark successfully.'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to remove question from bookmark.", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function add_bookmark_post(){

		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->addBookmark($questionid, $userid);
		if($bookmark) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question added to the bookmark successfully.'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to add question to the bookmark.", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

//	public function addquestion_post(){
//		$_POST = json_decode(file_get_contents("php://input"), true);
//
//		$config['upload_path'] = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/'; // Specify the upload directory
//		$config['allowed_types'] = 'gif|jpg|jpeg|png'; // Specify allowed file types
//		$config['max_size'] = 10000; // Specify max file size in KBs
//
//		$this->load->library('upload', $config);
//
//		$this->form_validation->set_rules('title', 'checkTitle', 'required');
//		$this->form_validation->set_rules('question', 'checkQuestion', 'required');
//		$this->form_validation->set_rules('expectationQ', 'checkExpectationQ', 'required');
//		$this->form_validation->set_rules('tags', 'checkTags', 'required');
//		$this->form_validation->set_rules('category', 'checkCategory', 'required');
//		$this->form_validation->set_rules('difficulty', 'checkDifficulty', 'required');
//
//
//		if (!$this->upload->do_upload('image')) {
//			$error = array('error' => $this->upload->display_errors());
//			$this->response($error, REST_Controller::HTTP_BAD_REQUEST);
//			return;
//		}
//
//		$data = array('upload_data' => $this->upload->data());
//		$questionimage = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/' . $data['upload_data']['file_name']; // Path to be saved in the database
//
//		$userid = strip_tags($this->post('user_id'));
//		$title = strip_tags($this->post('title'));
//		$question = strip_tags($this->post('question'));
//		$expectationQ = strip_tags($this->post('expectationQ'));
//		$tags = strip_tags($this->post('tags'));
//		$category = strip_tags($this->post('category'));
//		$qaddeddate = strip_tags($this->post('qaddeddate'));
//
//		if(!empty($userid) && !empty($title) && !empty($question) && !empty($expectationQ) && !empty($tags) && !empty($category) && !empty($qaddeddate)) {
//			$tagArray = explode(',', $tags);
//
//			$result = $this->QuestionModel->addQuestion($userid, $title, $question, $expectationQ, $questionimage, $category, $qaddeddate, $tagArray);
//			if ($result) {
//				$this->response(array(
//					'status' => TRUE,
//					'message' => 'Question added successfully.'
//				), REST_Controller::HTTP_OK);
//			} else {
//				$this->response("Failed to add question.", REST_Controller::HTTP_BAD_REQUEST);
//			}
//		}
//	}
}
