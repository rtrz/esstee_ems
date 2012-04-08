<?php
class InvoicesController extends AppController {
	public $name = 'Invoices';
	public $helpers = array('Html', 'Form');
	
	function beforeFilter() {
		if(CakeSession::check('isLoggedIn')) {
			if(!CakeSession::read('isLoggedIn')) {
				$this->Session->setFlash('Please log in.');
				$this->redirect(array('action' => 'index', 'controller' => 'users'));
			}
		} else {
			$this->Session->setFlash('Please log in.');
			$this->redirect(array('action' => 'index', 'controller' => 'users'));
		}
	}
	
	public function details($id = null) {
		
		$data = $this->Invoice->find('first', array('conditions' => array('Invoice.project_id' => $id)));
		if(!empty($data)) {
			if($this->request->is('get')) {
				// Read the data
	
				$this->Invoice->id = $data['Invoice']['id'];
				$this->request->data = $this->Invoice->read();
				$data = $this->request->data;
				$this->set('project', $data);
			} else {
				// Save the data
				$this->Invoice->id = $data['Invoice']['id'];
				
				if($this->Invoice->save($this->request->data)) {
					$this->Session->setFlash('Invoice has been updated.');
				} else {
					$this->Session->setFlash('Unable to update the invoice.');
				}
				$this->redirect(array('action' => 'details', $id));
			}
			
		} else {
			$this->Session->setFlash('Invoice not found!');
			$this->redirect(array('action'=>'index', 'controller' => 'projects'));
		}
	}	
}
?>