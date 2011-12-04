<?php
require_once 'FileSystemService.php';
$fileSvc = new FileSystemService();
$list = 0;
if ( isset ( $_GET[ 'm' ] ) )
{
	$mode = $_GET['m'];
}

if ( isset ( $_GET[ 'f' ] ) )
{
	$file = $_GET['f'];
}

if ( isset ( $_GET[ 'l' ] ) )
{
	$list = $_GET['l'];
}

switch ( $mode )
{
	
	case 'getDirectory' :
		$directory = $fileSvc->browseDirectory( $_GET['dir'], $list, true );
		echo  json_encode( $directory );
	break;
	
	case 'readFile' :
		$ignore = array ( 'swf', 'fla', 'zip', 'ai', 'swc', 'psd');
		$ext = substr( strrchr( $file, '.' ), 1 );
		if ( ! in_array ( $ext, $ignore ) )
		{
			$file = $fileSvc->readFile( $file );
			
			echo $file;
			
		} else {
			
			echo 'Download the File to View';
		}
	break;

	case 'downloadFile':
		echo $file;
	break;
	
	default:
		echo json_encode( array( 'message' => 'Please choose a mode.' ) );
	exit ();
}
?>