<?php
	class UsersController extends AppController {
		public $name = 'Users';
		public $helpers = array('Html', 'Form', 'Link');
		public $components = array('Authority');		
		
		function beforeFilter() {
			if(CakeSession::check('authority')) {
				$this->set('authLevel', CakeSession::read('authority'));
			}
		}
		
		function index() {
			$this->autoRender = false;
			$this->redirect(array('action' => 'login'));
		}
		
		function login() {
			if($this->request->is('post')) {
				
				// Try to find matching user credentials
				$userData = $this->User->find(	'first', 
												array('conditions' => 
													array(	'User.username' => $this->request->data['User']['username'],
															'User.password' => md5($this->request->data['User']['password']))));
				if(!empty($userData)) {
					//successful login
					CakeSession::write('isLoggedIn', true);
					CakeSession::write('username', $userData['User']['username']);
					CakeSession::write('password', $userData['User']['password']); 
					CakeSession::write('authority', $userData['User']['authority']);
					$this->Session->setFlash('Welcome to the Esstee Management System, <b>' . $this->request->data['User']['username'] . '</b>!');
					$this->redirect(array('controller'=>'projects','action'=>'index'));
				} else {
					//clear login credentials because login failed
					CakeSession::write('isLoggedIn', false);
					CakeSession::delete('username');
					CakeSession::delete('password');
					CakeSession::delete('authority');
					$this->Session->setFlash('Incorrect credentials');
				}
			} else {
				//user is logging out
				if(CakeSession::check('isLoggedIn') && CakeSession::check('username')) {
					if(CakeSession::read('isLoggedIn')) {
						$this->Session->setFlash('<b>' . CakeSession::read('username') . '</b> has successfully logged out.');
					}
				}
				
				//clear login credentials
				CakeSession::write('isLoggedIn', false);
				CakeSession::delete('username');
				CakeSession::delete('password');
				CakeSession::delete('authority');
			}
		}
		
		function add() {
			//check auth - admin only
			$this->Authority->checkAuthority(Configure::read('AUTH_ADD_USERS'));

			if(!empty($this->data)) {
				if($this->request->is('post')) {
					// MD5 hash the password
					if($this->User->save($this->request->data)) {
						$this->Session->setFlash('<b>' . $this->request->data['User']['username'] . '</b> is now a user.');
						$this->redirect(array('action' => 'admin'));
					} else {
						$this->Session->setFlash('The user could not be created. Please try again.');
					}
				}
			}
		}
		
		function admin() {
			//check auth - admin and non-admin
			$this->Authority->checkAuthority(Configure::read('AUTH_ANY_USER'));
			
			$authorityLevel = CakeSession::read('authority');
			
			if($authorityLevel == Configure::read('AUTH_LEVEL_ADMIN')) {
				// Admin user - show all accounts
				$this->set('users', $this->User->find('all'));
			} else {
				// Non-admin user - only show their account
				// Initialize an array so the view can handle either case with the same logic.
				$users[0] = $this->User->find('first', array('conditions' => array('User.username' => CakeSession::read('username'))));
				$this->set('users', $users);
			}
		}

		function delete($id) {
			//check auth - admin only
			$this->autoRender = false;
			
			$this->Authority->checkAuthority(Configure::read('AUTH_EDIT_DELETE_USERS'));
			
			if($id == null) {
				$this->redirect(array('action' => 'admin'));
			}

			$this->User->id = $id;
			$data = $this->User->read();

			// Check if user is trying to delete themselves.
			if($data['User']['username'] == CakeSession::read('username')) {
				$this->Session->setFlash('You can\'t delete yourself!');
				$this->redirect(array('action' => 'admin'));
			}

			if($this->User->delete()) {
				$this->Session->setFlash('<b>' . $data['User']['username'] . '</b> deleted.');
				$this->redirect(array('action' => 'admin'));
			} else {	
				$this->Session->setFlash('<b>' . $data['User']['username'] . '</b> was not deleted. Please try again');
				$this->redirect(array('action' => 'admin'));
			}
		}

		function edit($id) {			

			if($id == null) {
				$this->redirect(array('action' => 'admin'));
			}
			
			$myInfo = $this->User->find('first', array('conditions' => array('User.username' => CakeSession::read('username'))));
			if($id != $myInfo['User']['id']) {
				// User is trying edit another user's info.
				$this->Authority->checkAuthority(Configure::read('AUTH_EDIT_DELETE_USERS'));
			} else {
				// User is editing their own user info.
				$this->Authority->checkAuthority(Configure::read('AUTH_ANY_USER'));
			}

			$this->set('user_id', $id);
			$this->User->id = $id;
			if($this->request->is('get')) {
				$this->request->data = $this->User->read();
				$this->request->data['User']['password'] = '';
				
			} else {

				if($this->User->save($this->request->data)) {
					$this->Session->setFlash('<b>' . $this->request->data['User']['username'] . '</b> has been updated.');
				} else {
					$this->Session->setFlash('Unable to update <b>' . $this->request->data['User']['username'] . '</b>, please try again.');
				}
			}
		}
		
		function noAuth() {
			// Tells the user they don't have authority to do something.
			$this->Authority->checkAuthority(Configure::read('AUTH_ANY_USER'));
			$this->set('referer',$this->referer()); 
		}
	}
?>