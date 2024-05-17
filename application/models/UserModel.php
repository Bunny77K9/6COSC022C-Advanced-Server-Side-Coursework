<?php

class UserModel extends CI_Model
{

	/**
	 * Logs in a user by checking the provided username or email and hashed password against the database.
	 *
	 * @param string $username The username or email of the user.
	 * @param string $hashedPassword The hashed password of the user.
	 * @return mixed Returns the user data if login successful, otherwise returns false.
	 */
	public function loginUser($username, $hashedPassword)
	{
		try {
			// Selecting user data from the database based on provided credentials
			$this->db->select('*');
			$this->db->from('Users');
			$this->db->where("(username = '$username' OR email = '$username')");
			$this->db->where('password', $hashedPassword);
			$query = $this->db->get();

			// If only one user found matching the credentials, return user data
			if ($query->num_rows() == 1) {
				return $query->row();
			} else {
				// Log unsuccessful login attempts
				log_message('info', 'Login attempt with invalid credentials: ' . $username);
				// Return false for unsuccessful login
				return false;
			}
		} catch (Exception $e) {
			// Log database query errors
			log_message('error', 'Database query error: ' . $e->getMessage());
			// Return false for database query errors during login
			return false;
		}
	}


	/**
	 * Signs up a new user by inserting user data into the database.
	 *
	 * @param array $userData An associative array containing user data.
	 * @return boolean Returns true if user signup is successful, otherwise returns false.
	 */
	public function signupUser($userData)
	{
		try {
			// Attempt to insert user data into the 'Users' table
			$insertUserDetails = $this->db->insert('Users', $userData);
			return $insertUserDetails;
		} catch (Exception $e) {
			// Log any errors that occur during the database insert
			log_message('error', 'Database insert error: ' . $e->getMessage());
			return false;
		}
	}


	/**
	 * Checks if a username already exists in the database.
	 *
	 * @param string $username The username to check.
	 * @return boolean Returns true if the username exists, otherwise returns false.
	 */
	public function checkUsernameExist($username)
	{
		try {
			// Selecting username from the 'Users' table
			$this->db->select('username');
			$this->db->from('Users');
			$this->db->where('username', $username);
			$query = $this->db->get();

			// If only one row matches the query condition, return true
			if ($query->num_rows() == 1) {
				return true;
			} else {
				// If no rows match, return false
				return false;
			}
		} catch (Exception $e) {
			// Log database query errors
			log_message('error', 'Database query error: ' . $e->getMessage());
			// Return false indicating an error occurred during the query
			return false;
		}
	}


	/**
	 * Checks if an email already exists in the database.
	 *
	 * @param string $email The email to check.
	 * @return boolean Returns true if the email exists, otherwise returns false.
	 */
	public function checkEmailExist($email)
	{
		try {
			// Selecting email from the 'Users' table
			$this->db->select('email');
			$this->db->from('Users');
			$this->db->where('email', $email);
			$query = $this->db->get();

			// If only one row matches the query condition, return true
			if ($query->num_rows() == 1) {
				return true;
			} else {
				// If no rows match, return false
				return false;
			}
		} catch (Exception $e) {
			// Log database query errors
			log_message('error', 'Database query error: ' . $e->getMessage());
			// Return false indicating an error occurred during the query
			return false;
		}
	}


	/**
	 * Resets the password for a user.
	 *
	 * @param string $username The username or email of the user.
	 * @param string $newPassword The new password for the user.
	 * @return boolean Returns true if password reset is successful, otherwise returns false.
	 */
	public function resetPassword($username, $newPassword)
	{
		try {
			// Selecting password from the 'Users' table based on provided username or email
			$this->db->select('password');
			$this->db->where("(username = '$username' OR email = '$username')");
			$query = $this->db->get('Users');

			// If at least one user found with the provided username or email
			if ($query->num_rows() > 0) {
				// Updating the password for the user
				$this->db->where("(username = '$username' OR email = '$username')");
				$response = $this->db->update('Users', array('password' => $newPassword));
				return $response;
			} else {
				// If no user found, return false
				return false;
			}
		} catch (Exception $e) {
			// Log database update errors
			log_message('error', 'Database update error: ' . $e->getMessage());
			// Return false indicating an error occurred during the update
			return false;
		}
	}


