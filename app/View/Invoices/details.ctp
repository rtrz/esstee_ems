<br/>
<div class="grid_10">
	<h3>
	<?php 
		if($project['Project']['invoice_number'] != '') {
			// An invoice number has been provided
			echo 'Invoice #' . $project['Project']['invoice_number'];
		} else {
			// No invoice number available
			echo 'Invoice';
		}
	?>
	</h3>
</div>
<div class ="grid_2">
	<h3>
		<?php echo '20' . $project['Project']['docket_year'] . "-" . $project['Project']['docket_number']; ?>	
	</h3>
</div>
<div class="clear"></div>

<?php echo $this->Form->create('Invoice'); ?>
<div class="grid_2 invoice">
	<?php echo $this->Form->input('is_billed', array('label' => 'Has been billed?')); ?>
</div>
<div class="grid_4 invoice">
	<?php echo $this->Form->input('billing_date'); ?>
</div>
<div class="grid_6 invoice">
	<?php echo $this->Form->end('Save Changes'); ?>
	<br/>
	<?php echo $this->Html->link('Go to Project', array('controller' => 'projects', 'action' => 'edit', $project['Invoice']['project_id'])); ?>
</div>

