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

	public function login_post()
	{
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

					// Prepare response data without sensitive information
					$responseData = array(
						'user_id' => $loginUserResponse->user_id,
						'username' => $loginUserResponse->username,
						'email' => $loginUserResponse->email,
					);

					$this->response(array(
						'status' => TRUE,
						'message' => 'User logged in successfully!',
						'data' => $responseData,
					), REST_Controller::HTTP_OK);
				} else {
					log_message('info', 'Login failed for username: ' . $username);
					$this->response(array(
						'status' => FALSE,
						'message' => 'Invalid username or password.',
					), REST_Controller::HTTP_UNAUTHORIZED);
				}
			} catch (Exception $e) {
				log_message('error', 'Login error: ' . $e->getMessage());
				$this->response(array(
					'status' => FALSE,
					'message' => 'An unexpected error occurred. Please try again later.',
				), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'Please enter a valid username and password.',
			), REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function signup_post()
	{
		$username = strip_tags($this->post('username'));
		$password = strip_tags($this->post('password'));
		$occupation = strip_tags($this->post('occupation'));
		$premium = strip_tags($this->post('premium'));
		$firstname = strip_tags($this->post('firstname'));
		$lastname = strip_tags($this->post('lastname'));
		$email = strip_tags($this->post('email'));

		if (!empty($username) && !empty($password) && !empty($occupation) && !empty($firstname) && !empty($lastname) && !empty($email)) {

			$password = sha1($password);

			$userData = array(
				'username' => $username,
				'password' => $password,
				'occupation' => $occupation,
				'premium' => $premium,
				'firstname' => $firstname,
				'lastname' => $lastname,
				'email' => $email
			);

			if ($this->UserModel->checkUsernameExist($username)) {
				$this->response("Username already exists, please enter unique username!", REST_Controller::HTTP_CONFLICT);
			} else {
				$signupUserResponse = $this->UserModel->signupUser($userData);
				if ($signupUserResponse) {
					$this->response(array(
							'status' => TRUE,
							'message' => 'User has been registered successfully!',
							'data' => $signupUserResponse)
						, REST_Controller::HTTP_OK);
				} else {
					$this->response("User registration failed, server error!", REST_Controller::HTTP_BAD_REQUEST);
				}
			}

		} else {
			$this->response("User registration failed, please enter valid information!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function reset_password_post()
	{
		$username = strip_tags($this->post('username'));
		$newPassword = strip_tags($this->post('newpassword'));

		if (!empty($username) && !empty($newPassword)) {

			$newPassword = sha1($newPassword);

			$resetPasswordResponse = $this->UserModel->resetPassword($username, $newPassword);

			if ($resetPasswordResponse !== false) {
				$this->response(array(
						'status' => TRUE,
						'message' => 'Password reset successfully!',
						'data' => $resetPasswordResponse)
					, REST_Controller::HTTP_OK);
			} else {
				$this->response('Password reset failed, server error!', REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response('Password reset failed, please enter valid information!', REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function edit_user_details_post()
	{
		$userId = strip_tags($this->post('user_id'));
		$username = strip_tags($this->post('username'));
		$occupation = strip_tags($this->post('occupation'));
		$premium = strip_tags($this->post('premium'));
		$firstname = strip_tags($this->post('firstname'));
		$lastname = strip_tags($this->post('lastname'));
		$email = strip_tags($this->post('email'));

		if (!empty($userId) && !empty($username) && !empty($occupation) && !empty($firstname) && !empty($lastname) && !empty($email)) {

			$userData = array(
				'user_id' => $userId,
				'username' => $username,
				'occupation' => $occupation,
				'premium' => $premium,
				'firstname' => $firstname,
				'lastname' => $lastname,
				'email' => $email
			);

			$updateProfileDetailsResponse = $this->UserModel->updateProfileDetails($userId, $userData);

			if ($updateProfileDetailsResponse !== false) {
				$this->response(array(
						'status' => TRUE,
						'message' => 'User deatils updated successfully!',
						'data' => $updateProfileDetailsResponse)
					, REST_Controller::HTTP_OK);
			} else {
				$this->response("User details update failed, server error!", REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response("User details update failed, please enter valid information!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function edit_user_image_post()
	{
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DevForum/assets/images/userimage/';

			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('image')) {
				$uploadData = $this->upload->data();
				$imagePath = '../../assets/images/userimage/' . $uploadData['file_name'];

				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} else {
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response(array('error' => 'Please choose an image file to upload!'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function upload_image_post()
	{
		$userId = strip_tags($this->post('user_id'));
		$profilePicture = strip_tags($this->post('userimage'));

		if (!empty($userId) && !empty($profilePicture)) {
			$profileData = array(
				'user_id' => $userId,
				'userimage' => $profilePicture
			);

			$updateProfileResponse = $this->UserModel->updateProfilePicture($userId, $profileData);

			if ($updateProfileResponse !== false) {
				$this->response(array(
						'status' => TRUE,
						'message' => 'Profile image updated successfully!',
						'data' => $updateProfileResponse)
					, REST_Controller::HTTP_OK);
			} else {
				$this->response("Profile image update failed, server error!", REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response("Profile image update failed, please enter valid information!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function change_password_post()
	{
		$userId = strip_tags($this->post('user_id'));
		$oldPassword = strip_tags($this->post('oldpassword'));
		$newPassword = strip_tags($this->post('newpassword'));

		if (!empty($userId) && !empty($oldPassword) && !empty($newPassword)) {
			$oldPassword = sha1($oldPassword);
			$newPassword = sha1($newPassword);

			$changePasswordResponse = $this->UserModel->changePassword($userId, $oldPassword, $newPassword);

			if ($changePasswordResponse !== false) {
				$this->response(array(
						'status' => TRUE,
						'message' => 'Password changed successfully!',
						'data' => $changePasswordResponse)
					, REST_Controller::HTTP_OK);
			} else {
				$this->response("Password change failed, server error!", REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response("Password change failed, please enter valid information!", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function logout_post()
	{
		$this->session->sess_destroy();
		$this->response(array(
			'success' => true,
			'message' => 'User has been logged out successfully!'
		), REST_Controller::HTTP_OK);
	}
}
