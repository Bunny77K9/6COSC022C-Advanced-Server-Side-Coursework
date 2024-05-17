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

	/**
	 * Handles user login via POST request.
	 */
	public function login_post()
	{
		// Get the user_id from the session
		$this->user_id = $this->session->userdata('user_id');

		// Validate and sanitize input
		$username = strip_tags($this->post('username'));
		$password = strip_tags($this->post('password'));

		if (!empty($username) && !empty($password)) {
			// Hash the password using SHA-1
			$hashedPassword = sha1($password);

			// Check the user credentials
			try {
				$loginUserResponse = $this->UserModel->loginUser($username, $hashedPassword);

				if ($loginUserResponse != false) {
					// Create session data
					$session_data = array(
						'user_id' => $loginUserResponse->user_id,
						'ip_address' => $_SERVER['REMOTE_ADDR'],
						'login_timestamp' => time(),
						'login_date' => date('Y-m-d H:i:s'),
					);

					// Set session data
					$this->session->set_userdata($session_data);

					// Respond with user data
					$this->response(array(
						'status' => TRUE,
						'message' => 'User logged in successfully!',
						'username' => $loginUserResponse->username,
						'user_id' => $loginUserResponse->user_id,
						'premium' => $loginUserResponse->premium,
						'title' => $loginUserResponse->title,
						'userimage' => $loginUserResponse->userimage,
						'firstname' => $loginUserResponse->firstname,
						'lastname' => $loginUserResponse->lastname,
						'email' => $loginUserResponse->email,
						'answercount' => $loginUserResponse->answercount,
						'questioncount' => $loginUserResponse->questioncount,
					), REST_Controller::HTTP_OK);
				} else {
					// Log unsuccessful login attempt
					log_message('info', 'Login failed for username: ' . $username);
					// Respond with unauthorized status and message
					$this->response(array(
						'status' => FALSE,
						'message' => 'Invalid username or password.',
					), REST_Controller::HTTP_UNAUTHORIZED);
				}
			} catch (Exception $e) {
				// Log unexpected login error
				log_message('error', 'Login error: ' . $e->getMessage());
				// Respond with internal server error status and message
				$this->response(array(
					'status' => FALSE,
					'message' => 'An unexpected error occurred. Please try again later.',
				), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			// Respond with bad request status and message
			$this->response(array(
				'status' => FALSE,
				'message' => 'Please enter a valid username and password.',
			), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Handles user signup via POST request.
	 */
	public function signup_post()
	{
		// Validate and sanitize input
		$username = strip_tags($this->post('username'));
		$password = strip_tags($this->post('password'));
		$title = strip_tags($this->post('title'));
		$premium = strip_tags($this->post('premium'));
		$firstname = strip_tags($this->post('firstname'));
		$lastname = strip_tags($this->post('lastname'));
		$email = strip_tags($this->post('email'));

		if (!empty($username) && !empty($password) && !empty($title) && !empty($firstname) && !empty($lastname) && !empty($email)) {
			// Hash the password using SHA-1
			$hashedPassword = sha1($password);

			$userData = array(
				'username' => $username,
				'password' => $hashedPassword,
				'title' => $title,
				'premium' => $premium,
				'firstname' => $firstname,
				'lastname' => $lastname,
				'email' => $email
			);

			try {
				if ($this->UserModel->checkUsernameExist($username)) {
					// Respond with conflict status and message if username already exists
					$this->response(array(
						'status' => FALSE,
						'message' => 'Username already exists!'
					), REST_Controller::HTTP_CONFLICT);
				} elseif ($this->UserModel->checkEmailExist($email)) {
					// Respond with conflict status and message if email already exists
					$this->response(array(
						'status' => FALSE,
						'message' => 'Email already exists!'
					), REST_Controller::HTTP_CONFLICT);
				} else {
					$signupUserResponse = $this->UserModel->signupUser($userData);

					if ($signupUserResponse) {
						// Respond with success status, message, and user data
						$this->response(array(
							'status' => TRUE,
							'message' => 'User has been registered successfully!',
							'data' => $signupUserResponse
						), REST_Controller::HTTP_OK);
					} else {
						// Respond with internal server error status and message if signup fails
						$this->response(array(
							'status' => FALSE,
							'message' => 'User registration failed, server error!'
						), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
					}
				}
			} catch (Exception $e) {
				// Log unexpected signup error
				log_message('error', 'Signup error: ' . $e->getMessage());
				// Respond with internal server error status and message
				$this->response(array(
					'status' => FALSE,
					'message' => 'An unexpected error occurred. Please try again later.'
				), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			// Respond with bad request status and message if input data is not valid
			$this->response(array(
				'status' => FALSE,
				'message' => 'Please enter valid information!'
			), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Handles password reset via POST request.
	 */
	public function reset_password_post()
	{
		// Validate and sanitize input
		$username = $this->post('username');
		$newPassword = $this->post('newpassword');

		if (!empty($username) && !empty($newPassword)) {
			// Use SHA-1 hash for the new password
			$hashedPassword = sha1($newPassword);

			$resetPasswordResponse = $this->UserModel->resetPassword($username, $hashedPassword);

			if ($resetPasswordResponse !== false) {
				// Respond with success status, message, and data if password reset is successful
				$this->response(array(
					'status' => TRUE,
					'message' => 'Password reset successfully!',
					'data' => $resetPasswordResponse
				), REST_Controller::HTTP_OK);
			} else {
				// Respond with internal server error status and message if password reset fails
				$this->response(array(
					'status' => FALSE,
					'message' => 'Password reset failed, server error!'
				), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			// Respond with bad request status and message if input data is not valid
			$this->response(array(
				'status' => FALSE,
				'message' => 'Password reset failed, please enter valid information!'
			), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Handles editing user details via POST request.
	 */
	public function edit_user_details_post()
	{
		// Validate and sanitize input
		$userId = strip_tags($this->post('user_id'));
		$username = strip_tags($this->post('username'));
		$title = strip_tags($this->post('title'));
		$premium = strip_tags($this->post('premium'));
		$firstname = strip_tags($this->post('firstname'));
		$lastname = strip_tags($this->post('lastname'));
		$email = strip_tags($this->post('email'));

		if (!empty($userId) && !empty($username) && !empty($title) && !empty($firstname) && !empty($lastname) && !empty($email)) {

			$userEditDetails = array(
				'user_id' => $userId,
				'username' => $username,
				'title' => $title,
				'premium' => $premium,
				'firstname' => $firstname,
				'lastname' => $lastname,
				'email' => $email
			);

			$updateProfileDetailsResponse = $this->UserModel->updateProfileDetails($userId, $userEditDetails);

			if ($updateProfileDetailsResponse !== false) {
				// Respond with success status, message, and data if user details update is successful
				$this->response(array(
					'status' => TRUE,
					'message' => 'User details updated successfully!',
					'data' => $updateProfileDetailsResponse
				), REST_Controller::HTTP_OK);
			} else {
				// Respond with internal server error status and message if user details update fails
				$this->response(array(
					'status' => FALSE,
					'message' => 'User details update failed, server error!'
				), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			// Respond with bad request status and message if input data is not valid
			$this->response(array(
				'status' => FALSE,
				'message' => 'User details update failed, please enter valid information!'
			), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Handles editing user image via POST request.
	 */
	public function edit_user_image_post()
	{
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
			// Set upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/userimage/';

			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10;

			$this->load->library('upload', $config);

			// Attempt to upload the image
			if ($this->upload->do_upload('image')) {
				$uploadData = $this->upload->data();
				$imagePath = '../../assets/images/userimage/' . $uploadData['file_name'];

				// Respond with success status and image path
				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} else {
				// Respond with bad request status and error message if upload fails
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			// Respond with bad request status if no image is uploaded
			$this->response(array('error' => 'Please choose an image file to upload!'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Handles uploading user image via POST request.
	 */
	public function upload_image_post()
	{
		// Validate and sanitize input
		$userId = strip_tags($this->post('user_id'));
		$profilePicture = strip_tags($this->post('userimage'));

		if (!empty($userId) && !empty($profilePicture)) {
			// Prepare profile data
			$profileData = array(
				'user_id' => $userId,
				'userimage' => $profilePicture
			);

			// Attempt to update profile picture
			$updateProfileResponse = $this->UserModel->updateProfilePicture($userId, $profileData);

			if ($updateProfileResponse !== false) {
				// Respond with success status, message, and data if profile image update is successful
				$this->response(array(
						'status' => TRUE,
						'message' => 'Profile image updated successfully!',
						'data' => $updateProfileResponse)
					, REST_Controller::HTTP_OK);
			} else {
				// Respond with internal server error status and message if profile image update fails
				$this->response(array(
					'status' => FALSE,
					'message' => 'Profile image update failed, server error!'
				), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			// Respond with bad request status and message if input data is not valid
			$this->response(array(
				'status' => FALSE,
				'message' => 'Profile image update failed, please enter valid information!'
			), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Handles changing user password via POST request.
	 */
	public function change_password_post()
	{
		// Validate and sanitize input
		$userId = strip_tags($this->post('user_id'));
		$oldPassword = strip_tags($this->post('oldpassword'));
		$newPassword = strip_tags($this->post('newpassword'));

		if (!empty($userId) && !empty($oldPassword) && !empty($newPassword)) {
			// Hash old and new passwords
			$oldPassword = sha1($oldPassword);
			$newPassword = sha1($newPassword);

			// Attempt to change password
			$changePasswordResponse = $this->UserModel->changePassword($userId, $oldPassword, $newPassword);

			if ($changePasswordResponse !== false) {
				// Respond with success status, message, and data if password change is successful
				$this->response(array(
						'status' => TRUE,
						'message' => 'Password changed successfully!',
						'data' => $changePasswordResponse)
					, REST_Controller::HTTP_OK);
			} else {
				// Respond with internal server error status and message if password change fails
				$this->response(array(
					'status' => FALSE,
					'message' => 'Password change failed, server error!'
				), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			// Respond with bad request status and message if input data is not valid
			$this->response(array(
				'status' => FALSE,
				'message' => 'Password change failed, please enter valid information!'
			), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Handles user logout via POST request.
	 */
	public function logout_post()
	{
		// Destroy session
		$this->session->sess_destroy();
		// Respond with success status and message
		$this->response(array(
			'success' => true,
			'message' => 'User has been logged out successfully!'
		), REST_Controller::HTTP_OK);
	}
}
