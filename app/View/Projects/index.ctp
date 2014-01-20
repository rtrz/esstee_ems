<!-- File: /app/View/Projects/index.ctp -->
<?php echo $this->Html->script('https://www.google.com/jsapi'); ?>
<script type="text/javascript">
	google.load('visualization', '1', {packages:['table']});
    google.setOnLoadCallback(drawTable);
    function drawTable() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Docket');
	  data.addColumn('string', 'Customer');
      data.addColumn('string', 'Description');
      <?php if($this->Link->HasAuthority(Configure::read('AUTH_EDIT_DELETE_PROJECTS'))) { ?>
     	  data.addColumn('string', 'Ship');
      <?php } ?>
 	  data.addRows(<?php echo count($projects); ?>);
	
	<?php for($i=0; $i < count($projects); $i++) {		
		 	echo 'data.setCell(' . $i . ', 0, ' . $projects[$i]['Project']['docket_number'] . ', \'' . $projects[$i]['Project']['docket_year'] . '-' . $projects[$i]['Project']['docket_number'] . '\');';
		    echo 'data.setCell(' . $i . ', 1, \'' . $projects[$i]['Project']['customer'] . '\');';
            $project_link_style = null;
            if($projects[$i]['Project']['is_shipped'] == 1) {
                $project_link_style = array('class' => 'project_link');
            } else {
                $project_link_style = array('class' => 'project_link project_not_shipped');
            }
			echo 'data.setCell(' . $i . ', 2, \'' . $this->Html->link($projects[$i]['Project']['title'], array('action' => 'view', $projects[$i]['Project']['id']), $project_link_style);
			echo ' ' . $this->Link->linkA(Configure::read('AUTH_EDIT_DELETE_PROJECTS'), 'edit', array('action' => 'edit', $projects[$i]['Project']['id']));
			echo ' ' . $this->Link->linkA(Configure::read('AUTH_EDIT_DELETE_PROJECTS'), 'delete', array('action' => 'delete', $projects[$i]['Project']['id']), null, 'Are you sure you want to delete project ' . $projects[$i]['Project']['docket_year'] . '-' . $projects[$i]['Project']['docket_number'] . '?');
			echo ' ' . $this->Link->linkA(Configure::read('AUTH_EDIT_INVOICES'), 'invoice', array('action' => 'details', 'controller' => 'invoices', $projects[$i]['Project']['id']));
			echo '\');'; 

            if($this->Link->HasAuthority(Configure::read('AUTH_EDIT_DELETE_PROJECTS'))) {            
                // If the user has the ability to edit projects, make the shipped link clickable (i.e. able to be toggled).
                if($projects[$i]['Project']['is_shipped'] == 1) { 
                    echo 'data.setCell(' . $i . ', 3, \'1\',\'' . $this->Link->linkA(Configure::read('AUTH_EDIT_DELETE_PROJECTS'), '&diams;', array('action' => 'ship', $projects[$i]['Project']['id']), array('escape' => false)) . '\', {\'className\': \'project_shipped_link\'});';
                } else {
                    echo 'data.setCell(' . $i . ', 3, \'0\',\'' . $this->Link->linkA(Configure::read('AUTH_EDIT_DELETE_PROJECTS'), '&times;', array('action' => 'ship', $projects[$i]['Project']['id']), array('escape' => false)) . '\', {\'className\': \'project_shipped_link\'});';
                }
            } else {
                 // Otherwise, just display the shipping status.
                if($projects[$i]['Project']['is_shipped'] == 1) { 
                    echo 'data.setCell(' . $i . ', 3, \'1\',\'&diams;\', {\'className\': \'project_shipped_link\'});';
                } else {
                    echo 'data.setCell(' . $i . ', 3, \'0\',\'&times;\', {\'className\': \'project_shipped_link\'});';
                }
            }
	  }
	?> 

      var table = new google.visualization.Table(document.getElementById('table_div'));
      table.draw(data, {showRowNumber: false,
						alternatingRowStyle: true,
						allowHtml: true});
    }
</script>
<div class="grid_8">
	<h1>Projects <span><?php if($searchResults) { echo $this->Html->link('(see all)', array('action'=>'index')); } ?></span></h1>
</div>
<div class="grid_3">
	<?php
		echo $this->Form->create('Project');
		echo $this->Form->input('search', array('label' => false));
	?>
</div>
<div class="grid_1">
	<?php echo $this->Form->end('Search'); ?>
</div>
	<?php /*
		foreach ($projects as $project):
			echo $project['Project']['docket_year'] . '-' . $project['Project']['docket_number'] . ' ';
			echo $this->Html->link($project['Project']['title'], array('action' => 'view', $project['Project']['id']));
			echo '  ';
			echo $this->Html->link('edit', array('action' => 'edit', $project['Project']['id']));
			echo '  ';
			echo $this->Html->link('delete', array('action' => 'delete', $project['Project']['id']), null, 'Are you sure you want to delete project ' . $project['Project']['docket_year'] . '-' . $project['Project']['docket_number'] . '?');
			echo '<br/>';		
		endforeach;*/
	?>
<div class="clear"></div>
<div class="grid_12">
	<div id="table_div">
	</div>
</div>
