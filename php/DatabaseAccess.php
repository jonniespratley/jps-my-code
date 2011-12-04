<?php

class DatabaseAccess
{
	 private $service;
    private $svcResultFormat;
    private $svcDatabase;
    private $svcTable;
	private $result = null;
	private $fault = null;
	public $format = '';
	private $dsn = '';
	private $dbh = null;

	public function __construct($dbType = 'mysql', $dbHost = null, $dbPort = null, $dbUser = null, $dbPass = null)
	{
		/**
		 $sqlitedsn = 'sqlite2:js-projects.db';
		 $dsn = 'mysql:host=localhost;dbname=spratley_eventmanager;';
		 $user = 'root';
		 $password = 'fred';
		 */

		if ($dbType == 'mysql')
		{
			$this->dsn = 'mysql:host='.$dbHost.'';
			$this->dsn .= $dbPort != null ? ':'.$dbPort.';' : ';';
		}
		else if ($dbType == 'sqlite')
		{
			$this->dsn = 'sqlite2:'.$dbName.'.db';
		}

		try {
			$this->dbh = new PDO( $this->dsn, $dbUser, $dbPass );
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e)
		{
			echo 'Connection failed: '.$e->getMessage();
			die();
		}


	}
	
	public function execute( $sql )
	{
		
		try{
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute();
			$result = array();	
			$records = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
			{
				$records[] = $row;
			}
			$result = array('status' => 'success', 'response' => $records);
			
		} catch(PDOException $e){
			$result = array( 
						'status' => 'error', 
						'message' => $e->getMessage() );
		}
		return $result;
	}
	
	

	public function getDrivers()
	{
		return PDO::getAvailableDrivers();
	}
}


/* *************************
 * Testing
 * ************************ */
echo '<pre>';
$sql = 'SELECT * FROM test.posts limit 5';
#$sql = 'SELECT * FROM test.tas limit 25';

$svc = new DatabaseAccess('mysql', 'localhost', null, 'root', 'fred');

$getData = $svc->execute($sql);
echo json_encode($getData);

echo '</pre>';

?>
