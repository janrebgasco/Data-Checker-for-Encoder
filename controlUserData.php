<?php 
	$dataToArr = "";
	$dataToImplode = "";
	$corrected = "";
	$correctedArr = "";
	$dispRemarks = "";
	$remarks = array();
	$correctedArr = array();
	$invalidCharVal = array('?','[',']','+','=','"',';',"'",'!', '@', '#', '$', '%', '^', '&', '*', '(', ')','1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
	

	if (isset($_POST['checker1'])) 
	{
		$encode = $_POST['checker1'];
		$dataToArr = explode(PHP_EOL,$encode); //it makes the data input as array
		$dataToImplode = implode("_", $dataToArr); // making array to string
		$corrected = strtoupper(str_replace($invalidCharVal, "", $dataToImplode)); // removes all invalid characterss and display in CORRECTED column
		$correctedArr = explode("_", $corrected);
        
		for ($i=0; $i < sizeof($dataToArr); $i++) 
		{ 
			if (preg_match('/[\'^£$%&*()}{@#~?!;,|=_+¬]/', $dataToArr[$i]))
			{
				array_push($remarks, "Has Special Character");
			}
			else if(preg_match('~[0-9]+~', $dataToArr[$i])){
				array_push($remarks, "Has Numeric Value");
			}
			else
			{
				array_push($remarks, "No Remarks");
			}
		}
		$dispRemarks = implode("<br/>", $remarks); //display the remarks of names
	}
	
?>
