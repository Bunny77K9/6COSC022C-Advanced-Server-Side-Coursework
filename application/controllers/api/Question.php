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


	/**
	 * Adds a new question to the database.
	 *
	 * This function processes the input data, including file upload if an image is provided,
	 * and saves the new question along with its tags to the database.
	 *
	 * @return void Returns a JSON response indicating success or failure.
	 */
	public function add_new_question_post()
	{
		// Decode the JSON payload
		$_POST = json_decode(file_get_contents("php://input"), true);

		// Retrieve and sanitize input data
		$userid = strip_tags($this->post('user_id'));
		$title = strip_tags($this->post('title'));
		$description = $this->post('description');
		$expectation = $this->post('expectation');
		$tags = strip_tags($this->post('tags'));
		$category = strip_tags($this->post('category'));
		$date = strip_tags($this->post('date'));
		$imageurl = strip_tags($this->post('images'));

		$images = '';

		// Check if an image file is provided and process the upload
		if (!empty($_FILES['image']['name'])) {
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/question/';
			$uploadFile = $uploadDir . basename($_FILES['image']['name']);

			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
				$images = $uploadFile;
			}
		}

		// Validate required fields
		if (!empty($userid) && !empty($title) && !empty($description) && !empty($expectation) && !empty($tags) && !empty($category) && !empty($date)) {
			// Split the tags into an array
			$questionTagsArray = explode(',', $tags);

			// Attempt to add the question to the database
			$result = $this->QuestionModel->addQuestion($userid, $title, $description, $expectation, $images, $category, $date, $questionTagsArray, $imageurl);

			if ($result) {
				// Respond with success message
				$this->response(array(
					'status' => TRUE,
					'message' => 'Question created successfully!'
				), REST_Controller::HTTP_OK);
			} else {
				// Respond with failure message
				$this->response("Failed to create question!", REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}


	/**
	 * Handles the upload of an image for a new question.
	 *
	 * If an image file is provided, it uploads the image to the server and returns the path to the uploaded image.
	 * If no image file is provided, it returns an empty image path.
	 *
	 * @return void Returns a JSON response containing the image path or an error message.
	 */
	public function new_question_image_post()
	{
		// Check if an image file is uploaded
		if (!empty($_FILES['image']['name'])) {
			// Define the upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/question/';

			// Set up upload configuration
			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10; // 10 MB

			// Load the upload library with the configuration
			$this->load->library('upload', $config);

			// Attempt to upload the file
			if ($this->upload->do_upload('image')) {
				$uploadData = $this->upload->data();
				$imagePath = '../../assets/images/question/' . $uploadData['file_name'];

				// Send the image path in the response
				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} else {
				// If upload fails, send the error message in the response
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			// If no file is uploaded, send an empty image path in the response
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}


	/**
	 * Retrieves all questions or a specific question by its ID.
	 *
	 * If no question ID is provided, retrieves all questions from the database.
	 * If a question ID is provided, retrieves the question with that ID.
	 *
	 * @param int|false $question_id The ID of the question to retrieve, or FALSE to retrieve all questions.
	 * @return void Returns a JSON response containing the retrieved questions or a message if no questions are found.
	 */
	public function display_all_questions_get($question_id = FALSE)
	{
		// Check if a specific question ID is provided
		if ($question_id === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			// If a specific ID is provided, get the question with that ID
			$questions = $this->QuestionModel->getQuestion($question_id);
		}

		// Check if questions are retrieved successfully
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			// If no questions are found, send a message in the response
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}


	/**
	 * Retrieves questions based on a search query.
	 *
	 * If no search value is provided, retrieves all questions.
	 * If a search value is provided, retrieves questions matching the search query.
	 *
	 * @param string|false $searchValue The search query to match against question titles, descriptions, expectations, or tags, or FALSE to retrieve all questions.
	 * @return void Returns a JSON response containing the retrieved questions or a message if no questions are found.
	 */
	public function display_search_questions_get($searchValue = FALSE)
	{
		// Check if a search value is provided
		if ($searchValue === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			// If a search value is provided, get questions matching the search query
			$questions = $this->QuestionModel->getSearchQuestions($searchValue);
		}

		// Check if questions are retrieved successfully
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			// If no questions are found, send a message in the response
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}


	/**
	 * Retrieves questions based on the provided category.
	 *
	 * If no category is provided, retrieves all questions.
	 * If a category is provided, retrieves questions belonging to that category.
	 *
	 * @param string|false $category The category to filter questions, or FALSE to retrieve all questions.
	 * @return void Returns a JSON response containing the retrieved questions or a message if no questions are found.
	 */
	public function display_category_questions_get($category = FALSE)
	{
		// Check if a category is provided
		if ($category === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			// If a category is provided, get questions belonging to that category
			$questions = $this->QuestionModel->getCategoryQuestions($category);
		}

		// Check if questions are retrieved successfully
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			// If no questions are found, send a message in the response
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}


	/**
	 * Retrieves questions based on the provided tag.
	 *
	 * If no tag is provided, retrieves all questions.
	 * If a tag is provided, retrieves questions tagged with that tag.
	 *
	 * @param string|false $tag The tag to filter questions, or FALSE to retrieve all questions.
	 * @return void Returns a JSON response containing the retrieved questions or a message if no questions are found.
	 */
	public function display_tag_questions_get($tag = FALSE)
	{
		// Check if a tag is provided
		if ($tag === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} else {
			// If a tag is provided, get questions tagged with that tag
			$questions = $this->QuestionModel->getTagsQuestions($tag);
		}

		// Check if questions are retrieved successfully
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			// If no questions are found, send a message in the response
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}


	/**
	 * Retrieves all categories.
	 *
	 * @return void Returns a JSON response containing the retrieved categories or a message if no categories are found.
	 */
	public function display_all_categories_get()
	{
		// Retrieve all categories
		$categories = $this->QuestionModel->getAllCategories();

		// Check if categories are retrieved successfully
		if ($categories) {
			$this->response($categories, REST_Controller::HTTP_OK);
		} else {
			// If no categories are found, send a message in the response
			$this->response(array(
				'status' => FALSE,
				'message' => 'No categories found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}


	/**
	 * Retrieves all tags.
	 *
	 * @return void Returns a JSON response containing the retrieved tags or a message if no tags are found.
	 */
	public function display_all_tags_get()
	{
		// Retrieve all tags
		$tags = $this->QuestionModel->getAllTags();

		// Check if tags are retrieved successfully
		if ($tags) {
			$this->response($tags, REST_Controller::HTTP_OK);
		} else {
			// If no tags are found, send a message in the response
			$this->response(array(
				'status' => FALSE,
				'message' => 'No tags found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}


	/**
	 * Upvotes a question.
	 *
	 * @param int $questionid The ID of the question to upvote.
	 * @return void Returns a JSON response indicating the success or failure of the upvote action.
	 */
	public function upvote_get($questionid)
	{
		// Upvote the question
		$upvote = $this->QuestionModel->upvote($questionid);

		// Check if the upvote is successful
		if ($upvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question upvoted successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			// If upvote fails, send an error message in the response
			$this->response("Failed to upvote question!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Downvotes a question.
	 *
	 * @param int $questionid The ID of the question to downvote.
	 * @return void Returns a JSON response indicating the success or failure of the downvote action.
	 */
	public function downvote_get($questionid)
	{
		// Downvote the question
		$downvote = $this->QuestionModel->downvote($questionid);

		// Check if the downvote is successful
		if ($downvote) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question downvoted successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			// If downvote fails, send an error message in the response
			$this->response("Failed to downvote question!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Displays all bookmarked questions for a user.
	 *
	 * @param int $userid The ID of the user.
	 * @return void Returns a JSON response containing the bookmarked questions or an error message if no bookmarked questions are found.
	 */
	public function display_all_bookmarked_questions_get($userid)
	{
		// Get bookmarked questions for the user
		$questions = $this->QuestionModel->getBookmarkQuestions($userid);

		// Check if bookmarked questions are found
		if ($questions) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} else {
			// If no bookmarked questions are found, send an error message in the response
			$this->response(array(
				'status' => FALSE,
				'message' => 'No bookmarked questions found!'
			), REST_Controller::HTTP_NO_CONTENT);
		}
	}


	/**
	 * Displays whether a question is bookmarked for a user.
	 *
	 * @return void Returns a JSON response indicating whether the question is bookmarked or not.
	 */
	public function display_question_bookmark_post()
	{
		// Get question ID and user ID from the request
		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		// Check if the question is bookmarked for the user
		$bookmark = $this->QuestionModel->getBookmark($questionid, $userid);

		// Send response based on whether the question is bookmarked or not
		if ($bookmark) {
			$this->response(array(
				'is_bookmark' => TRUE,
				'status' => TRUE,
				'message' => 'Bookmark added successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			// If the question is not bookmarked, send response indicating it is not bookmarked
			$this->response(array(
				'is_bookmark' => FALSE,
				'status' => TRUE,
				'message' => 'Bookmark removed successfully!'
			), REST_Controller::HTTP_OK);
		}
	}


	/**
	 * Adds a bookmark for a question by a user.
	 *
	 * @return void Returns a JSON response indicating the success or failure of adding the bookmark.
	 */
	public function add_bookmark_post()
	{
		// Get question ID and user ID from the request
		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		// Add the bookmark
		$bookmark = $this->QuestionModel->addBookmark($questionid, $userid);

		// Send response based on the success or failure of adding the bookmark
		if ($bookmark) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Bookmark added successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			// If adding the bookmark fails, send error response
			$this->response("Failed to add bookmark!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Removes a bookmark for a question by a user.
	 *
	 * @return void Returns a JSON response indicating the success or failure of removing the bookmark.
	 */
	public function remove_bookmark_post()
	{
		// Get question ID and user ID from the request
		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		// Remove the bookmark
		$bookmark = $this->QuestionModel->removeBookmark($questionid, $userid);

		// Send response based on the success or failure of removing the bookmark
		if ($bookmark) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Bookmark removed successfully!'
			), REST_Controller::HTTP_OK);
		} else {
			// If removing the bookmark fails, send error response
			$this->response("Failed to remove bookmark!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
