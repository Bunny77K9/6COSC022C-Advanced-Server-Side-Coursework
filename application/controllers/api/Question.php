<?php

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

class Question extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('QuestionModel');
	}

	// Add new question API

	public function add_new_question_post()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);

		$userid = strip_tags($this->post('user_id'));
		$title = strip_tags($this->post('title'));
		$description = $this->post('description');
		$expectation = $this->post('expectation');
		$tags = strip_tags($this->post('tags'));
		$category = strip_tags($this->post('category'));
		$date = strip_tags($this->post('date'));
		$imageurl = strip_tags($this->post('images'));

		$images = '';

		if (!empty($_FILES['image']['name'])) {

			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/question/';
			$uploadFile = $uploadDir . basename($_FILES['image']['name']);

			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
				$images = $uploadFile;
			}
		}

		if (!empty($userid) && !empty($title) && !empty($description) && !empty($expectation) && !empty($tags) && !empty($category) && !empty($date)) {

			$questionTagsArray = explode(',', $tags);

			$result = $this->QuestionModel->addQuestion($userid, $title, $description, $expectation, $images, $category, $date, $questionTagsArray, $imageurl);

			if ($result) {
				$this->response(array(
					'status' => TRUE,
					'message' => 'Question created successfully!'
				), REST_Controller::HTTP_OK);
			} else {
				$this->response("Failed to created question!", REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}

	public function new_question_image_post()
	{
		if (!empty($_FILES['image']['name'])) {
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/question/';

			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10; // 10 MB

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('image')) {
				$uploadData = $this->upload->data();
				$imagePath = '../../assets/images/question/' . $uploadData['file_name'];

				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} else {
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}

	// Display all questions API

	public function display_all_questions_get($question_id = FALSE)
	{
		if ($question_id === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			$questions = $this->QuestionModel->getQuestion($question_id);
		}

		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	// Display search questions API

	public function display_search_questions_get($searchValue = FALSE)
	{
		if ($searchValue === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			$questions = $this->QuestionModel->getSearchQuestions($searchValue);
		}

		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	// Display questions by category API

	public function display_category_questions_get($category = FALSE)
	{
		if ($category === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			$questions = $this->QuestionModel->getCategoryQuestions($category);
		}

		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	// Display questions by tags API

	public function display_tag_questions_get($tag = FALSE)
	{
		if ($tag === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			$questions = $this->QuestionModel->getTagsQuestions($tag);
		}

		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	// Display all categories API

	public function display_all_categories_get()
	{
		$categories = $this->QuestionModel->getAllCategories();
		if ($categories) {
			$this->response($categories, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No categories found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	// Display all tags API

	public function display_all_tags_get()
	{
		$categories = $this->QuestionModel->getAllTags();
		if ($categories) {
			$this->response($categories, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No categories found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	// Upvote and Downvote questions API

	public function upvote_get($questionid)
	{
		$upvote = $this->QuestionModel->upvote($questionid);
		if ($upvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question upvoted successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to upvote question!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function downvote_get($questionid)
	{
		$upvote = $this->QuestionModel->downvote($questionid);
		if ($upvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question downvoted successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to downvote question!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// Bookmark questions API

	public function display_all_bookmarked_questions_get($userid)
	{
		$questions = $this->QuestionModel->getBookmarkQuestions($userid);

		if ($questions) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No bookmarked questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}

	public function display_question_bookmark_post()
	{
		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->getBookmark($questionid, $userid);
		if ($bookmark) {
			$this->response(array(
				'is_bookmark' => TRUE,
				'status' => TRUE,
				'message' => 'Bookmark added successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'is_bookmark' => FALSE,
				'status' => TRUE,
				'message' => 'Bookmark removed successfully!'
			), REST_Controller::HTTP_OK);
		}
	}

	public function add_bookmark_post()
	{
		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->addBookmark($questionid, $userid);
		if ($bookmark) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Bookmark added successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to add bookmark!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function remove_bookmark_post()
	{
		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->removeBookmark($questionid, $userid);
		if ($bookmark) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Bookmark removed successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response("Failed to remove bookmark!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
