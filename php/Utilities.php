<?php
  
class Utilities
{
	
	public function __construct()
	{
	
	}
	
	public function toCamelCase( $in )
	{	
		$ret = str_replace ( ' ', '_', ucwords ( preg_replace ( '/[^A-Z^a-z^0-9]+/', ' ', $in ) ) );
		if ( $ret == "Default" )
		{
			$ret = "DefaultOption";
		}
		return ( $ret );
		
	//return $in;
	}
	
	public function toCamelCaseMember( $in )
	{
		$ret = ( strtolower ( substr ( $in, 0, 1 ) ) . substr ( $this->toCamelCase ( $in ), 1, strlen ( $in ) - 1 ) );
		if ( $ret == "default" )
		{
			$ret = "defaultOption";
		}
		return ( $ret );
		
	//return $in;
	}
	
	/**
	 * pre: tablename corresponds to table with only 2 foreign keys and only 2
	 * columns
	 */
	public function addMappingTable( $table, $tablename )
	{
		$table1 = $table [ $tablename ] [ 0 ] [ 2 ];
		$table2 = $table [ $tablename ] [ 1 ] [ 2 ];
		
	// fixme: add hibernate stuff here
	}
	
	public function findReferencesToColumn( $tableName, $columnName )
	{
		$ret = array (); // array containing referncing columns
		foreach ( con::$tables as $refTablename => $refColumns )
		{
			foreach ( $refColumns as $k => $refColumn )
			{
				if ( array_key_exists ( 2, $refColumn ) && $refColumn [ 2 ] [ 'referenced_column_name' ] != null && $refColumn [ 2 ] [ 'referenced_table_name' ] != null && strtolower ( $refColumn [ 2 ] [ 'referenced_column_name' ] ) == strtolower ( $columnName ) && strtolower ( $refColumn [ 2 ] [ 'referenced_table_name' ] ) == strtolower ( $tableName ) )
				{
					array_push ( $ret, $refColumn [ 2 ] );
				}
			}
		}
		return ( $ret );
	}
	
	/**
	 * Copy a file, or recursively copy a folder and its contents
	 * @param       string   $source    Source path
	 * @param       string   $dest      Destination path
	 * @return      bool     Returns TRUE on success, FALSE on failure
	 */
	public function copyr( $source, $dest, $skipHidden = false, $overwrite = true )
	{
		// Simple copy for a file
		if ( is_file ( $source ) )
		{
			if ( ( ! $overwrite ) && is_file ( $dest ) )
			{
				return;
			}
			return copy ( $source, $dest );
		}
		
		// Make destination directory
		if ( ! is_dir ( $dest ) )
		{
			mkdir ( $dest );
		}
		
		// Loop through the folder
		$dir = dir ( $source );
		while ( false !== $entry = $dir->read () )
		{
			// Skip pointers
			if ( $entry == '.' || $entry == '..' )
			{
				continue;
			}
			// Skip hidden
			if ( $skipHidden && strtolower ( substr ( $entry, 0, 1 ) ) == '.' )
			{
				continue;
			}
			
			// Deep copy directories
			if ( $dest !== "$source/$entry" )
			{
				copyr ( "$source/$entry", "$dest/$entry", $skipHidden, $overwrite );
			}
		}
		
		// Clean up
		$dir->close ();
		return true;
	}
	
	public function printNestedArray( $array )
	{
		echo '<ul>';
		
		foreach ( $array as $key => $value )
		{
			echo '<li>' . htmlspecialchars ( "$key: " );
			
			if ( is_array ( $value ) )
			{
				$this->printNestedArray ( $value );
			}
			else
			{
				echo htmlspecialchars ( "$value " ) . '</li>';
			}
		}
		echo '</ul>';
	}
	
	public function findAllKeys( $array )
	{
		$this->dump ( 'Array Keys', array_keys ( $array ) );
		
		foreach ( $array as $value )
		{
			if ( is_array ( $value ) )
			{
				$this->findAllKeys ( $value );
			}
		}
	}
	
	/**
	 * I log data to a file
	 *
	 * @param [string] $title
	 * @param [variable] $var
	 */
	public function log( $title = '', $var )
	{
		//write to log file
		$file = "log.txt";
		
		//$date = date('n/j/y  g:i a  ');
		$date = DATE_W3C;
		
		//append to end of content in log fil
		$fp = fopen( $file, "a+" ); 
		
		//make sure file is writable
		chmod( "log.txt", 0777 ); 
		
		$contents = $date . $var;
		
		fwrite( $fp, $contents, 1024 );
		 
		fclose( $fp );
	}
	
	public function readLog()
	{
		$file = "log.txt";
		
		$print = file_get_contents( $file );
		echo '<pre>';
		print_r( $print );
		echo '</pre>';
	}
	 
	public function dump( $title, $var )
	{	
		echo "<h3>$title</h3>";
		echo '<pre>';
		print_r($var);
		echo '<pre>';
	}
}

?>