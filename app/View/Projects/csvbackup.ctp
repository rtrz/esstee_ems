<?php 
	function comma(&$text) {
		$text .= ",";
	}
	
	function clean($text) {
		$ret = str_replace(',', ' ', $text); //remove commas
		$ret = str_replace(array("\r\n", "\r", "\n"), null, $ret);
		$ret = str_replace("\\n", ' ', $ret);
		$ret = str_replace(PHP_EOL, ' ', $ret);
		return $ret;
	}
	
	//$filename = 'db-backup-'. date('Y-m-d_H:i:s') .'.csv';	//with time of day
	
	$filename = 'db-backup-'. date('Y-m-d') .'.csv';			//without time of day
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=' . $filename);
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$contents = 'Customer,';
	$contents .= 'Job Title,';
	$contents .= 'Docket Year,';
	$contents .= 'Docket #,';
	$contents .= 'Customer PO,';
	$contents .= 'Date,';
	$contents .= 'Date Required,';
	$contents .= 'Prev. Docket Year,';
	$contents .= 'Prev. Docket #,';
	$contents .= 'Invoice #,';
	$contents .= 'Units of Work,';
	$contents .= 'Pre-Press,';
	$contents .= 'Stock,';
	$contents .= 'Press,';
	$contents .= 'Ink,';
	$contents .= 'Bindery,';
	$contents .= 'Shipping,';
	$contents .= 'Comments';
	$contents .= "\n";
	//$contents .= "<br/>";
	
	foreach($projects as $project):
		
		$p = $project['Project'];
		
		//add core docket items
		$contents .= clean($p['customer']) . ',';		//Customer
		$contents .= clean($p['title']) . ',';			//Job Title
		$contents .= clean($p['docket_year']) . ',';	//Docket Year
		$contents .= clean($p['docket_number']) . ',';	//Docket Number
		$contents .= clean($p['customer_po']) . ',';	//Customer PO
		$contents .= clean($p['date']) . ',';			//Date
		$contents .= clean($p['date_required']) . ',';	//Date Required
		$contents .= clean($p['prev_docket_year']) . ',';		//Prev Docket Year
		$contents .= clean($p['prev_docket_number']) . ',';	//Prev Docket Number
		$contents .= clean($p['invoice_number']) . ',';	//Invoice Number
		
		//now add units of work
		for($i=0; $i<count($project['Unit']); $i++) {
		//foreach ($project['Unit'] as $unit):
			//$contents .= '(' . $unit['quantity'] . ') ' . clean($unit['description']) . ' | ';
			if($i > 0) {
				$contents .= ' | ';
			}
			$contents .= '(' . $project['Unit'][$i]['quantity'] . ") " . clean($project['Unit'][$i]['description']);
		}
		//endforeach;
		$contents .= ',';
		
		//now add aspects
		$a = $project['Aspect'];
		
		for($i=0; $i < 6; $i++) {
			$contents .= clean($a[$i]['description']) . ',';
		}
		
		$contents .= clean($p['comments']);	//Finally, add comments
		
		$contents .= "\n";
		//$contents .= '<br/>';
		
	endforeach;
	
	//echo $contents;
	
	$handle = fopen('php://output', 'w+');
	fwrite($handle,$contents);
	
?>