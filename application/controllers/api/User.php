<?php

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel');
	}

//	public function login_post(){
//
//		log_message('debug', 'login_post() method call');
//
//		$_POST = json_decode(file_get_contents("php://input"), true);
//		$this->form_validation->set_rules('username', 'checkUsername', 'required');
//		$this->form_validation->set_rules('password', 'checkPassword', 'required');
//
//
//
//		$this->user_id = $this->session->userdata('user_id');
//
//		if($this->form_validation->run() == FALSE){
//			$this->response("Something went wrong!.", REST_Controller::HTTP_BAD_REQUEST);
//		}else{
//			$username = strip_tags($this->post('username'));
//			$password = strip_tags($this->post('password'));
//
//			$result = $this->UserModel->loginUser($username, sha1($password));
//
//			if($result != false){
//				// Set session here
//				$session_data = array(
//					'user_id' => $result->user_id,
//					'ip_address' => $_SERVER['REMOTE_ADDR'],
//					'login_timestamp' => time(),
//					'login_date' => date('Y-m-d H:i:s')
//				);
//
//				$this->session->set_userdata($session_data);
//
//				$this->response(array(
//					'status' => TRUE,
//					'message' => 'User has logged in successfully.',
//					'data' => true,
//					'username' => $result->username,
//					'user_id' => $result->user_id,
//					'premium' => $result->premium,
//					'occupation' => $result->occupation,
//					'userimage'=> $result->userimage,
//					'name' => $result->name,
//					'email' => $result->email,
//					'answerquestioncnt' => $result->answerquestioncnt,
//					'askquestioncnt' => $result->askquestioncnt,
//				), REST_Controller::HTTP_OK);
//			}else{
//				$this->response("Enter valid username and password", REST_Controller::HTTP_BAD_REQUEST);
//			}
//		}
//	}

	/**
	 * This function handles the login process for a user.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author Nandun Dias
	 *
	 */
	public function signin_post()
	{

		// Log the start of login process
		log_message('debug', 'login_post() method call');

		// Get the user_id from the session
		$this->user_id = $this->session->userdata('user_id');

		// Get the username and password from the POST request
		$username = strip_tags($this->post('username'));
		$password = strip_tags($this->post('password'));

		// Log the attempt of the user to log in
		log_message('info', 'username: ' . $username . ' trying to log the system.');

		// Call the loginUser method of the UserModel to check the credentials
		$result = $this->UserModel->loginUser($username, sha1($password));

		// If the result is not false, the login is successful
		if ($result != false) {
			// Set session data
			$session_data = array(
				'user_id' => $result->user_id,
				'ip_address' => $_SERVER['REMOTE_ADDR'],
				'login_timestamp' => time(),
				'login_date' => date('Y-m-d H:i:s')
			);

			// Set the session data
			$this->session->set_userdata($session_data);

			// Send a response with the user data
			$this->response(array(
				'status' => TRUE,
				'message' => 'User has logged in successfully.',
				'data' => true,
				'username' => $result->username,
				'user_id' => $result->user_id,
				'premium' => $result->premium,
				'occupation' => $result->occupation,
				'userimage' => $result->userimage,
				'firstname' => $result->firstname,
				'lastname' => $result->lastname,
				'email' => $result->email,
				'answerquestioncnt' => $result->answerquestioncnt,
				'askquestioncnt' => $result->askquestioncnt,
			), REST_Controller::HTTP_OK);

			// Log the successful login
			log_message('info', 'username: ' . $username . ' logged in successfully.');
		} else {
			// Log the failed login attempt
			log_message('info', 'username: ' . $username . ' failed to log in the system.');

			// Send a response with an error message
			$this->response("Enter valid username and password", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	/**
	 * This function handles the image upload process for a question.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author Nandun Dias
	 *
	 */
	public function new_question_image_post()
	{
		log_message('debug', 'new_question_image_post() method call');

		// Check if an image file is uploaded
		if (!empty($_FILES['image']['name'])) {
			// Define the upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/question/';

			// Log the upload directory for debugging purposes
			log_message('debug', 'uploadDir: ' . $uploadDir);

			// Set the upload configuration
			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10; // 10 MB

			// Load the upload library
			$this->load->library('upload', $config);

			// Perform the upload
			if ($this->upload->do_upload('image')) {
				// If the upload is successful, get the uploaded file data
				$uploadData = $this->upload->data();

				// Define the image path relative to the project root
				$imagePath = '../../assets/images/question/' . $uploadData['file_name'];

				log_message('debug', 'imagePath: ' . $imagePath);
				log_message('info', 'Image uploaded successfully');
				// Send a response with the image path
				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} else {
				log_message('error', 'Error uploading the file');
				// If there was an error uploading the file, send a response with the error message
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			log_message('info', 'No file was uploaded');
			// If no file was uploaded, send a response with an empty image path
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}

//	public function new_question_image_post() {
//		// Check if file is uploaded
//		if (!empty($_FILES['image']['name'])) {
//			// Define upload directory
//			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/question/';
//
//			log_message('debug', 'uploadDir: ' . $uploadDir);
//
//
//			// Set upload configuration
//			$config['upload_path'] = $uploadDir;
//			$config['allowed_types'] = 'gif|jpg|png|jpeg';
//			$config['max_size'] = 1024 * 10; // 2 MB
//
//			// Load upload library
//			$this->load->library('upload', $config);
//
//			// Perform upload
//			if ($this->upload->do_upload('image')) {
//				// File uploaded successfully
//				$uploadData = $this->upload->data();
////				$imagePath = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/' . $uploadData['file_name'];
//				$imagePath = '../../assets/images/question/' . $uploadData['file_name'];
//				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
//			} else {
//				// Error uploading file
//				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
//			}
//		} else {
//			// No file uploaded
//			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);		}
//	}

	/**
	 * This function handles the user registration process.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author Nandun Dias
	 *
	 */
	public function signup_post()
	{
		log_message('debug', 'register_post() method call');

		// Retrieve and sanitize input data
		$username = strip_tags($this->post('username'));
		$password = strip_tags($this->post('password'));
		$occupation = strip_tags($this->post('occupation'));
		$premium = strip_tags($this->post('premium'));
		$firstname = strip_tags($this->post('firstname'));
		$lastname = strip_tags($this->post('lastname'));
		$email = strip_tags($this->post('email'));

		// Check if all required fields are filled
		if (!empty($username) && !empty($password) && !empty($occupation) && !empty($firstname) && !empty($lastname) && !empty($email)) {

			// Prepare user data for registration
			$userData = array(
				'username' => $username,
				'password' => sha1($password), // Password is hashed using SHA1
				'occupation' => $occupation,
				'premium' => $premium,
				'firstname' => $firstname,
				'lastname' => $lastname,
				'email' => $email
			);

			// Check if the username already exists
			if ($this->UserModel->checkUser($username)) {
				// If username exists, send a response with an error message
				log_message('info', 'Username already exists');
				$this->response("Username already exists", 409);
			} else {
				// Register the user
				$userInformation = $this->UserModel->registerUser($userData);
				if ($userInformation) {
					log_message('info', 'User has been registered successfully.');
					// If registration is successful, send a response with the user data
					$this->response(array(
							'status' => TRUE,
							'message' => 'User has been registered successfully.',
							'data' => $userInformation)
						, REST_Controller::HTTP_OK);
				} else {
					// If registration failed, send a response with an error message
					$this->response("Failed to register user", REST_Controller::HTTP_BAD_REQUEST);
				}
			}

		} else {
			log_message('info', 'Enter valid information');
			// If not all required fields are filled, send a response with an error message
			$this->response("Enter valid information", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

//	public function register_post(){
////		$_POST = json_decode(file_get_contents("php://input"), true);
////		$this->form_validation->set_rules('username', 'checkUsername', 'required');
////		$this->form_validation->set_rules('password', 'checkPassword', 'required');
////		$this->form_validation->set_rules('occupation', 'checkOccupation', 'required');
////		$this->form_validation->set_rules('premium', 'checkPremium', 'required');
////		$this->form_validation->set_rules('name', 'checkName', 'required');
////		$this->form_validation->set_rules('email', 'checkEmail', 'required');
//
//		$username = strip_tags($this->post('username'));
//		$password = strip_tags($this->post('password'));
//		$occupation = strip_tags($this->post('occupation'));
//		$premium = strip_tags($this->post('premium'));
//		$name = strip_tags($this->post('name'));
//		$email = strip_tags($this->post('email'));
//
//		if(!empty($username) && !empty($password) &&!empty($occupation) && !empty($name) && !empty($email)){
//
//			$userData = array(
//				'username' => $username,
//				'password' => sha1($password),
//				'occupation' => $occupation,
//				'premium' => $premium,
//				'name' => $name,
//				'email' => $email
//			);
//
//			if($this->UserModel->checkUser($username)) {
//				$this->response("Username already exists", 409);
//			}else{
//				$userInformation = $this->UserModel->registerUser($userData);
//				if($userInformation){
//					$this->response(array(
//						'status' => TRUE,
//						'message' => 'User has been registered successfully.',
//						'data' => $userInformation)
//						, REST_Controller::HTTP_OK);
//				}else{
//					$this->response("Failed to register user", REST_Controller::HTTP_BAD_REQUEST);
//				}
//			}
//
//		}else{
//			$this->response("Enter valid information", REST_Controller::HTTP_BAD_REQUEST);
//		}
//
//	}

	/**
	 * This function handles the user update process.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author Nandun Dias
	 *
	 */
	public function edit_user_post()
	{
		// Log the start of user update process
		log_message('debug', 'edit_user_post() method call');

		// Retrieve and sanitize input data
		$user_id = strip_tags($this->post('user_id'));
		$username = strip_tags($this->post('username'));
		$occupation = strip_tags($this->post('occupation'));
		$premium = strip_tags($this->post('premium'));
		$firstname = strip_tags($this->post('firstname'));
		$lastname = strip_tags($this->post('lastname'));
		$email = strip_tags($this->post('email'));

		// Check if all required fields are filled
		if (!empty($user_id) && !empty($username) && !empty($occupation) && !empty($firstname) && !empty($lastname) && !empty($email)) {

			// Prepare user data for update
			$userData = array(
				'user_id' => $user_id,
				'username' => $username,
				'occupation' => $occupation,
				'premium' => $premium,
				'firstname' => $firstname,
				'lastname' => $lastname,
				'email' => $email
			);

			// Log the prepared user data
			log_message('info', 'Prepared user data for update: ' . print_r($userData, true));

			// Update the user
			$updateUser = $this->UserModel->updateUser($user_id, $userData);
			if ($updateUser !== false) {
				// Log the successful user update
				log_message('info', 'User has been updated successfully.');

				// User was updated successfully
				$this->response(array(
					'status' => TRUE,
					'message' => 'User has been updated successfully.',
					'data' => $userData) // Return updated user data
					, REST_Controller::HTTP_OK);
			} else {
				// Log the failed user update
				log_message('info', 'User data is already up to date.');

				// Update was not performed, possibly due to data being already up to date
				$this->response(array(
					'status' => FALSE,
					'message' => 'User data is already up to date.',
					'data' => null) // Return null data or an appropriate message
					, REST_Controller::HTTP_OK);
			}

		} else {
			// Log the invalid input data
			log_message('info', 'Enter valid information');

			// If not all required fields are filled, send a response with an error message
			$this->response("Enter valid information", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

//	public function edit_user_post(){
//		log_message('debug', 'edit_user_post() method call');
//
//		$user_id = strip_tags($this->post('user_id'));
//		$username = strip_tags($this->post('username'));
//		$occupation = strip_tags($this->post('occupation'));
//		$premium = strip_tags($this->post('premium'));
//		$name = strip_tags($this->post('name'));
//		$email = strip_tags($this->post('email'));
//
//		if(!empty($user_id) && !empty($username) && !empty($occupation) && !empty($name) && !empty($email)){
//
//			$userData = array(
//				'user_id' => $user_id,
//				'username' => $username,
//				'occupation' => $occupation,
//				'premium' => $premium,
//				'name' => $name,
//				'email' => $email
//			);
//
//			$updateUser = $this->UserModel->updateUser($user_id, $userData);
//			if($updateUser !== false) {
//				log_message('info', 'User has been updated successfully.');
//				// User was updated successfully
//				$this->response(array(
//					'status' => TRUE,
//					'message' => 'User has been updated successfully.',
//					'data' => $userData) // Return updated user data
//					, REST_Controller::HTTP_OK);
//			} else {
//				log_message('info', 'User data is already up to date.');
//				// Update was not performed, possibly due to data being already up to date
//				$this->response(array(
//					'status' => FALSE,
//					'message' => 'User data is already up to date.',
//					'data' => null) // Return null data or an appropriate message
//					, REST_Controller::HTTP_OK);
//			}
//
//		}else{
//			log_message('info', 'Enter valid information');
//			$this->response("Enter valid information", REST_Controller::HTTP_BAD_REQUEST);
//		}
//
//	}

	/**
	 * This function handles the user image update process.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author Nandun Dias
	 *
	 */
	public function edit_user_image_post()
	{
		// Log the start of user image update process
		log_message('debug', 'edit_user_image_post() method call');

		// Check if an image file is uploaded and its size is greater than 0
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
			// Define the upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/userimage/';

			// Log the upload directory for debugging purposes
			log_message('debug', 'uploadDir: ' . $uploadDir);

			// Set the upload configuration
			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10; // 10 MB

			// Load the upload library
			$this->load->library('upload', $config);

			// Perform the upload
			if ($this->upload->do_upload('image')) {
				// If the upload is successful, get the uploaded file data
				$uploadData = $this->upload->data();

				// Define the image path relative to the project root
				$imagePath = '../../assets/images/userimage/' . $uploadData['file_name'];

				// Log the image path for debugging purposes
				log_message('debug', 'imagePath: ' . $imagePath);

				// Send a response with the image path
				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} else {
				// If there was an error uploading the file, log the error and send a response with the error message
				log_message('error', 'Error uploading the file');
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			// If no file was uploaded, log the event and send a response with an empty image path
			log_message('info', 'No file was uploaded');
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}

//	public function edit_user_image_post(){
//		log_message('debug', 'edit_user_image_post() method call');
//
//		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
//			// Define upload directory
//			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/userimage/';
//
//			log_message('debug', 'uploadDir: ' . $uploadDir);
//
//			// Set upload configuration
//			$config['upload_path'] = $uploadDir;
//			$config['allowed_types'] = 'gif|jpg|png|jpeg';
//			$config['max_size'] = 1024 * 10; // 10 MB
//
//			// Load upload library
//			$this->load->library('upload', $config);
//
//			// Perform upload
//			if ($this->upload->do_upload('image')) {
//				// File uploaded successfully
//				$uploadData = $this->upload->data();
//				// Adjust imagePath relative to the URL structure
//				$imagePath = '../../assets/images/userimage/' . $uploadData['file_name'];
//
//				log_message('debug', 'imagePath: ' . $imagePath);
//				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
//			} else {
//
//				log_message('error', 'Error uploading the file');
//
//				// Error uploading file
//				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
//			}
//		} else {
//			log_message('info', 'No file was uploaded');
//			// No file uploaded, return a default image path or an empty response
//			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
//		}
//
////		$_POST = json_decode(file_get_contents("php://input"), true);
////
////		$user_id = strip_tags($this->post('user_id'));
////		$userpic = strip_tags($this->post('userimage'));
////
////
////		// Initialize userimage variable
////		$userimage = '';
////
////		if(!empty($_FILES['image']['name'])){
////
////			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/userimage/';
////			$uploadFile = $uploadDir . basename($_FILES['image']['name']);
////
////			if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
////				$userimage = $uploadFile;
////			}
////		}
////
////		if(!empty($user_id) && !empty($userimage)){
////			$userData = array(
////				'user_id' => $user_id,
////				'userimage' => $userimage
////			);
////
////			$updateUser = $this->UserModel->updateUserImage($user_id, $userpic);
////			if($updateUser !== false) {
////				// User was updated successfully
////				$this->response(array(
////					'status' => TRUE,
////					'message' => 'User image has been updated successfully.',
////					'data' => $userData) // Return updated user data
////					, REST_Controller::HTTP_OK);
////			} else {
////				// Update was not performed, possibly due to data being already up to date
////				$this->response(array(
////					'status' => FALSE,
////					'message' => 'User image is already up to date.',
////					'data' => null) // Return null data or an appropriate message
////					, REST_Controller::HTTP_OK);
////			}
////		}
//
//	}

	/**
	 * This function handles the logout process for a user.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author Nandun Dias
	 *
	 */
	public function logout_post()
	{
		// Log the start of logout process
		log_message('debug', 'logout_post() method call');

		// Destroy the session to log out the user
		$this->session->sess_destroy();

		// Log the successful logout
		log_message('info', 'User has logged out successfully.');

		// Send a response indicating the successful logout
		$this->response(array(
			'success' => true,
			'message' => 'Logout successful'
		), REST_Controller::HTTP_OK);
	}

//	public function logout_post() {
//		log_message('debug', 'logout_post() method call');
//		// Destroy session
//		$this->session->sess_destroy();
//
//		log_message('info', 'User has logged out successfully.');
//		// Send response
//		$this->response(array(
//			'success' => true,
//			'message' => 'Logout successful'
//		), REST_Controller::HTTP_OK);
//	}

	/**
	 * This function handles the user image upload process.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author Nandun Dias
	 *
	 */
	public function upload_image_post()
	{
		// Log the start of user image upload process
		log_message('debug', 'upload_image_post() method call');

		// Retrieve and sanitize input data
		$user_id = strip_tags($this->post('user_id'));
		$userpic = strip_tags($this->post('userimage'));

		// Initialize userimage variable
		$userimage = '';

		// Check if an image file is uploaded
		if (!empty($_FILES['image']['name'])) {

			// Define the upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/userimage/';
			$uploadFile = $uploadDir . basename($_FILES['image']['name']);

			// Move the uploaded file to the upload directory
			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
				$userimage = $uploadFile;
			}
		}

		// Check if user_id and userpic are not empty
		if (!empty($user_id) && !empty($userpic)) {
			// Log the user_id and userpic for debugging purposes
			log_message('debug', 'user_id: ' . $user_id . ', userpic: ' . $userpic);

			// Prepare user data for update
			$userData = array(
				'user_id' => $user_id,
				'userimage' => $userpic
			);

			// Update the user image
			$updateUser = $this->UserModel->updateUserImage($user_id, $userData);
			if ($updateUser !== false) {
				// Log the successful user image update
				log_message('info', 'User image has been updated successfully.');

				// User image was updated successfully
				$this->response(array(
					'status' => TRUE,
					'message' => 'User image has been updated successfully.',
					'data' => $userData) // Return updated user data
					, REST_Controller::HTTP_OK);
			} else {
				// Log the failed user image update
				log_message('info', 'User image is already up to date.');

				// Update was not performed, possibly due to image being already up to date
				$this->response(array(
					'status' => FALSE,
					'message' => 'User image is already up to date.',
					'data' => null) // Return null data or an appropriate message
					, REST_Controller::HTTP_OK);
			}
		}
	}

//	public function upload_image_post()
//	{
//		log_message('debug', 'upload_image_post() method call');
////		$_POST = json_decode(file_get_contents("php://input"), true);
//
//		$user_id = strip_tags($this->post('user_id'));
//		$userpic = strip_tags($this->post('userimage'));
//
//
//		// Initialize userimage variable
//		$userimage = '';
//
//		if(!empty($_FILES['image']['name'])){
//
//			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/userimage/';
//			$uploadFile = $uploadDir . basename($_FILES['image']['name']);
//
//			if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
//				$userimage = $uploadFile;
//			}
//		}
//
//		if(!empty($user_id) && !empty($userpic)){
//			log_message('debug', 'user_id: ' . $user_id . ', userpic: ' . $userpic);
//			$userData = array(
//				'user_id' => $user_id,
//				'userimage' => $userpic
//			);
//
//			$updateUser = $this->UserModel->updateUserImage($user_id, $userData);
//			if($updateUser !== false) {
//
//				log_message('info', 'User image has been updated successfully.');
//				// User was updated successfully
//				$this->response(array(
//					'status' => TRUE,
//					'message' => 'User image has been updated successfully.',
//					'data' => $userData) // Return updated user data
//					, REST_Controller::HTTP_OK);
//			} else {
//				log_message('info', 'User image is already up to date.');
//				// Update was not performed, possibly due to data being already up to date
//				$this->response(array(
//					'status' => FALSE,
//					'message' => 'User image is already up to date.',
//					'data' => null) // Return null data or an appropriate message
//					, REST_Controller::HTTP_OK);
//			}
//		}
//	}

	/**
	 * This function handles the password change process for a user.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author Nandun Dias
	 *
	 */
	public function change_password_post()
	{
		// Log the start of password change process
		log_message('debug', 'change_password_post() method call');

		// Retrieve and sanitize input data
		$user_id = strip_tags($this->post('user_id'));
		$oldpassword = strip_tags($this->post('oldpassword'));
		$newpassword = strip_tags($this->post('newpassword'));

		// Check if all required fields are filled
		if (!empty($user_id) && !empty($oldpassword) && !empty($newpassword)) {

			// Log the received input data
			log_message('info', 'Received input data: user_id - ' . $user_id);

			// Hash the old and new passwords
			$oldpassword = sha1($oldpassword);
			$newpassword = sha1($newpassword);

			// Call the updatePassword method of the UserModel to change the password
			$updateUser = $this->UserModel->updatePassword($user_id, $oldpassword, $newpassword);
			if ($updateUser !== false) {
				// Log the successful password change
				log_message('info', 'User password has been updated successfully.');

				// Send a response indicating the successful password change
				$this->response(array(
					'status' => TRUE,
					'message' => 'User password has been updated successfully.') // Return updated user data
					, REST_Controller::HTTP_OK);
			} else {
				// Log the failed password change
				log_message('info', 'Please check the credentials.');

				// Send a response indicating the failed password change
				$this->response(array(
					'status' => FALSE,
					'message' => 'Please check the credentials.',
					'data' => null) // Return null data or an appropriate message
					, REST_Controller::HTTP_OK);
			}
		}
	}

//	public function change_password_post(){
////		$_POST = json_decode(file_get_contents("php://input"), true);
//
//		log_message('debug', 'change_password_post() method call');
//
//		$user_id = strip_tags($this->post('user_id'));
//		$oldpassword = strip_tags($this->post('oldpassword'));
//		$newpassword = strip_tags($this->post('newpassword'));
//
//		if(!empty($user_id) && !empty($oldpassword) && !empty($newpassword)){
//
//			log_message('info', 'Received input data: user_id - ' . $user_id );
//			$oldpassword = sha1($oldpassword);
//			$newpassword = sha1($newpassword);
//
//			$updateUser = $this->UserModel->updatePassword($user_id, $oldpassword, $newpassword);
//			if($updateUser !== false) {
//				log_message('info', 'User password has been updated successfully.');
//				// User was updated successfully
//				$this->response(array(
//					'status' => TRUE,
//					'message' => 'User password has been updated successfully.') // Return updated user data
//					, REST_Controller::HTTP_OK);
//			} else {
//				log_message('info', 'Please check the credentials.');
//				// Update was not performed, possibly due to data being already up to date
//				$this->response(array(
//					'status' => FALSE,
//					'message' => 'Please check the credentials.',
//					'data' => null) // Return null data or an appropriate message
//					, REST_Controller::HTTP_OK);
//			}
//		}
//	}
	/**
	 * This function handles the password reset process for a user.
	 *
	 * @param none
	 *
	 * @return JSON response
	 * @author NandunDias
	 *
	 */
	public function reset_password_post()
	{
		// Log the start of password reset process
		log_message('debug', 'reset_password_post() method call');

		// Retrieve and sanitize input data
		$username = strip_tags($this->post('username'));
		$newpassword = strip_tags($this->post('newpassword'));

		// Check if username and newpassword are not empty
		if (!empty($username) && !empty($newpassword)) {

			// Hash the new password
			$newpassword = sha1($newpassword);

			// Log the received input data
			log_message('info', 'Received input data: username - ' . $username);

			// Call the resetPassword method of the UserModel to reset the password
			$updateUser = $this->UserModel->resetPassword($username, $newpassword);
			if ($updateUser !== false) {
				// Log the successful password reset
				log_message('info', 'User password has been updated successfully.');

				// Send a response indicating the successful password reset
				$this->response(array(
					'status' => TRUE,
					'message' => 'User password has been updated successfully.') // Return updated user data
					, REST_Controller::HTTP_OK);
			} else {
				// Log the failed password reset
				log_message('info', 'Please check the credentials.');

				// Send a response indicating the failed password reset
				$this->response(array(
					'status' => FALSE,
					'message' => 'Please check the credentials.',
					'data' => null) // Return null data or an appropriate message
					, REST_Controller::HTTP_OK);
			}
		}
	}

//	public function reset_password_post(){
//		log_message('debug', 'reset_password_post() method call';
////		$_POST = json_decode(file_get_contents("php://input"), true);
//
//		$username = strip_tags($this->post('username'));
//		$newpassword = strip_tags($this->post('newpassword'));
//
//		if(!empty($username) && !empty($newpassword)){
//
//			$newpassword = sha1($newpassword);
//
//			log_message('info', 'Received input data: username - ' . $username );
//			$updateUser = $this->UserModel->resetPassword($username, $newpassword);
//			if($updateUser !== false) {
//				log_message('info', 'User password has been updated successfully.');
//				// User was updated successfully
//				$this->response(array(
//					'status' => TRUE,
//					'message' => 'User password has been updated successfully.') // Return updated user data
//					, REST_Controller::HTTP_OK);
//			} else {
//				log_message('info', 'Please check the credentials.');
//				// Update was not performed, possibly due to data being already up to date
//				$this->response(array(
//					'status' => FALSE,
//					'message' => 'Please check the credentials.',
//					'data' => null) // Return null data or an appropriate message
//					, REST_Controller::HTTP_OK);
//			}
//		}
//	}
}
