<?php
	class UsersController extends AppController {
		public $name = 'Users';
		public $helpers = array('Html', 'Form');		
		
		function beforeFilter() {
			if(CakeSession::check('isLoggedIn')) {
				if(CakeSession::read('isLoggedIn')) {
					$userData = $this->User->find(	'first', 
													array(	'conditions' => 
															array(	'User.username' => CakeSession::read('username'),
																	'User.password' => CakeSession::read('password'))));
					
					if(	$userData['User']['username'] == CakeSession::read('username') &&
						$userData['User']['password'] == CakeSession::read('password')) {
							$this->Session->setFlash(CakeSession::read('username') . " | " . CakeSession::read('password'));
							//$this->Session->redirect(array('action' => 'index', 'controller' => 'projects'));
						}
				}
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
					CakeSession::write('username', $this->request->data['User']['username']);
					CakeSession::write('password', $this->request->data['User']['password']);
					$this->Session->setFlash('Welcome to the Esstee Management System, <b>' . $this->request->data['User']['username'] . '</b>!');
					$this->redirect(array('controller'=>'projects','action'=>'index'));
				} else {
					//clear login credentials because login failed
					CakeSession::write('isLoggedIn', false);
					CakeSession::delete('username');
					CakeSession::delete('password');
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
			}
		}
	}
?>