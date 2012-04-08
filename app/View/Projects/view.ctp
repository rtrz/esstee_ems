<style tyle="text/css" media="print">
	/* Sets this page to print in landscape mode by default */
	<!--
	@page {
	  size: landscape;
	}
	-->
</style>

<!-- /app/View/Projects/view.ctp -->
<div class="grid_10 view">
	<h3><span class="nameLabel"><?php echo $project['Project']['customer']; ?></span>
	<br/>
	<i><?php echo $project['Project']['title']; ?></i></h3>
</div>
<div class="grid_2 view">
	<h3><span class="itemLabel"> <?php echo '20'. $project['Project']['docket_year'] . "-" . $project['Project']['docket_number']; ?></span></h3>
</div>
<div class="clear"></div>
<div class="grid_6 view">
	<span class="itemLabel">Customer PO:</span> <?php echo $project['Project']['customer_po']; ?>
</div>
<div class="grid_2 view">
	<span class="itemLabel">Date:</span><br/> <?php echo $project['Project']['date']; ?>
</div>
<div class="grid_2 view">
	<span class="itemLabel">Date Req'd:</span><br/><?php echo $project['Project']['date_required']; ?>
</div>
<div class="grid_2 view">
	<span class="itemLabel">Prev. Doc:</span> <?php echo $project['Project']['prev_docket_year'] . "-" . $project['Project']['prev_docket_number']; ?>
	<br/>
	<span class="itemLabel">Invoice #:</span> <?php echo $project['Project']['invoice_number']; ?>
</div>
<div class="clear"></div>
<!-- -->
<div class="grid_6 view">
	<table width="100%">
		<tr><td width="50%">
	<span class="itemLabel">Address:</span>
	<br/>
	<?php 
		echo nl2br($project['Project']['address']); 
		if(empty($project['Project']['address'])) {
			echo '<span class="empty_span">None.</span>';
		}
	?>
	
	</td><td width="50%">
	<span class="itemLabel">Ship To:</span>
	<br/>
	<?php 
		echo nl2br($project['Project']['shipping_address']); 
		if(empty($project['Project']['shipping_address'])) {
			echo '<span class="empty_span">None.</span>';
		}
	?>
	</td></tr></table>
	<table width="100%">
		<tr class="table_underline">
			<th>Qty.</th>
			<th>Description</th>
			<th>Invoice Amt.</th>
		</tr>
		<?php 
			for($i=0; $i<count($project['Unit']); $i++) {
				echo '<tr class="table_underline">';
				echo '<td width="15%">';
				echo $project['Unit'][$i]['quantity'];
				echo '</td><td>';
				echo nl2br($project['Unit'][$i]['description']);
				echo '</td>';
				echo '<td width="20%">';
			
				echo '</td>';
				echo '</tr>';
			}
		?>
		<tr>
			<td width="15%"></td>
			<th>Total</th>
			<td width="20%" class="table_underline"><br/></td>
		</tr>
	</table>
	<span class="itemLabel">Comments:</span><br/>
	<?php 
		echo nl2br($project['Project']['comments']); 
		if(empty($project['Project']['comments'])) {
			echo '<span class="empty_span">None.</span>';
		}
	?>
</div>
<div class="grid_6 view">
	<?php $aspects = array('Pre-Press','Stock','Press','Ink','Bindery','Shipping'); ?>
	<table>
		<tr class="table_underline">
			<td width="30%"></td>
			<td width="45%"><span class="itemLabel">Description</span></td>
			<td width="50%"><span class="itemLabel">Estimated</span></td>
			<td width="15%"><span class="itemLabel">Actual</span></td>
		</tr>
		<tr class="table_underline">
			<td>
				Pre-Press
			</td>
			<td>
				<?php echo nl2br($project['Aspect'][0]['description']); ?>
			</td>
			<td>
				<?php echo $project['Aspect'][0]['estimate_cost']; ?>	
			</td>
			<td>
				<?php echo $project['Aspect'][0]['actual_cost']; ?>
			</td>
		</tr>
		<tr class="table_underline">
			<td>
				Stock
			</td>
			<td>
				<?php echo nl2br($project['Aspect'][1]['description']); ?>
			</td>
			<td>
				<?php echo $project['Aspect'][1]['estimate_cost']; ?>	
			</td>
			<td>
				<?php echo $project['Aspect'][1]['actual_cost']; ?>
			</td>
		</tr>
		<tr class="table_underline">
			<td>
				Press
			</td>
			<td>
				<?php echo nl2br($project['Aspect'][2]['description']); ?>
			</td>
			<td>
				<?php echo $project['Aspect'][2]['estimate_cost']; ?>	
			</td>
			<td>
				<?php echo $project['Aspect'][2]['actual_cost']; ?>
			</td>
		</tr>
		<tr class="table_underline">
			<td>
				Ink
			</td>
			<td>
				<?php echo nl2br($project['Aspect'][3]['description']); ?>
			</td>
			<td>
				<?php echo $project['Aspect'][3]['estimate_cost']; ?>	
			</td>
			<td>
				<?php echo $project['Aspect'][3]['actual_cost']; ?>
			</td>
		</tr>
		<tr class="table_underline">
			<td>
				Bindery
			</td>
			<td>
				<?php echo nl2br($project['Aspect'][4]['description']); ?>
			</td>
			<td>
				<?php echo $project['Aspect'][4]['estimate_cost']; ?>	
			</td>
			<td>
				<?php echo $project['Aspect'][4]['actual_cost']; ?>
			</td>
		</tr>
		<tr class="table_underline">
			<td>
				Shipping
			</td>
			<td>
				<?php echo nl2br($project['Aspect'][5]['description']); ?>
			</td>
			<td>
				<?php echo $project['Aspect'][5]['estimate_cost']; ?>	
			</td>
			<td>
				<?php echo $project['Aspect'][5]['actual_cost']; ?>
			</td>
		</tr>
		<tr>
			<td></td>
			<th>Total</th>
			<td><?php echo $project['Project']['total_cost_estimate']; ?></td>
			<td><?php echo $project['Project']['total_cost_amount']; ?></td>
		</tr>
	</table>
	<table width="100%"><tr>
	<td>
		<div id="form_layout">
			Layout
		</div>
	</td>
	<td>
		<div id="write_container">
			Sheets Net<br/>
			<div id="write_box"></div>
			Sheets Gross<br/>
			<div id="write_box"></div>
			Press Count<br/>
			<div id="write_box"></div>
		</div>
	</td>
	</tr></table>
</div>
