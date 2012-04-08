<?php 
	function comma(&$text) {
		$text .= ",";
	}
	
	function clean($text) {
		$ret = str_replace(',', ' ', $text); //remove commas
		$ret = str_replace("\n", ' ', $ret); //remove new lines ('\n')
		$ret = str_replace("\r\n", " ", $ret);
		$ret = str_replace("\\n", ' ', $ret);
		return $ret;
	}
	
	//$filename = 'db-backup-'. date('Y-m-d_H:i:s') .'.csv';	//with time of day
	
	$filename = 'db-backup-'. date('Y-m-d') .'.csv';			//without time of day
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=' . $filename);
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$contents = "Customer, Job Title, Docket Year, Docket #, Customer PO, Date, Date Required, Prev. Docket Year, Prev. Docket #, Invoice #, Units of Work, Pre-Press, Stock, Press, Ink, Bindery, Shipping, Comments";
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
		foreach ($project['Unit'] as $unit):
			$contents .= '(' . $unit['quantity'] . ') ' . clean($unit['description']) . ' | ';
		endforeach;
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
	
	echo $contents;
	
	$handle = fopen('php://output', 'w+');
	fwrite($handle,$contents);
	
?>