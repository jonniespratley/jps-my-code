<?php

class ExportXML
{
	private $mysqli;
	private $database;
	private $table;
	
	public function __construct( $link, $database, $table )
	{
		$this->setMysqli ( $link );
		$this->setTable ( $table );
		$this->setDatabase ( $database );
	}
	
	/**
	 * I export the database and tables to XML
	 *
	 * @return [xml]
	 */
	public function exportData()
	{
		/* Set the content type for the browser */
		header ( "Content-type: text/xml" );
		
		//table query
		$sql = mysqli_query ( $this->getMysqli (), "SELECT * FROM " . $this->getDatabase () . "." . $this->getTable () );
		
		echo "<" . $this->getDatabase () . ">";
		
		//loop all the results
		while ( $rows = mysqli_fetch_assoc ( $sql ) )
		{
			echo "<" . $this->getTable () . ">";
			//for each table in the result make an array
			foreach ( $rows as $key => $value )
			{
				echo "<$key>" . htmlspecialchars ( $value ) . "</$key>";
			}
			echo "</" . $this->getTable () . ">";
		}
		echo "</" . $this->getDatabase () . ">";
	}
	
	public function exportTableStructure()
	{
		/* Set the content type for the browser */
		header ( "Content-type: text/xml" );
		
		//table query
		$sql = mysqli_query ( $this->getMysqli (), "SELECT * FROM " . $this->getDatabase () . "." . $this->getTable () );
		
		echo "<" . $this->getDatabase () . ">";
		
		//loop all the results
		while ( $rows = mysqli_fetch_assoc ( $sql ) )
		{
			echo "<" . $this->getTable () . ">";
			//for each table in the result make an array
			foreach ( $rows as $key => $value )
			{
				echo "<$key>" . htmlspecialchars ( $value ) . "</$key>";
			}
			echo "</" . $this->getTable () . ">";
		}
		echo "</" . $this->getDatabase () . ">";
	}
	
	public function exportDatabaseStructure()
	{
		/* Set the content type for the browser */
		header ( "Content-type: text/xml" );
		
		//table query
		$sql = mysqli_query ( $this->getMysqli (), "SELECT * FROM " . $this->getDatabase () . "." . $this->getTable () );
		
		echo "<" . $this->getDatabase () . ">";
		
		//loop all the results
		while ( $rows = mysqli_fetch_assoc ( $sql ) )
		{
			echo "<" . $this->getTable () . ">";
			//for each table in the result make an array
			foreach ( $rows as $key => $value )
			{
				echo "<$key>" . htmlspecialchars ( $value ) . "</$key>";
			}
			echo "</" . $this->getTable () . ">";
		}
		echo "</" . $this->getDatabase () . ">";
	}
	
	/**
	 * @return link
	 */
	public function getDatabase()
	{
		return $this->database;
	}
	
	/**
	 * @return link
	 */
	public function getMysqli()
	{
		return $this->mysqli;
	}
	
	/**
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}
	
	/**
	 * @param string $database
	 */
	public function setDatabase( $database )
	{
		$this->database = $database;
	}
	
	/**
	 * @param link $mysqli
	 */
	public function setMysqli( $mysqli )
	{
		$this->mysqli = $mysqli;
	}
	
	/**
	 * @param string $table
	 */
	public function setTable( $table )
	{
		$this->table = $table;
	}

}
$link = new mysqli ( 'localhost', 'root', 'fred' );

$xml = new ExportXML ( $link, 'test', 'users' );
$xml->exportData ();

?>