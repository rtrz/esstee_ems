<div class="prefix_4 grid_4 suffix_4">
<?php
	echo $this->Form->create('User');
	echo $this->Form->input('username');
	echo $this->Form->input('password');

	$radio_options = array(	'511'=>'Administrator<br />',
							'17'=> 'Reception: Read-only dockets and invoices<br />',
							'7' => 'User+: Read and add dockets, but no editing<br />',
							'3' => 'User: Read dockets<br />' );

	echo $this->Form->radio('authority', $radio_options, array('legend' => ''));
	
	echo $this->Form->end('Add User');
?>
</div>