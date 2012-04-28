<?php
	class LinkHelper extends AppHelper {
		var $helpers = array('Html');
	
		function linkA($reqAuth, $title, $url = null, $options = array(), $confirmMessage = false) {
			
			if(!CakeSession::check('authority')) {
				return null;
			}
		
			$myAuth = CakeSession::read('authority'); 
		
			if(($myAuth & $reqAuth) == 0) {
				// Don't display the link for this user
				return null;
			} else {
				// This user should see the link
				return $this->Html->link($title, $url, $options, $confirmMessage);
			}
		}
		
		function HasAuthority($reqAuth) {
    		// Simply returns True or False if the user has the required authority level
    		if(!CakeSession::check('authority')) {
				return false;
			}
		
			$myAuth = CakeSession::read('authority'); 
		
			return !(($myAuth & $reqAuth) == 0);
		}
	}
?>