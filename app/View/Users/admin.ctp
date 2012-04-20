<?php echo $this->Html->script('https://www.google.com/jsapi'); ?>
<script type="text/javascript">
	google.load('visualization', '1', {packages:['table']});
    google.setOnLoadCallback(drawTable);
    function drawTable() {
      var data = new google.visualization.DataTable();
 	
	  data.addColumn('string', 'User name');
	  data.addColumn('string', 'Authority');
	  data.addColumn('string', 'Actions');
	
 	  data.addRows(<?php echo count($users); ?>);
	
	<?php for($i=0; $i < count($users); $i++) {		
		 	echo 'data.setCell(' . $i . ', 0, \'' . $users[$i]['User']['username'] . '\');';
		
			$authority = null;
			switch($users[$i]['User']['authority']) {
				case 511:
					$authority = 'Administrator';
					break;
				case 17:
					$authority = 'Reception';
					break;
				case 7:
					$authority = 'User+';
					break;
				case 3:
					$authority = 'User';
					break;
			}
			
			echo 'data.setCell(' . $i . ', 1, \'' . $authority . '\');';
			
			$actions = $this->Html->link('edit', array('action' => 'edit', $users[$i]['User']['id']));
			$actions .= $this->Html->link('delete', array('action' => 'delete', $users[$i]['User']['id']), null, 'Are you sure you want to delete this user?');
			
			echo 'data.setCell(' . $i . ', 2, \'' . $actions . '\');';
	  }
	?> 

      var table = new google.visualization.Table(document.getElementById('table_div'));
      table.draw(data, {showRowNumber: false,
						alternatingRowStyle: true,
						allowHtml: true});
    }
</script>
<div class="grid_8">
	<h1>User Account Management</h1>
</div>
<div class="clear"></div>
<div class="grid_4">
	<?php echo $this->Link->linkA(Configure::read('AUTH_ADD_USERS'), 'Add User', array('action' => 'add')); ?>
</div>
<div class="grid_4 suffix_4">
	<div id="table_div"></div>
</div>