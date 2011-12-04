<?php
require_once 'Utilities.php';
require_once 'data/DatabaseInfo.php';
require_once 'data/TableStatus.php';
require_once 'data/ColumnInfo.php';
require_once 'data/ServerInfo.php';
/**
 * I hold mysql methods
 *
 * @name MySQLService
 * @author  Jonnie Spratley
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
class MySQLConnect {
	public $mysqli;
	
	/**
	 * I hold methods to alter a mysql database
	 *
	 * @param [string] $host
	 * @param [string] $username
	 * @param [string] $password
	 */
	public function __construct($host, $username, $password) {
		
		$link = new mysqli ( $host, $username, $password );
		
		/* check connection */
		if (mysqli_connect_errno ()) {
			trigger_error ( 'Database connection failure: Username/Password was incorrect.', E_USER_ERROR );
			exit ();
		} else {
			$this->setMysqli ( $link );
		}
	}
	
	/**
	 * I execute a query
	 *
	 * @param [string] $sql
	 * @return [array]
	 */
	public function execute($sql) {
		return $this->queryToARRAY ( $sql );
	}
	
	/**
	 * I execute a raw query
	 *
	 * @param [string] $query
	 * @return [link]
	 */
	public function realQuery($query) {
		return $this->mysqli->query ( $query );
	}
	
	public function getServer() {
		$mysqlVersion = mysqli_get_client_info ( $this->mysqli );
		$serverInfo = $_SERVER ['HTTP_HOST'] . " (MySQL v. $mysqlVersion )";
		$server = new ServerInfo ( $serverInfo, $this->getDatabases () );
		return $server;
	}
	
	/**
	 * I get the databases
	 *
	 * @return [array]
	 */
	public function getDatabases() {
		$result = $this->realQuery ( "SHOW DATABASES" );
		$databaseArray = array ();
		while ( $row = mysqli_fetch_row ( $result ) ) {
			$databaseArray [] = new DatabaseInfo ( $row [0], $this->getTables ( $row [0] ) );
		}
		return $databaseArray;
	}
	
	public function getTables($database) {
		$result = $this->realQuery ( "SHOW TABLE STATUS FROM $database" );
		$tableArray = array ();
		
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			foreach ( $row as $key => $value ) {
				echo $key;
				if (array_key_exists ( 'Name', $row )) {
					$columns = $this->getColumns ( $database, $value );
				}
			}
			
			$tableArray [] = new TableStatus ( $database, $row, $columns );
		}
		return $tableArray;
	}
	
	public function getColumns($database, $table) {
		$result = $this->realQuery ( "SHOW FIELDS FROM $database.$table" );
		$columnArray = array ();
		
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$columnArray [] = new ColumnInfo ( $row );
		}
		return $columnArray;
	}
	
	/**
	 * I start the tree
	 *
	 * @return [array]
	 */
	public function tree_getTree() {
		$mysqlVersion = mysqli_get_client_info ( $this->mysqli );
		$host = $_SERVER ['HTTP_HOST'] . " (MySQL v. $mysqlVersion )";
		
		$hostArray = array ('label' => $host, 'type' => 'server', 'children' => $this->tree_getSchemas () );
		$treeArray [] = array ('label' => 'SQL Databases', 'type' => 'servers', 'children' => $hostArray );
		
		return $treeArray;
	}
	
	/**
	 * I build the tree
	 *
	 * @return [array]
	 */
	private function tree_getSchemas() {
		//Database query
		$databaseSQL = $this->realQuery ( "SHOW DATABASES" );
		
		//New database array
		$databases = array ();
		
		//Loop the query
		while ( $database = mysqli_fetch_assoc ( $databaseSQL ) ) {
			//Create a new array of tables for each database
			$tables = array ();
			$status = array ();
			$size = array ();
			
			foreach ( $database as $key => $value ) {
				//Set the table array to get the tbles from the database
				$tables = $this->tree_db_getTables ( $value );
				$status = $this->_db_getStatus ( $value );
				$size = $this->_db_getSize ( $value );
			}
			
			//Add the tables to the database array
			$databases [] = array ("aDatabase" => $value, "aData" => $key, "aType" => "database", "aIcon" => "database", "aStatus" => $status, "aSize" => $size, "aTables" => $tables );
		}
		
		$databaseFolder [] = array ('label' => 'Schemas', 'children' => $databases );
		
		return $databaseFolder;
	}
	
	/**
	 * I get the users auth
	 *
	 * @return [array]
	 */
	private function tree_db_getAuthorizations() {
		$authorizationsArray = array ('label' => 'Authorization IDs', 'children' => array ('label' => 'rfd' ) );
		
		return $authorizationsArray;
	}
	
	//TODO:
	private function tree_db_getDependcenies($database) {
		$dependceniesArray = array ('label' => 'Dependcencies', 'children' => array ('label' => 'test' ) );
		
		return $dependceniesArray;
	}
	
	//TODO:
	private function tree_db_getStoredProcs($database) {
		$storedProcsArray = array ('label' => 'Stored Procedures', 'children' => array ('label' => 'test' ) );
		
		return $storedProcsArray;
	}
	
	/**
	 * I get the tables
	 *
	 * @param [string] $database the database
	 * @return [array]
	 */
	private function tree_db_getTables($database) {
		//table query
		$tableSQL = $this->realQuery ( "SHOW TABLES FROM $database" );
		
		//create a new array of tables
		$tables = array ();
		
		//loop all the results
		while ( $table = mysqli_fetch_assoc ( $tableSQL ) ) {
			$columns = array ();
			$statuss = array ();
			$indexes = array ();
			$constraints = array ();
			$dependicies = array ();
			$triggers = array ();
			
			//for each table in the result make an array
			foreach ( $table as $t_key => $t_value ) {
				//get the tables fields for each table
				$columns = $this->tree_tbl_getColumns ( $database, $t_value );
				
				//now get the primary key for each table
				$constraints = $this->tree_tbl_getConstraints ( $database, $t_value );
				
				//now get the indexes for each table
				$indexes = $this->tree_tbl_getIndexes ( $database, $t_value );
				
				//now get the dependencys for each table
				$dependicies = $this->tree_tbl_getDependcenies ( $database, $t_value );
				
				//now get the triggers for each table
				$triggers = $this->tree_tbl_getTriggers ( $database, $t_value );
				
				//now get the status for each table
				$statuss = $this->_tbl_getStatus ( $database, $t_value );
			
			}
			
			$columnArr = $columns;
			$constraintArr = $constraints;
			$indexArr = $indexes;
			$dependencyArr = $dependicies;
			$triggerArr = $triggers;
			$statusArr = $statuss;
			$tables [] = array ("label" => $t_value, "type" => "table", "icon" => "table", "children" => array ($columnArr, $constraintArr, $indexArr, $dependencyArr, $triggerArr, $statusArr ) );
		}
		
		$tableFolder [] = array ('label' => 'Tables', 'children' => $tables );
		
		return $tableFolder;
	}
	
	//TODO:
	private function tree_db_getUserFunctions($database) {
	
	}
	
	//TODO:
	private function tree_db_getViews($database) {
	
	}
	
	/**
	 * I get the columns
	 *
	 * @param [string] $database
	 * @param [string] $table
	 * @return [array]
	 */
	private function tree_tbl_getColumns($database, $table) {
		$sql = "SHOW FIELDS FROM $database.$table";
		$query = $this->realQuery ( $sql );
		
		$columnsArray = array ();
		
		while ( $row = mysqli_fetch_row ( $query ) ) {
			$type = strtoupper ( $row [1] );
			$null = '';
			
			//Check if the column can be null
			if ($row [2] == 'YES') {
				$null = 'Nullable';
			}
			$type = '[' . $type . ' ' . $null . ']';
			
			$columnsArray [] = array ('label' => $row [0] . ' ' . $type );
		}
		//Create the folder
		$columnsFolder = array ('label' => 'Columns', 'children' => $columnsArray );
		
		return $columnsFolder;
	}
	
	/**
	 * I get the primary keys
	 *
	 * @param [string] $database
	 * @param [string] $table
	 * @return [array]
	 */
	private function tree_tbl_getConstraints($database, $table) {
		$sql = "SHOW INDEX FROM $database.$table";
		$result = $this->realQuery ( $sql );
		$constraintArray = array ();
		
		while ( $constraint = mysqli_fetch_assoc ( $result ) ) {
			//check if the key is the primary key
			if ($constraint ['Key_name'] == 'PRIMARY') {
				$constraintArray = array ('label' => $constraint ['Key_name'] );
			}
		}
		$constraintFolder = array ('label' => 'Constraints', 'children' => array ($constraintArray ) );
		
		return $constraintFolder;
	}
	
	//TODO:
	/**
	 * I get the dependcencies
	 *
	 * @param [string] $database
	 * @param [string] $table
	 * @return [array]
	 */
	private function tree_tbl_getDependcenies($database, $table) {
		$dependArray = array ('label' => 'admin table' );
		
		$dependFolder = array ('label' => 'Dependencies', 'children' => array ($dependArray ) );
		
		return $dependFolder;
	}
	
	/**
	 * I get the indexes
	 *
	 * @param [string] $database
	 * @param [string] $table
	 * @return [array]
	 */
	private function tree_tbl_getIndexes($database, $table) {
		$sql = "SHOW INDEX FROM $database.$table";
		$query = mysqli_query ( $this->mysqli, $sql );
		
		$indexArray = array ();
		
		while ( $row = mysqli_fetch_row ( $query ) ) {
			if ($row [2] !== 'PRIMARY') {
				$indexArray [] = array ('label' => $row [4] . "($row[2])" );
			}
		}
		
		$indexFolder = array ('label' => 'Indexes', 'children' => $indexArray );
		
		return $indexFolder;
	}
	
	//TODO:
	/**
	 * I get the triggers
	 *
	 * @param [string] $database
	 * @param [string] $table
	 * @return [array]
	 */
	private function tree_tbl_getTriggers($database, $table) {
		$triggerArray = $this->queryToARRAY ( "SHOW INDEX FROM $database.$table" );
		
		$triggerFolder = array ('label' => 'Triggers', 'children' => array ($triggerArray ) );
		
		return $triggerFolder;
	}
	
	/**
	 * I get the table status
	 *
	 * @param [string] $database
	 * @param [string] $table
	 * @return [array]
	 */
	private function _tbl_getStatus($database, $table) {
		return $this->queryToARRAY ( "SHOW TABLE STATUS FROM $database LIKE '$table'" );
	}
	
	/**
	 * I get the size of all the databases
	 *
	 * @param [string] $database the database
	 * @return [array]
	 */
	private function _db_getSize($database) {
		$statusSQL = $this->realQuery ( "SHOW TABLE STATUS FROM $database" );
		$sizeArray = array ();
		
		$totalSize = 0;
		$dataSize = 0;
		$indexSize = 0;
		
		//loop all the results
		while ( $size = mysqli_fetch_assoc ( $statusSQL ) ) {
			$dataSize += $size ['Data_length'];
			$indexSize += $size ['Index_length'];
		}
		$totalSize = $dataSize + $indexSize;
		$sizeArray [] = array ('totalSize' => $totalSize, 'dataSize' => $dataSize, 'indexSize' => $indexSize );
		
		return $sizeArray;
	}
	
	/**
	 * I get the status of the all the tables for a database.
	 *
	 * @param [string] $database the database
	 * @return [array]
	 */
	private function _db_getStatus($database) {
		return $this->queryToARRAY ( "SHOW TABLE STATUS FROM $database" );
	}
	
	/**
	 * I execute a query and return the results as json.
	 *
	 * @param [string] $sql the query to be executed
	 * @return [json] the result in json
	 */
	private function queryToJSON($sql) {
		$result = $this->realQuery ( $sql );
		
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$array [] = $row;
		}
		return json_encode ( $array );
	}
	
	/**
	 * I execute a query and return the result as an array.
	 *
	 * @param [string] $sql the query to be executed
	 * @return [array] the result array
	 */
	public function queryToARRAY($sql) {
		
		$query = $this->realQuery ( $sql );
		$array = array ();
		
		while ( $row = mysqli_fetch_assoc ( $query ) ) {
			$array [] = $row;
		}
		
		return $array;
	}
	
	/**
	 * I get the query status
	 *
	 * @param [string] $sql
	 * @return [json] mysql status with the ('_') striped out
	 */
	public function queryStatusToJSON($sql) {
		$result = $this->realQuery ( $sql );
		
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			//replace some of the names
			$row = str_replace ( 'Com_', '', $row );
			//take out the _ of the rows
			$row = str_replace ( '_', ' ', $row );
			
			$array [] = $row;
		}
		sort ( $array );
		
		return json_encode ( $array );
	}
	
	/**
	 * I dump vars
	 *
	 * @param [string] $title the title of the dump
	 * @param [var] $var the var
	 */
	public function dump($title, $var) {
		print "<h4>$title</h4>";
		print "<pre>";
		print_r ( $var );
		print "</pre>";
	}
	
	/**
	 * @return [link]
	 */
	public function getMysqli() {
		return $this->mysqli;
	}
	
	/**
	 * @param [link] $mysqli
	 */
	public function setMysqli($mysqli) {
		$this->mysqli = $mysqli;
	}

}
?>
