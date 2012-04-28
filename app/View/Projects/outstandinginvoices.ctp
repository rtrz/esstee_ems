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
	  data.addColumn('number', 'Elapsed Days');
 	  data.addRows(<?php echo count($projects); ?>);
	
	<?php for($i=0; $i < count($projects); $i++) {		
		 	echo 'data.setCell(' . $i . ', 0, ' . $projects[$i]['Project']['docket_number'] . ', \'' . $projects[$i]['Project']['docket_year'] . '-' . $projects[$i]['Project']['docket_number'] . '\');';
		    
			echo 'data.setCell(' . $i . ', 1, \'' . $projects[$i]['Project']['customer'] . '\');';
		
			$urgency_style = null;
			if(!$projects[$i]['Invoice']['is_billed'] && $projects[$i]['Invoice']['days_elapsed'] > 30) {
				$urgency_style = array('class' => 'invoice_overdue');
			}
			echo 'data.setCell(' . $i . ', 2, \'' . $this->Html->link($projects[$i]['Project']['title'], array('action' => 'view', $projects[$i]['Project']['id']), $urgency_style);
			
			echo ' ' . $this->Link->linkA(Configure::read('AUTH_EDIT_DELETE_PROJECTS'), 'edit', array('action' => 'edit', $projects[$i]['Project']['id']), array('class' => 'project_sublink'));
			
			echo ' ' . $this->Link->linkA(Configure::read('AUTH_EDIT_DELETE_PROJECTS'), 'delete', array('action' => 'delete', $projects[$i]['Project']['id']), array('class' => 'project_sublink'), 'Are you sure you want to delete project ' . $projects[$i]['Project']['docket_year'] . '-' . $projects[$i]['Project']['docket_number'] . '?');
			
			echo ' ' . $this->Link->linkA(Configure::read('AUTH_EDIT_INVOICES'), 'invoice', array('action' => 'details', 'controller' => 'invoices', $projects[$i]['Project']['id']), array('class' => 'project_sublink'));
			echo '\');'; 
			
			
			if($projects[$i]['Invoice']['is_billed'] == 1) { 
				echo 'data.setCell(' . $i . ', 3, true);';
			} else {
				echo 'data.setCell(' . $i . ', 3, false);';
			}
			
			echo 'data.setCell(' . $i . ',4, ' . $projects[$i]['Invoice']['days_elapsed'] . ');';
	  }
	?> 

      var table = new google.visualization.Table(document.getElementById('table_div'));
      table.draw(data, {showRowNumber: false,
						alternatingRowStyle: true,
						allowHtml: true});
    }
</script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Category');
        data.addColumn('number', 'Quantity');
        data.addRows([
          ['Billed', <?php echo $under30billed; ?>],
          ['Not Billed', <?php echo $under30notbilled; ?>]
        ]);

        var options = {
          	title: '30 Days or Less',
			legend: {position: 'bottom'},
			is3D: true,
			chartArea: {width:"100%",height:"85%"}
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div_lteq30'));
        chart.draw(data, options);
      }
</script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart2);
      function drawChart2() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Category');
        data.addColumn('number', 'Quantity');
        data.addRows([
          ['Billed', <?php echo $over30billed; ?>],
          ['Not Billed', <?php echo $over30notbilled; ?>]
        ]);

        var options = {
          	title: 'Over 30 Days',
		  	legend: {position: 'bottom'},
			is3D: true,
			chartArea: {width:"100%",height:"85%"}
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div_gt30'));
        chart.draw(data, options);
      }
</script>
<div class="grid_12">
	<h1>Invoices 
		<span>
		<?php 
		if($instruction == 'all') {
			echo $this->Html->link('unbilled', array('action'=>'outstandinginvoices'));
			echo ' / show all';
		} else {
			echo 'unbilled / ';
			echo $this->Html->link('show all', array('action'=>'outstandinginvoices', 'all'));
		}
		?>
		</span>
	</h1>
</div>
<div class="clear"></div>
<div class="grid_6">
	<div id="chart_div_lteq30"></div>
</div>
<div class="grid_6">
	<div id="chart_div_gt30"></div>
</div>
<div class="clear"></div>
<div class="grid_12">
	<br/>
	<div id="table_div"></div>
</div>