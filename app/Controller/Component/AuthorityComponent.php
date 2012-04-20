<?php
	class AuthorityComponent extends Component {
		
		/* bits:
			0 - logged in
			1 - read projects
			2 - add projects
			3 - edit/delete projects
			4 - read invoices
			5 - edit invoices
			6 - add other users
			7 - edit/delete other users
			8 - access backups
			9 - unused (9-15)
			10 - 
			11 - 
			12 - 
			13 - 
			14 -
			15 -
			
			common examples:
			15				  0
			0000 0001 1111 1111 = 511	admin: they can do everything
			0000 0000 0001 0001 = 17	receptionist: read projects, read invoices
			0000 0000 0000 0111 = 7		basic users+: can add and read projects
			0000 0000 0000 0011 = 3		basic users: can only read projects
			0000 0000 0000 0001 = 1		logged in: any user, as long as they're logged in
		 */
		
		// Allows "$this->controller->redirect"
		public function startup(&$controller) {
			$this->controller = $controller;
		}
		
		public function checkAuthority($requiredAuthority) {
			
			// Verify authority level
			if(!CakeSession::check('authority')) {
				$this->controller->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			
			$myAuthority = CakeSession::read('authority');
						
			//$this->controller->Session->setFlash($myAuthority . 'vs' . $requiredAuthority . '=' .  ($myAuthority & $requiredAuthority));
			
			// Bitwise AND - if the result is 0, this user doesn't meet the authority requirements
			if ( ($myAuthority & $requiredAuthority) == 0) {

				if($requiredAuthority == Configure::read('AUTH_ANY_USER')) {
				
					// The required authority is to simply be a user, and it failed so log out.
					// This should almost always pass
					$this->controller->redirect(array('controller' => 'users', 'action' => 'login'));
					
				} else {
				
					// Failed the authority check, but don't log them out.
					$this->controller->redirect(array('controller' => 'users', 'action' => 'noAuth'));
				}
			}
		}
	}
?>