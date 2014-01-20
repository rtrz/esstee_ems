<?php
class ProjectsController extends AppController {
	public $name = 'Projects';
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
	
	// Default page retrieves all projects
	public function index() {
		$this->Authority->checkAuthority(Configure::read('AUTH_ANY_USER'));
				
		if(!empty($this->data)) {
			// Search
			
			// Get the search term from the input form
			$searchTerm = $this->request->data['Project']['search'];
			
			// First search the Unit and Aspect models to see if the search matches any of their descriptions
			$subConditions = array('OR' => array( array('Unit.description LIKE' => '%' . $searchTerm . '%')));
			$subQuery_Unit = $this->Project->Unit->find('all', array('fields'=>array('DISTINCT Unit.project_id'),
																	 'conditions'=>$subConditions));
																
			$subConditions = array('OR' => array( array('Aspect.description LIKE' => '%' . $searchTerm . '%')));
			$subQuery_Aspect = $this->Project->Aspect->find('all', array(	'fields'=>array('DISTINCT Aspect.project_id'),
																	 	'conditions'=>$subConditions));									
			
			// Construct the OR conditions for the main query to the Projects model
			$orConditions = array(	array(	'Project.customer LIKE' => '%' . $searchTerm . '%'),
									array(	'Project.title LIKE' => '%' . $searchTerm . '%'),
									array(	'Project.comments LIKE' => '%' . $searchTerm . '%'),
									array(	'Project.customer_po LIKE' => '%' . $searchTerm . '%'),
									array(	'Project.docket_number LIKE' => '%' . $searchTerm . '%'),
									array(	'Project.docket_year LIKE' => '%' . $searchTerm . '%'),
									array(	'Project.invoice_number LIKE' => '%' . $searchTerm . '%'));
									
			// Now add the the IDs of the projects found in the Aspect and Units tables that match
			// the search term to the OR condition array
			foreach($subQuery_Unit as $sq):
				array_push($orConditions, array('Project.id' => $sq['Unit']['project_id']));
			endforeach;		
			
			foreach($subQuery_Aspect as $sq):
				array_push($orConditions, array('Project.id' => $sq['Aspect']['project_id']));
			endforeach;				
			
			$conditions = array('OR' => $orConditions);
			
			// Perform the grand query!
			$query = $this->Project->find('all', array(	'conditions' => $conditions));
			
			$this->Session->setFlash('Showing results for "' . $searchTerm . '"');				
		
			$this->set('projects', $query);
			$this->set('searchResults', true);
			
		} else {
			//Show all results
			//$this->	Project->order = 'Project.docket_number DESC';
			$this->Project->order = array('Project.docket_year DESC','Project.docket_number DESC');
            $this->set('projects', $this->Project->find('all', array('recursive' => false,
'fields' => array('Project.id','Project.docket_number', 'Project.docket_year', 'Project.customer', 'Project.title','Project.is_shipped'))));
			$this->set('searchResults', false);
		}
	}
	
	// View an individual project
	public function view($id = null) {
		$this->Authority->checkAuthority(Configure::read('AUTH_READ_PROJECTS'));

		$this->Project->id = $id;
		$this->set('project', $this->Project->read());	
	}
	
	// Add a new project
	public function add() {
		$this->Authority->checkAuthority(Configure::read('AUTH_ADD_PROJECTS'));
		
		if(empty($this->data)) {
			//find the last docket number
			$prev_docket = $this->Project->find('first', array(
							'order'=>array('Project.docket_number'=>'desc'),
							'conditions'=>array('Project.docket_year'=>intval(date('y')))));
			//$this->set('prev_docket', $prev_docket['Project']);
			$this->request->data['Project']['docket_number'] = $prev_docket['Project']['docket_number'] + 1;
			$this->request->data['Aspect'][2]['description'] = "Mitsubishi 40\"";
		}
		
		if(!empty($this->data)) {
			
			unset($this->Project->Unit->validate['project_id']);
			unset($this->Project->Aspect->validate['project_id']);
							
			if($this->request->is('post')) {
				
				$this->request->data['Project']['timestamp'] = date('Y-m-d H:i:s');
                $this->request->data['Project']['is_shipped'] = 0;
				$this->request->data['Invoice']['project_id'] = $id;
				$this->request->data['Invoice']['billing_date'] = null;
				$this->request->data['Invoice']['is_billed'] = 0;
				
				if($this->Project->saveAll($this->request->data)) {
					
					$this->Session->setFlash('Your project has been saved');
					$this->redirect(array('action'=>'index'));
					
				}
			}
		}
	}
	
