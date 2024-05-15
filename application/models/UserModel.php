<?php

class UserModel extends CI_Model{

	public function loginUser($username, $password){
		$this->db->select('*');
		$this->db->where("(username = '$username' OR email = '$username')");
		$this->db->where('password', $password);
		$this->db->from('users');

		$respond = $this->db->get();
		if($respond->num_rows() == 1){
			return ($respond->row(0));
		}else{
			return false;
		}
	}

	public function registerUser($userData){
		$insertDetails = $this->db->insert('users', $userData);
		return $insertDetails;
	}

	public function updateUser($user_id, $userData){
		// Select specific columns from the database table
		$this->db->select('user_id, username, occupation, firstname, lastname, email');
		$this->db->where('user_id', $user_id);
		$existingData = $this->db->get('users')->row_array();

		// Check if the existing data is different from $userData
		$isDifferent = false;
		foreach ($existingData as $key => $value) {
			if (isset($userData[$key]) && $existingData[$key] !== $userData[$key]) {
				$isDifferent = true;
				break;
			}
		}

		if ($isDifferent) {
			// Perform the update
			$this->db->where('user_id', $user_id);
			$updateDetails = $this->db->update('users', $userData);
			return $updateDetails;
		} else {
			// Data is already up to date, no need to update
			return false;
		}
	}

	public function updatePassword($user_id,  $oldpassword, $newpassword) {

		// Retrieve existing password from the database
		$this->db->select('password');
		$this->db->where('user_id', $user_id);
		$existingPasswordQuery = $this->db->get('users');

		// Check if user exists and retrieve the existing password
		if ($existingPasswordQuery->num_rows() > 0) {
			$existingPasswordRow = $existingPasswordQuery->row();
			$existingPassword = $existingPasswordRow->password;

			// Compare old password with the existing password
			if ($oldpassword == $existingPassword) {
				// Update password
				$this->db->where('user_id', $user_id);
				$this->db->update('users', array('password' => $newpassword));

				// Check if the password was successfully updated
				if ($this->db->affected_rows() > 0) {
					return $newpassword; // Password updated successfully
				} else {
					return false; // Failed to update password
				}
			} else {
				return false; // Old password doesn't match
			}
		} else {
			return false; // User not found
		}
	}

	public function forgetPassword($username, $newpassword){
		$this->db->select('password');
		$this->db->where("(username = '$username' OR email = '$username')");
		$existingPasswordQuery = $this->db->get('users');

		if($existingPasswordQuery->num_rows() > 0) {
			$this->db->where("(username = '$username' OR email = '$username')");
			$updatepassword = $this->db->update('users', array('password' => $newpassword));
			return $updatepassword;
//			if ($this->db->affected_rows() > 0) {
//				return $newpassword;
//			} else {
//				return false;
//			}
		}
	}


//	public function updatePassword($user_id, $userData){
//		$oldpassword = $userData['oldpassword'];
//		$newpassword = $userData['newpassword'];
//
//		$this->db->select('password');
//		$this->db->where('user_id', $user_id);
//		$existingPassword = $this->db->get('users');
//
//		if($oldpassword == $existingPassword){
//
//		}
//
//	}

	public function updateUserImage($user_id, $userData){

		$this->db->select('userimage');
		$this->db->where('user_id', $user_id);
		$existingData = $this->db->get('users')->row_array();

		// Check if the existing data is different from $userData
		$isDifferent = false;
		foreach ($existingData as $key => $value) {
			if (isset($userData[$key]) && $existingData[$key] !== $userData[$key]) {
				$isDifferent = true;
				break;
			}
		}

		if ($isDifferent) {
			// Perform the update
			$this->db->where('user_id', $user_id);
			$updateDetails = $this->db->update('users', $userData);
			return $updateDetails;
		} else {
			// Data is already up to date, no need to update
			return false;
		}
	}

	public function getUser($id){
		$this->db->select('*');
		$this->db->where('userID', $id);
		$this->db->from('users');

		$respond = $this->db->get();
		return $respond->row();
	}


	public function checkUser($username){
		$this->db->select('username, email');
		$this->db->where("(username = '$username' OR email = '$username')");
		$respond = $this->db->get('users');
		if($respond->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
}
