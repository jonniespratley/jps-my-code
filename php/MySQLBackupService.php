<?php
/**
 * # Sequel Pro dump
# Version 254
# http://code.google.com/p/sequel-pro
#
# Host: localhost (MySQL 5.0.41)
# Database: test
# Generation Time: 2009-02-15 21:13:06 -0800
 ************************************************************

# Dump of table Tutorials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Tutorials`;

CREATE TABLE `Tutorials` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Tutorials` (`id`,`name`,`lastname`)
VALUES
	(2,'Jonnie','Spratley'),
	(3,'Steve','Patterson'),
	(4,'JonnieDollas','fds'),
	(13,'Lauren','Lauren'),
	(14,'Tyrrell','Tyrrell');



 * 
 *
 */
class MySQLBackupService
{
	private $link;
	private $compress = false;
	private $file = null;
	private $filename = null;
	private $isWritten = false;
	private $database;
	private $structure;
	
	public function __construct( $link, $compress = false )
	{
		//	error_reporting ( E_ERROR | E_USER_ERROR | E_PARSE );
		$this->setCompress ( $compress );
		$this->setLink ( $link );
	}
	
	/********************************
	 * GET TABLE STRUCTURE/DATA
	 ********************************/
	public function exportTableStructure( $database, $table )
	{
		$struct = '';
		
		$query = mysqli_query ( $this->link, "SHOW CREATE TABLE $database.$table" );
		$row = mysqli_fetch_row ( $query );
		
		$struct .= "DROP TABLE IF EXISTS `$table`; $row[1];";
		
		return $this->setStructure ( $struct );
	}
	
	public function exportTableData( $database, $table )
	{
		$result = $this->link->query ( "SELECT * FROM $database.$table" );
		$columns = $this->_getColumns ( $database, $table );
		$values = '';
		
		while ( $row = $result->fetch_assoc () )
		{
			foreach ( $row as $k => $v )
			{
				$values .= '(' . $v . '),';
			}
		}
		
		echo $values;
	}
	
	private function _getColumns( $database, $table )
	{
		$result = mysqli_query ( $this->link, "SHOW FIELDS FROM $database.$table" );
		
		while ( $row = $result->fetch_row () )
		{
			$columns .= $row [ 0 ];
		}
		
		echo $columns;
	}
	
	/********************************
	 * UTILITIES
	 ********************************/
	
	private function escapeQuery( $s )
	{
		if ( is_null ( $s ) )
		{
			return "NULL";
		}
		
		return "'" . $this->link->escape_string ( $s ) . "'";
	}
	
	/********************************
	 * GETTERS/SETTERS
	 ********************************/
	
	/**
	 * @return unknown
	 */
	public function getCompress()
	{
		return $this->compress;
	}
	
	/**
	 * @return unknown
	 */
	public function getFile()
	{
		return $this->file;
	}
	
	/**
	 * @return unknown
	 */
	public function getFilename()
	{
		return $this->filename;
	}
	
	/**
	 * @return unknown
	 */
	public function getDatabase()
	{
		return $this->database;
	}
	
	/**
	 * @param unknown_type $compress
	 */
	public function setCompress( $compress )
	{
		$this->compress = $compress;
	}
	
	/**
	 * @param unknown_type $file
	 */
	public function setFile( $file )
	{
		$this->file = $file;
	}
	
	/**
	 * @param unknown_type $filename
	 */
	public function setFilename( $filename )
	{
		$this->filename = $filename;
	}
	
	/**
	 * @param unknown_type $file
	 */
	public function setDatabase( $database )
	{
		$this->database = $database;
	}
	
	/**
	 * @return unknown
	 */
	public function getIsWritten()
	{
		return $this->isWritten;
	}
	
	/**
	 * @return unknown
	 */
	public function getLink()
	{
		return $this->link;
	}
	
	/**
	 * @return unknown
	 */
	public function getStructure()
	{
		return $this->structure;
	}
	
	/**
	 * @param unknown_type $isWritten
	 */
	public function setIsWritten( $isWritten )
	{
		$this->isWritten = $isWritten;
	}
	
	/**
	 * @param unknown_type $link
	 */
	public function setLink( $link )
	{
		$this->link = $link;
	}
	
	/**
	 * @param unknown_type $structure
	 */
	public function setStructure( $structure )
	{
		$this->structure = $structure;
	}

}

?>