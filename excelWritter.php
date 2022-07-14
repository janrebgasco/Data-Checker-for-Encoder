<?php
include "xlsx.php";
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 

$columnArray = array();
$XlsDataToImplode = "";
$correctedXls = "";
$correctedXlsArr = array();
$rem = array();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['excel']['tmp_name'])) {
	if(file_exists($_FILES['excel']['tmp_name']) && is_uploaded_file($_FILES['excel']['tmp_name']))
	{
		$excel = SimpleXLSX::parse($_FILES['excel']['tmp_name']);

		$sheet = $excel->rows(); # first sheet
		unset($xlsx); # free object memory when continuing to process large files

		$columns = extractColumns($sheet, [2]); # variable number and selection of columns
		for ($i=0; $i < sizeof($columns); $i++) 
		{
		    
		    if($i == 0){
		       	array_push($rem, "REMARKS");
		       	array_push($columnArray,"CORRECTED");
		    }
		    else{
    		    array_push($columnArray,$columns[$i][0]);
    			if (preg_match('/[\'^£$%&*()}{@#~?!;,|=_+¬]/', $columns[$i][0]))
    			{
    				array_push($rem, "Has Special Character");
    			}
    			else if(preg_match('~[0-9]+~', $columns[$i][0])){
    				array_push($rem, "Has Numeric Value");
    			}
    			else
    			{
    				array_push($rem, "No Remarks");
    			}
		    }
		}
		$XlsDataToImplode = implode("_", $columnArray); // making array to string
		$correctedXls = strtoupper(str_replace($invalidCharVal, "", $XlsDataToImplode)); // removes all invalid characterss and display in CORRECTED column
		$correctedXlsArr = explode("_", $correctedXls);
// 		print_r($columnArray);
		
		uploadExcel($correctedXlsArr,$rem);
		
	}else{
	    echo "<script type='text/javascript'>alert('Please upload excel file first');</script>";
	}
}
function extractColumns(array &$aInput, array $aColumns) 
{

		    /**
		        * Extract multiple array columns.
		        * Martin Latter, 06/05/17
		        *
		        * @param   array $aInput, input array
		        * @param   array $aColumns, array of integers corresponding to column index/position
		        * @return  indexed array
		    */

		    $aCols = [];

		    foreach ($aColumns as $i) {
		        $s = '$c' . $i;
		        $aCols[$s] = $i;
		    }

		    return array_map(function($aRow) use($aCols) {

		        $aOutput = [];

		        foreach ($aCols as $c) {
		            $aOutput[] = $aRow[$c];
		        }

		        return $aOutput;

		    }, $aInput);
}
function uploadExcel($correctedXlsArr,$rem)
{
    $target_dir = "xlsFiles/";
    $target_file = $target_dir . basename($_FILES['excel']['name']);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    
    // Check if image file is a actual image or fake image
    if (isset($_FILES['excel']['name'])) {
    
        if ($target_file == "xlsFiles/") {
            // console.log("cannot be empty");
            $uploadOk = 0;
        }  // Check file size
        else if ($_FILES['excel']['size'] > 5000000) {
            // console.log("Sorry, your file is too large.");
            $uploadOk = 0;
        } // Check if $uploadOk is set to 0 by an error
        else if ($uploadOk == 0) {
            // console.log("Sorry, your file was not uploaded.");
    
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES['excel']['tmp_name'], $target_file)) {
                // console.log("The file " . basename($_FILES['excel']['name']) . " has been uploaded.");
                editExcelFile($_FILES['excel']['name'],$correctedXlsArr,$rem);
            }
        }
    }
}
function editExcelFile($filename,$correctedXlsArr,$rem){
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load('xlsFiles/'.$filename);
     
        $sheet = $spreadsheet->getActiveSheet();
        
		for($i = 0; $i < count($correctedXlsArr); $i++)
        {
        	$sheet->setCellValueByColumnAndRow(4, $i+1, $correctedXlsArr[$i]);
        	$sheet->setCellValueByColumnAndRow(5, $i+1, $rem[$i]);
        }
        
        
        // Write an .xlsx file  
        $writer = new Xlsx($spreadsheet); 
          
        // Save .xlsx file to the files directory 
        $writer->save('xlsFiles/'.$filename); 
        
        $_SESSION["filename"] = $filename;
}

?>