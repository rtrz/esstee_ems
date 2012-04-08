<div class="prefix_4 grid_4 suffix_4" id="login">
<?php
	echo $this->Form->create('User', array('action' => 'login'));
	echo $this->Form->input('User.username');
	echo $this->Form->input('User.password');
	echo $this->Form->end('Go');
?>
</div>