	// Edit a project
	public function edit($id) {
		$this->Authority->checkAuthority(Configure::read('AUTH_EDIT_DELETE_PROJECTS'));

		
		if($id == null) {
			$this->redirect(array('action' => 'index'));
		}
		
		$this->Project->id = $id;
		if($this->request->is('get')) {
			$this->request->data = $this->Project->read();
			$data = $this->Project->read();
			$this->set('project', $data);
			$this->set('uow_count',count($data['Unit']));
			
			// Maintain newlines in the input textboxes
			foreach($this->request->data['Unit'] as $unit):
				$unit['description'] = str_replace('\n', "\n", $unit['description']);
			endforeach;
			foreach($this->request->data['Aspect'] as $aspect):
				$aspect['description'] = str_replace('\n', "\n", $aspect['description']);
			endforeach;
			$this->request->data['Project']['address'] = 
					str_replace('\n', "\n", $this->request->data['Project']['address']);
			$this->request->data['Project']['shipping_address'] = 
					str_replace('\n', "\n", $this->request->data['Project']['shipping_address']);
					
			// Maintain special characters
			$this->request->data['Project']['customer'] =
				str_replace('&amp;',"&", $this->request->data['Project']['customer']);
	
			$this->set('invoice_id', $this->request->data['Project']['id']);

		} else {
			
			unset($this->Project->Unit->validate['project_id']);
			unset($this->Project->Aspect->validate['project_id']);
			unset($this->Project->Invoice->validate['project_id']);
			
			//set the timestamp manually
			$this->request->data['Project']['timestamp'] = date('Y-m-d H:i:s');
			
			//save the data and its hasMany relationships (Units and Aspects)
			if ($this->Project->saveAll($this->request->data)) {	
				
				// the newly generated data set project id
				$new_id = $this->Project->id;
				
				// delete the original data set that was being editted
				$this->Project->id = $id;
				$this->Project->Unit->deleteAll(array('project_id'=>$id));	
				$this->Project->Aspect->deleteAll(array('project_id'=>$id));
				$this->Project->Invoice->updateAll(	array('Invoice.project_id' => $new_id),
													array('Invoice.project_id' => $id));
				$this->Project->delete($id);
				$this->Session->setFlash('Changes saved.');
	            $this->redirect(array('action' => 'edit', $new_id));
	        } else {
	            $this->Session->setFlash('Unable to update your project.');
	        }
		}
	}
	
	// Delete a project
	public function delete($id) {
		$this->Authority->checkAuthority(Configure::read('AUTH_EDIT_DELETE_PROJECTS'));

		$this->autoRender = false;
		
		if($id == null) {
			$this->redirect(array('action' => 'index'));
		}
		
		$this->Project->id = $id;
		$data = $this->Project->read();
		
		// delete all associated records
		$this->Project->Unit->deleteAll(array('project_id'=>$id));	
		$this->Project->Aspect->deleteAll(array('project_id'=>$id));
		$this->Project->Invoice->deleteAll(array('project_id'=>$id));
		$this->Project->delete($id);
		
		$this->Session->setFlash('Project ' . $data['Project']['docket_year'] . '-' . $data['Project']['docket_number'] . ' has been deleted.');	
		$this->redirect(array('action'=>'index'));
	}

    // Mark a project as shipped/not shipped (toggle).
    public function ship($id) {
		$this->Authority->checkAuthority(Configure::read('AUTH_EDIT_DELETE_PROJECTS'));

        $this->autoRender = false;

        if($id != null) {
            $this->Project->id = $id;
            $project = $this->Project->read();

            // Toggle the value.
            $is_shipped = ($project['Project']['is_shipped'] == 0);

            $this->Project->set('is_shipped', $is_shipped);
            $this->Project->save();
        }

		$this->redirect(array('action'=>'index'));
    }
	
