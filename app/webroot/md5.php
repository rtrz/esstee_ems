<?php
	// navigate to this page to get an md5 hash
	// $SCRIPT_LOCATION/md5.php?pass=<the string
	if(isset($_GET['str'])) {
		echo md5($_GET['str']);
	}
?>