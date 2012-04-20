<?php
	class User extends AppModel{ 
		var $name = 'User';
		var $validate = array(
			'username' => array('rule' => 'isUnique', 'message' => 'This username has already been taken.'),
			'password' => array('rule'    => array('minLength', 4), 'message' => 'Passwords must be at least 4 characters long.'),
			'authority' => array('rule' => 'notEmpty', 'message' => 'Choose an authority level.')
		);
		
		// MD5 hash the password before saving
		function beforeSave() {
	       	if ($this->data['User']['password']) {
            	$this->data['User']['password'] = md5($this->data['User']['password']);
       		}
       		return true;
  		}
		

	}
?>