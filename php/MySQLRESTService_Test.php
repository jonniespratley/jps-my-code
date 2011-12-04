<?php
require_once 'MySQLService.php';
require_once 'Utilities.php';
require_once 'MySQLHelpService.php';
require_once 'FileSystemService.php';
require_once 'JSONQuery.php';

$flexJSON = '[
{"table":"contacts","objectName":"name","objectValue":"Jonnie","database":"test","tableKey":"id","objectKey":"8"},
{"table":"contacts","objectName":"name","objectValue":"Sue","database":"test","tableKey":"id","objectKey":"9"},
{"table":"contacts","objectName":"name","objectValue":"Hannah","database":"test","tableKey":"id","objectKey":"10"},
{"table":"contacts","objectName":"name","objectValue":"Lauren","database":"test","tableKey":"id","objectKey":"11"},
{"table":"contacts","objectName":"name","objectValue":"John","database":"test","tableKey":"id","objectKey":"12"},
{"table":"contacts","objectName":"name","objectValue":"Rio","database":"test","tableKey":"id","objectKey":"13"},
{"table":"contacts","objectName":"name","objectValue":"Tyrrell","database":"test","tableKey":"id","objectKey":"14"}
]
';
$databaseName = 'test';
$tableName = 'contacts';
$tableQuery = "SELECT * FROM $databaseName.$tableName LIMIT 5";
$host = 'localhost';
$user = 'root';
$pass = 'fred';
$svc = new MySQLService ( $host, $user, $pass );
$util = new Utilities ();
$sql = "SELECT * FROM test.Tutorials";
$util->dump ( 'showSystemStatus', $svc->showSystemStatus() );
$util->dump ( 'showSystemVariables', $svc->showSystemVariables() );
$util->dump( '_describeTable', $svc->_describeTable( $databaseName, $tableName ) );
$util->dump( '_getSingleTableStatus', $svc->_getSingleTableStatus( $databaseName, $tableName ) );
$util->dump( '_getTableIndexes', $svc->_getTableIndexes( $databaseName, $tableName ) );
$util->dump( 'analyzeQuery', $svc->analyzeQuery( 'SELECT * FROM test.users LIMIT 10' ) );
$util->dump ( 'insertRecord', $svc->insertRecord ( $flexJSON ) );
$util->dump ( 'executeQuery', $svc->executeQuery ( $sql ) );
$util->dump ( 'pollTraffic', $svc->pollTraffic () );
$util->dump ( 'pollQueries', $svc->pollQueries () );
$util->dump ( 'pollConnections', $svc->pollConnections () );
$util->dump ( 'getDatabases', $svc->getDatabases () );
$util->dump ( 'getDatabaseSpace', $svc->getDatabaseSpace () );
$util->dump ( 'getUserInfo', $svc->getUserInfo ( $user ) );
$util->dump ( 'getTableRows', $svc->getTableRows ( $databaseName, $tableName ) );
$util->dump ( 'getOpenTables', $svc->getOpenTables ( $databaseName ) );
$util->dump ( 'getDatabaseBackups', $svc->getDatabaseBackups () );
$util->dump ( 'getDatabasesAndTables', $svc->getDatabasesAndTables () );
$util->dump ( 'getTables', $svc->getTables ( $databaseName ) );
$util->dump ( 'getTableColumns', $svc->getTableColumns ( $databaseName, $tableName ) );
$util->dump ( 'getTableIndex', $svc->getTableIndex ( $databaseName, $tableName ) );
$util->dump ( 'describeTable', $svc->describeTable ( $databaseName, $tableName ) );
$util->dump ( 'showTableStatus', $svc->showTableStatus ( $databaseName ) );
$util->dump ( 'analyzeTable', $svc->analyzeTable ( $databaseName, $tableName ) );
$util->dump ( 'checkTable', $svc->checkTable ( $databaseName, $tableName ) );
$util->dump ( 'optimizeTable', $svc->optimizeTable ( $databaseName, $tableName ) );
$util->dump ( 'repairTable', $svc->repairTable ( $databaseName, $tableName ) );
$util->dump ( 'showSystemStatus', $svc->showSystemStatus () );
$util->dump ( 'showSystemVariables', $svc->showSystemVariables () );
$util->dump ( 'showSystemUsers', $svc->showSystemUsers () );
$util->dump ( 'showSystemProcess', $svc->showSystemProcess () );
$util->dump ( 'showSystemPrivileges', $svc->showSystemPrivileges () );
$util->dump ( '_getBytes', $svc->_getBytes () );
$util->dump ( '_getUptime', $svc->_getUptime () );
$util->dump ( '_getClients', $svc->_getClients () );
$util->dump ( '_getCommands', $svc->_getCommands () );
$util->dump ( '_getConnections', $svc->_getConnections () );
$util->dump ( '_getInnoDb', $svc->_getInnoDb () );
$util->dump ( '_getKeys', $svc->_getKeys () );
$util->dump ( '_getOpen', $svc->_getOpen () );
$util->dump ( '_getPerformance', $svc->_getPerformance () );
$util->dump ( '_getQcache', $svc->_getQcache () );
$util->dump ( '_getQuestions', $svc->_getQuestions () );
$util->dump ( '_getReplication', $svc->_getReplication () );
$util->dump ( '_getServerInfo', $svc->_getServerInfo () );
$util->dump ( '_getShowCommands', $svc->_getShowCommands () );
$util->dump ( '_getSort', $svc->_getSort () );
$util->dump ( '_getTemp', $svc->_getTemp () );
$util->dump ( '_getThreads', $svc->_getThreads () );



?>