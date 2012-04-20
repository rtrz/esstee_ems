<div class="prefix_4 grid_4 suffix_4">
<?php
	echo $this->Form->create('User');
	echo $this->Form->input('username', array('readonly' => true));
	echo $this->Form->input('password', array('label' => 'Set New Password'));
	
	echo $this->Form->end('Save Changes');
	
	echo $this->Html->link('Delete User', array('action' => 'delete', $user_id));
?>
</div>