	/**
	 * Updates profile details of a user.
	 *
	 * @param int $user_id The ID of the user.
	 * @param array $userEditDetails An associative array containing the updated user details.
	 * @return boolean Returns true if profile details are successfully updated, otherwise returns false.
	 */
	public function updateProfileDetails($user_id, $userEditDetails)
	{
		try {
			$isDifferentDetails = false;

			// Selecting user details from the 'Users' table based on user_id
			$this->db->select('user_id, username, title, firstname, lastname, email');
			$this->db->where('user_id', $user_id);

			// Getting existing user details as an associative array
			$existingUserDetails = $this->db->get('Users')->row_array();

			// Checking if the updated details are different from the existing ones
			foreach ($existingUserDetails as $key => $value) {
				if (isset($userEditDetails[$key]) && $existingUserDetails[$key] !== $userEditDetails[$key]) {
					$isDifferentDetails = true;
					break;
				}
			}

			// If updated details are different, update the user profile
			if ($isDifferentDetails) {
				$this->db->where('user_id', $user_id);
				$userUpdateDetails = $this->db->update('Users', $userEditDetails);
				return $userUpdateDetails;
			} else {
				// If updated details are same as existing, return false
				return false;
			}
		} catch (Exception $e) {
			// Log database update errors
			log_message('error', 'Database update error: ' . $e->getMessage());
			// Return false indicating an error occurred during the update
			return false;
		}
	}


	/**
	 * Changes the password of a user.
	 *
	 * @param int $user_id The ID of the user.
	 * @param string $oldPassword The current password of the user.
	 * @param string $newPassword The new password for the user.
	 * @return mixed Returns the new password if password change is successful, otherwise returns false.
	 */
	public function changePassword($user_id, $oldPassword, $newPassword)
	{
		// Selecting password from the 'Users' table based on user_id
		$this->db->select('password');
		$this->db->where('user_id', $user_id);

		// Executing the database query
		$query = $this->db->get('Users');

		// If at least one user found with the provided user_id
		if ($query->num_rows() > 0) {
			$existingPasswordQuery = $query->row();
			$existingPassword = $existingPasswordQuery->password;

			// Checking if the old password matches the existing password
			if ($oldPassword == $existingPassword) {
				// Updating the password with the new one
				$this->db->where('user_id', $user_id);
				$this->db->update('Users', array('password' => $newPassword));

				// If password update is successful, return the new password
				if ($this->db->affected_rows() > 0) {
					return $newPassword;
				} else {
					// If password update fails, return false
					return false;
				}
			} else {
				// If old password does not match existing password, return false
				return false;
			}
		} else {
			// If no user found with the provided user_id, return false
			return false;
		}
	}


	/**
	 * Updates the profile picture of a user.
	 *
	 * @param int $user_id The ID of the user.
	 * @param array $userData An associative array containing the updated user profile picture data.
	 * @return boolean Returns true if profile picture update is successful, otherwise returns false.
	 */
	public function updateProfilePicture($user_id, $userData)
	{
		// Selecting user image from the 'Users' table based on user_id
		$this->db->select('userimage');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('Users')->row_array();

		$isDifferentProfilePic = false;
		// Checking if the updated profile picture is different from the existing one
		foreach ($query as $key => $value) {
			if (isset($userData[$key]) && $query[$key] !== $userData[$key]) {
				$isDifferentProfilePic = true;
				break;
			}
		}

		// If updated profile picture is different, update the user's profile picture
		if ($isDifferentProfilePic) {
			$this->db->where('user_id', $user_id);
			$updatedProfilePicture = $this->db->update('Users', $userData);
			return $updatedProfilePicture;
		} else {
			// If updated profile picture is same as existing, return false
			return false;
		}
	}


	/**
	 * Retrieves user data based on user ID.
	 *
	 * @param int $id The ID of the user.
	 * @return object|null Returns user data if user found, otherwise returns null.
	 */
	public function getUser($id)
	{
		// Selecting all fields from the 'Users' table where userID matches the provided ID
		$this->db->select('*');
		$this->db->where('userID', $id);
		$this->db->from('Users');

		// Executing the database query
		$response = $this->db->get();
		// Returning the user data
		return $response->row();
	}
}
