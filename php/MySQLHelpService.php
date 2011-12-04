<?php

class MySQLHelpService
{
	private $mysqli;
	
	public function __construct( $host, $username, $password )
	{
		//temporary for the bs warning signs on live
		// Report simple running errors
		error_reporting ( E_ERROR | E_USER_ERROR | E_PARSE );
		
		$this->mysqli = new mysqli ( $host, $username, $password );
		/* check connection */
		if ( mysqli_connect_errno () )
		{
			trigger_error ( 'Database connection failure: Username/Password was incorrect.', E_USER_ERROR );
			exit ();
		}
		else
		{
			return $this->mysqli;
		}
	}
	
	public function getHelpTree()
	{
		$helpTree [] = array ( 
			'label' => 'MySQL Help', 'children' => $this->_getAllHelp () 
		);
		
		return json_encode ( $helpTree );
	}
	
	public function _getAllHelp()
	{
		$sql = "SELECT 
				help_category.name as category, 
				help_topic.name as label, 
				help_topic.description as description, 
				help_topic.example as example
				FROM mysql.help_relation 
					INNER JOIN mysql.help_topic 
						ON help_relation.help_topic_id = help_topic.help_topic_id
					INNER JOIN mysql.help_category 
						ON help_category.help_category_id = help_topic.help_category_id
				ORDER BY category ASC";
		
		return $this->_queryToARRAY ( $sql );
	}
	
	public function _getDataDefinition()
	{
		$sql = "SELECT 
help_keyword.name as keyword, 
help_category.name as category, 
help_topic.name as topic, 
help_topic.description as description, 
help_topic.example as example
FROM mysql.help_relation 
	INNER JOIN mysql.help_keyword 
		ON help_relation.help_keyword_id = help_keyword.help_keyword_id
	INNER JOIN mysql.help_topic 
		ON help_relation.help_topic_id = help_topic.help_topic_id
	INNER JOIN mysql.help_category 
		ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Data Definition'
ORDER BY topic ASC";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getDataManipulation()
	{
		$sql = "SELECT 
help_keyword.name as keyword, 
help_category.name as category, 
help_topic.name as topic, 
help_topic.description as description, 
help_topic.example as example
FROM mysql.help_relation 
	INNER JOIN mysql.help_keyword 
		ON help_relation.help_keyword_id = help_keyword.help_keyword_id
	INNER JOIN mysql.help_topic 
		ON help_relation.help_topic_id = help_topic.help_topic_id
	INNER JOIN mysql.help_category 
		ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Data Manipulation'
ORDER BY topic ASC";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getTableMaintenance()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Table Maintenance'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getStoredRoutines()
	{
		$sql = "";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getAdministration()
	{
		$sql = "SELECT 
		help_keyword.name as keyword, 
		help_category.name as category, 
		help_topic.name as topic, 
		help_topic.description as description, 
		help_topic.example as example
		FROM mysql.help_relation 
			INNER JOIN mysql.help_keyword 
				ON help_relation.help_keyword_id = help_keyword.help_keyword_id
			INNER JOIN mysql.help_topic 
				ON help_relation.help_topic_id = help_topic.help_topic_id
			INNER JOIN mysql.help_category 
				ON help_category.help_category_id = help_topic.help_category_id
		WHERE help_category.name = 'Administration'
		ORDER BY topic ASC";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getDataTypes()
	{
		$sql = "SELECT 
		help_keyword.name as keyword, 
		help_category.name as category, 
		help_topic.name as topic, 
		help_topic.description as description, 
		help_topic.example as example
		FROM mysql.help_relation 
			INNER JOIN mysql.help_keyword 
				ON help_relation.help_keyword_id = help_keyword.help_keyword_id
			INNER JOIN mysql.help_topic 
				ON help_relation.help_topic_id = help_topic.help_topic_id
			INNER JOIN mysql.help_category 
				ON help_category.help_category_id = help_topic.help_category_id
		WHERE help_category.name = 'Data Types'
		ORDER BY topic ASC";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getAccountManagement()
	{
		$sql = "SELECT 
help_keyword.name as keyword, 
help_category.name as category, 
help_topic.name as topic, 
help_topic.description as description, 
help_topic.example as example
FROM mysql.help_relation 
	INNER JOIN mysql.help_keyword 
		ON help_relation.help_keyword_id = help_keyword.help_keyword_id
	INNER JOIN mysql.help_topic 
		ON help_relation.help_topic_id = help_topic.help_topic_id
	INNER JOIN mysql.help_category 
		ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Account Management'
ORDER BY topic ASC";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getStringFunctions()
	{
		$sql = "SELECT 
help_keyword.name as keyword, 
help_category.name as category, 
help_topic.name as topic, 
help_topic.description as description, 
help_topic.example as example
FROM mysql.help_relation 
	INNER JOIN mysql.help_keyword 
		ON help_relation.help_keyword_id = help_keyword.help_keyword_id
	INNER JOIN mysql.help_topic 
		ON help_relation.help_topic_id = help_topic.help_topic_id
	INNER JOIN mysql.help_category 
		ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'String Functions'
ORDER BY topic ASC";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getControlFlow()
	{
		$sql = " SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Control flow functions'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _gettTransactions()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Transactions'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getFunctions()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Functions'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getWKT()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'WKB'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getWKB()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'WKB'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getNumericFunctions()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Numeric Functions'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getLanguageStructure()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Language Structure'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getComparison()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Comparison operators'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getDateTime()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Date and Time Functions'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getLogicalOpperators()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Logical operators'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getEncryptionFunctions()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Encryption Functions'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getGeographicFeatures()
	{
		$sql = "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Geographic Features'";
		return $this->_queryToArray ( $sql );
	}
	
	public function _getTriggers()
	{
		$sql - "SELECT help_keyword.name, 	help_category.name, help_topic.name, help_topic.description, help_topic.example
FROM mysql.help_relation 
INNER JOIN mysql.help_keyword 
ON help_relation.help_keyword_id = help_keyword.help_keyword_id
INNER JOIN mysql.help_topic 
ON help_relation.help_topic_id = help_topic.help_topic_id
INNER JOIN mysql.help_category 
ON help_category.help_category_id = help_topic.help_category_id
WHERE help_category.name = 'Triggers'";
		return $this->_queryToArray ( $sql );
	}
	
	/* ********************************************************************
* ********************************************************************
* 
* 						8. RESULT HANDLERS
* 
* ********************************************************************
* ********************************************************************/
	
	/**
	 * I execute a query and return the results as json.
	 *
	 * @param [string] $sql the query to be executed
	 * @return [json] the result in json
	 */
	private function _queryToJSON( $sql )
	{
		$result = mysqli_query ( $this->mysqli, $sql );
		
		while ( $row = mysqli_fetch_assoc ( $result ) )
		{
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
	private function _queryToARRAY( $sql )
	{
		$query = mysqli_query ( $this->mysqli, $sql );
		$array = array ();
		
		while ( $row = mysqli_fetch_assoc ( $query ) )
		{
			$array [] = $row;
		}
		
		return $array;
	}

}

//testing
//$help = new MySQLHelpService( 'localhost', 'root', 'fred' );
//print_r( $help->getHelpTree() );


?>