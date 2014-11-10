
<?php

include("includes/connect.php");

function createURL($ticker){ //$ticker = symbol of the company

	$currentMonth = date("n");
	$currentMonth = $currentMonth - 1; // january is 0 like array

	$currentDay = date("j");
	$currentYear = date("Y");

	return "http://real-chart.finance.yahoo.com/table.csv?s=$ticker&d=$currentMonth&e=$currentDay&f=$currentYear&g=d&a=2&b=27&c=2011&ignore=.csv";

}

function getCSVFile($url, $outputFile){ // $url = from where i download, $outputFile = to where i save it

	$content = file_get_contents($url);

	$content = str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", $content)
	$content = trim($content);

	//saving the string to a file

	file_put_contents($outputFile, $content); // txt file
}

function fileToDatabase($txtFile, $tableName){
	
	$file = fopen($txtFile, "r"); // refering to the file	

	while(!feof($file)){ 		//loop through till the end of the file
		
		$line = fgets($file);	//takes a line of text and returns it as a string

		$pieces = explode(",", $line); //breaking each line into pieces


		$date = $pieces[0];
		$open = $pieces[1];
		$high = $pieces[2];
		$low = $pieces[3];
		$close = $pieces[4];
		$volume = $pieces[5];

		$amount_change = $close-$open;
		$percent_change = ($amount_change/$open) * 100;

		//check if a table exists

		$sql = $db->prepare("SELECT * FROM :table");
		$result = $sql->execute(array('table'=>$tableName));


		if(!$result){
			
			$sql_create_table = $db->prepare("CREATE TABLE :tablename ( date DATE, PRIMARY KEY(date), open FLOAT, high FLOAT, low FLOAT, close FLOAT, volume INT, amount_change FLOAT, percent_change FLOAT ) ");
			$sql_create_table->execute(array("tablename"=>$tableName));

		}else{

		}






	} 




}





?>