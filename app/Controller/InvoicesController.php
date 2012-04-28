<?php
class InvoicesController extends AppController {
	public $name = 'Invoices';
	public $helpers = array('Html', 'Form', 'Link');
	public $components = array('Authority');		

	
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
	
	public function index() {
		$this->autoRender = false;
		$this->redirect(array('action' => 'outstandinginvoices', 'controller' => 'projects'));
	}
	
	public function details($id = null) {
		$this->Authority->checkAuthority(Configure::read('AUTH_EDIT_INVOICES'));
		
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
								
				$invoiceNumber = $this->request->data['Invoice']['invoice_number'];				
				unset($this->request->data['Invoice']['invoice_number']);
				
				$this->Invoice->Project->id = $id;
				
				if(	$this->Invoice->save($this->request->data) &&
					$this->Invoice->Project->saveField('invoice_number', $invoiceNumber) ) {
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