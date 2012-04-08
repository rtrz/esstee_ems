<!-- File: /app/View/Projects/index.ctp -->
<?php echo $this->Html->script('https://www.google.com/jsapi'); ?>
<script type="text/javascript">
	google.load('visualization', '1', {packages:['table']});
    google.setOnLoadCallback(drawTable);
    function drawTable() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Docket #');
	  data.addColumn('string', 'Customer');
      data.addColumn('string', 'Description');
	  data.addColumn('boolean', 'Invoiced');
 	  data.addRows(<?php echo count($projects); ?>);
	
	<?php for($i=0; $i < count($projects); $i++) {		
		 	echo 'data.setCell(' . $i . ', 0, ' . $projects[$i]['Project']['docket_number'] . ', \'' . $projects[$i]['Project']['docket_year'] . '-' . $projects[$i]['Project']['docket_number'] . '\');';
		    echo 'data.setCell(' . $i . ', 1, \'' . $projects[$i]['Project']['customer'] . '\');';
			echo 'data.setCell(' . $i . ', 2, \'' . $this->Html->link($projects[$i]['Project']['title'], array('action' => 'view', $projects[$i]['Project']['id']), array('class' => 'project_link'));
			echo ' ' . $this->Html->link('edit', array('action' => 'edit', $projects[$i]['Project']['id']));
			echo ' ' . $this->Html->link('delete', array('action' => 'delete', $projects[$i]['Project']['id']), null, 'Are you sure you want to delete project ' . $projects[$i]['Project']['docket_year'] . '-' . $projects[$i]['Project']['docket_number'] . '?');
			echo ' ' . $this->Html->link('invoice', array('action' => 'details', 'controller' => 'invoices', $projects[$i]['Project']['id']));
			echo '\');'; 
			
			if($projects[$i]['Invoice']['is_billed'] == 1) { 
				echo 'data.setCell(' . $i . ', 3, true);';
			} else {
				echo 'data.setCell(' . $i . ', 3, false);';
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