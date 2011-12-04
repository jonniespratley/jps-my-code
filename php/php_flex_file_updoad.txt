<?php
/** *******************************************************************
 * Working File Upload Server Script for Flex
 *
 * MySnippets
 * Free for use
 *
 * @author  Jonnie Spratley
 * @contact jonniespratley@gmail.com
 ******************************************************************* */
if (isset( $_POST['FileType']) && isset($_FILES['Filedata']) ) 
{

/* Establish a connection to database */
$link = mysql_connect('localhost', 'spratley_guest', 'guest') or die('Could not connect: ' . mysql_error());

/* Select the database */
mysql_select_db( 'spratley_tutorials', $link ) or die ( mysql_error() );

/* Set the upload directory */
$upload_dir = "uploads/";

/* PHP temp_name variable for file upload */
$temp_name = $_FILES['Filedata']['tmp_name'];

/* PHP file_name variable sent from Flex */
$file_name = $_FILES['Filedata']['name'];
 
/* PHP file_size variable sent from Flex */
$file_size = $_FILES['Filedata']['size'];

/* PHP file_type variable sent from Flex */
$file_type = $_FILES['Filedata']['type'];

/* PHP file_ext variable set from Flex */
$file_ext = $_FILES['Filedata']['extension'];

/* Set the file_url to the hosturl and the updload directory and filename */
$file_url = $_SERVER['HTTP_HOST'] . $upload_dir . $file_name;

/* Replace any computer garbage  */
$file_name = str_replace("\\","",$file_name);

/* Replace any garbage */
$file_name = str_replace("'","",$file_name);

/* Set up the filepath */
$file_path = $upload_dir.$file_name;


/* Insert info into fle_uploads table in your mysql database */
$insert = "INSERT INTO flex_uploads ( file_name, file_size, file_type, file_ext, file_url ) 
		   VALUES ( '$file_name', 
					'$file_size', 
					'$file_type', 
					'$file_ext',
					'$file_url' )";

/* Execute the query */				  
$query = mysql_query($insert) or die(mysql_error());

/* Get the last insert id */
$lastid = mysql_insert_id();

/* Move the uploaded file */
$result  =  move_uploaded_file( $temp_name, $file_path );

/* Set the content type for the browser */
header("Content-type: text/xml");

/* Set the header xml version */
$xml_output = "<?xml version=\"1.0\"?>\n";

/* Set the root node for the xml */
$xml_output .= "<results>\n";

/* If file was moved and inserted */
if ( $result ) 
{
	/* Build the XML if file was successful */			     
	/* Set the child node */
    $xml_output .= "\t<result>\n";
    
    /* status */
    $xml_output .= "\t\t<status>" . "Successful" . "</status>\n";
    
    /* file_name variable */
    $xml_output .= "\t\t<name>" . $file_name . "</name>\n";
    
    /* file_size variable */
	$xml_output .= "\t\t<size>" . $file_size . "</size>\n";
	
	/* file_type variable */
	$xml_output .= "\t\t<type>" . $file_type . "</type>\n";
	
	/* file_url variable */
	$xml_output .= "\t\t<url>" . $file_url . "</url>\n";
    
    /* Close the child result now and print the child result node out */
    $xml_output .= "\t</result>\n";

}
    
} else {

	/* Set the content type for the browser */
	header("Content-type: text/xml");
	
	/* Set the header xml version */
	$xml_output = "<?xml version=\"1.0\"?>\n";
	
	/* Set the root node for the xml */
	$xml_output .= "<results>\n";

	/* Build the XML if file was un-successful */			     
	/* Set the child node */
    $xml_output .= "\t<result>\n";
    
    /* status */
    $xml_output .= "\t\t<status>" . "Error" . "</status>\n";
    
    /* file_name variable */
    $xml_output .= "\t\t<name>" . $file_name . "</name>\n";
    
    /* file_name variable */
    $xml_output .= "\t\t<message>" . "There was a problem uploading your file." . "</message>\n";

    
    /* Close the child result now and print the child result node out */
    $xml_output .= "\t</result>\n";
				
}

/* Close the parent results node */
$xml_output .= "</results>";

/* Output all of the xml */
echo $xml_output; 



?> 