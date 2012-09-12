<?php
/*---- CHANGE DATABSE COIFGURATION HERE ----*/
define('HOSTNAME','localhost');
define('USERNAME','root');
define('PASSWORD','');
define('DATABASENAME','WorkBoardStreamer');

// change the path for saving xml data here
$path = "recordings";

//conncting to databse
if(!$con = mysql_connect(HOSTNAME,USERNAME,PASSWORD)){
	die("Error in databse connection");
}

//selecting the databse
if(!$db = mysql_select_db(DATABASENAME,$con)){
	die("Error in databse selection");
}

//checking for presentaion details
if(isset($_POST['USERID']) && isset($_POST['XMLNAME'])){
	
	$user_id = $_POST['USERID'];
	$xml_name = $_POST['XMLNAME'];
	$date_created = date('Y-m-d');
	$complete_filename = $path."/".$xml_name.".xml";
	
	//saving to databse
	$table_name = "presentation_details";
	$sql_string = "INSERT INTO ".$table_name."(user_id,xml_name,date_created)";
	$sql_string .=" VALUES('".$user_id."','".$xml_name."','".$date_created."')";
	//echo $sql_string;
	if(mysql_query($sql_string,$con)){
		if (!file_exists($complete_filename)) {
			create_xml_data($xml_name);
		}
		echo "success";
	}
	else{
		echo "error";
	}
	
}
else{
	echo "post not set";	
}
//closing databse connection
mysql_close($con);


//create_xml_data("554e687b5ecbf827bd05401cb2a7883b");

/**
* creates a xml file in a direcory named recordings
* if you want to change the diirectory change the varible named path
* @param string file_name name of the file
* @return null
*/
function create_xml_data($file_name){
	global $path;
	$xml = new DOMDocument('1.0', 'utf-8');	
	$root = $xml->createElement("Presentation");
		
		$child_level1 = $xml->createElement("Documents");
		$root->appendChild($child_level1);
		
		$child_level1 = $xml->createElement("MainVideo");
		$root->appendChild($child_level1);
		
		$child_level1 = $xml->createElement("InsiteVideo");
		$root->appendChild($child_level1);
		
		$child_level1 = $xml->createElement("Questions");
		$root->appendChild($child_level1);
		
	$xml->appendChild($root);
	
	//converting the xml object into a string
	$xml->formatOutput = true;
	$xml_string = $xml->saveXML();
	echo "<xmp>".$xml_string."</xmp>";
	//saving to file
	$xml->save($path."/".$file_name.".xml");
}
?>