	public function autocomplete() {
		$this->layout = 'json';
		if(isset($this->params['url']['customer'])) {
			$customer = $this->params['url']['customer'];

			$suggestions = $this->Project->find('all', array(	'fields'=>array('DISTINCT Project.customer'), 
																'recursive'=>-1,
																'conditions'=>array('Project.customer LIKE' => '%' . $customer . '%')
																));
			
			$this->set('data', $suggestions);
		}
	}
	
	public function outstandinginvoices($instruction = null) {
		// instruction:
		// null ->  show only unbilled invoices
		// 'all' -> show all invoices, both billed and unbilled
		
		$this->Authority->checkAuthority(Configure::read('AUTH_READ_INVOICES'));
		
		//Get all results
		//$this->Project->order = 'Project.docket_number DESC';
		$this->Project->order = array('Project.docket_year DESC','Project.docket_number DESC');
        $projects = $this->Project->find('all', array(
					'recursive' => false,
					'fields' => array('Project.id','Project.docket_number', 'Project.docket_year', 'Project.customer', 'Project.title','Project.date_required','Invoice.is_billed')));
		$alert = false;
		
		$under30billed = 0;
		$under30notbilled = 0;
		$over30billed = 0;
		$over30notbilled = 0;
		
		//only get invoices that need to be billed (30 days between ship date and today's date)
		$limit = count($projects);
		for($i=0; $i < $limit; $i++) {
			
			//determine time between today's date and the docket's due date
			$date_today = date('Y-m-d');
			$date_required = $projects[$i]['Project']['date_required'];
			$diff = strtotime($date_today) - strtotime($date_required);
			$projects[$i]['Invoice']['days_elapsed'] = round($diff/60/60/24);
			if($projects[$i]['Invoice']['days_elapsed'] > 30 && !$projects[$i]['Invoice']['is_billed']) {
				$alert = true;
			}
			
			// TODO count unbilled and billed dockets for >30 and <30 day dockets!
			if($projects[$i]['Invoice']['is_billed']) {
				if($projects[$i]['Invoice']['days_elapsed'] > 30) { 
					$over30billed++;
				} else {
					$under30billed++;
				}
			} else {
				if($projects[$i]['Invoice']['days_elapsed'] > 30) { 
					$over30notbilled++;
				} else {
					$under30notbilled++;
				}
			}
			
			//remove dockets from the list that have already been billed
			if($projects[$i]['Invoice']['is_billed'] && $instruction == null) {
				
				//hide billed dockets
				unset($projects[$i]);
				$projects = array_values($projects);
					
				$limit--;
				$i--;
					
				continue;				
			}
		}

			
		
		$this->set('projects', $projects);
		$this->set('over30billed', $over30billed);
		$this->set('over30notbilled', $over30notbilled);
		$this->set('under30billed', $under30billed);
		$this->set('under30notbilled', $under30notbilled);
		$this->set('instruction', $instruction);
		
		if($alert) {
			$this->Session->setFlash('You have invoices outstanding over 30 days.');
		}
	}
	
	public function csvbackup($year = null) {
		$this->Authority->checkAuthority(Configure::read('AUTH_ACCESS_BACKUPS'));
		$this->layout = 'db_backup_csv';
		
		$this->Project->order = 'Project.docket_number ASC';

        // Subtract 2000 for the docket_year column. For example, 2013 is stored as 13.
		$this->set('projects', $this->Project->find('all', array('conditions' => array('Project.docket_year' => ($year - 2000)))));
        $this->set('year', $year);
	}
	
	public function sqlbackup() {
		$this->Authority->checkAuthority(Configure::read('AUTH_ACCESS_BACKUPS'));

		// redirect to http://[base URL]/projects//db_backup_sql.php
		// this step serves as authentication instead of simply directing straight to
		// that URL
		$this->redirect(array('controller'=>'','action'=>'db_backup_sql.php?auth=' . date(Ymd)));
		
	}
	
	public function backup() {
		$this->Authority->checkAuthority(Configure::read('AUTH_ACCESS_BACKUPS'));		
	}
}
?>
