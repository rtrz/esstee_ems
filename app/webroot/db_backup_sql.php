<?php

ob_start();

// REVERT TO OLD AUTH FORMAT ----
//				|
//				V
if($_GET['auth'] != date(YYmmdd)) {
	header('Location: http://' . $_SERVER['SERVER_NAME']);
}

$filename = 'db-backup-'. date('Y-m-d') .'.sql';

header('Content-Type: text/sql; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);


/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	$return = '';
	$return .= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\n";
	$return .= "CREATE DATABASE `$name` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;\n";
	$return .= "USE `$name`;\n";
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		//$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$row2[1] = str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $row2[1]);
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = str_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	//write to file
	//echo $return; 
	
	$handle = fopen('php://output', 'w');
	fwrite($handle,$return);
}

backup_tables('localhost','root','3nt3rpr153','esstee');

ob_flush();

?>