<?php
class Project extends AppModel {
	var $name = 'Project';
	var $hasMany = array('Unit','Aspect');
	var $hasOne = 'Invoice';
}
?>