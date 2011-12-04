<?php
require_once 'data/TableInfo.php';
require_once 'data/TableStatus.php';
require_once 'data/RelationType.php';
require_once 'data/RelationInfo.php';
require_once 'data/RelationColumnInfo.php';
class Connection
{
	
public $connection;

    /**
     * I hold methods to alter a mysql database
     *
     * @param [string] $host
     * @param [string] $username
     * @param [string] $password
     */
    public function __construct($host, $username, $password)
    {
        $link = new mysqli($host, $username, $password);

        /* check connection */
        if (mysqli_connect_errno())
        {
            trigger_error('Database connection failure: Username/Password was incorrect.', E_USER_ERROR);
            exit ();
        }
        else
        {
            $this->setMysqli($link);
        }
    }

    /**
     * I execute a query
     *
     * @param [string] $sql
     * @return [array]
     */
    public function execute($sql)
    {
        return $this->queryToARRAY($sql);
    }

    /**
     * I get the databases
     *
     * @return [array]
     */
    public function getDatabases()
    {
        return $this->queryToARRAY("SHOW DATABASES");
    }
	
	public function getTables( $database )
    {
        $collection = array();
   
        $result = $this->realQuery( "SHOW TABLES FROM $database" );
        
        while ( $row = mysqli_fetch_row( $result ) )
        {
            $collection[ ] = new TableInfo( $row[ 0 ], $database );
        }
        
        return $collection;
    }

    public function getTableStatus( $database )
    {
        $collection = array();
   
        $result = $this->realQuery( "SHOW TABLE STATUS FROM $database" );
        
        while ( $row = mysqli_fetch_assoc( $result ) )
        {
            $collection[ ] = new TableStatus( $database, $row );
        }
        
        return $collection;
    }
    
    public function getRelations( $database, $table, $relationType )
    {
        $parent_relation = "
		    select
		        constraint_name,
		        referenced_table_name as related_table_name,
		        referenced_column_name,
		        column_name
		    from information_schema.key_column_usage
		    where table_schema = '" . $database . "' and table_name = '" . $table . "' and referenced_table_schema is not null;";
        
        $child_relation = "
	        select
	            constraint_name,
	            table_name as related_table_name,
	            referenced_column_name,
	            column_name
	        from information_schema.key_column_usage
	        where table_schema = '" . $database . "' and referenced_table_name = '" . $table . "' and referenced_table_schema is not null;";
        
        $relations = array();
        
        $query = $relationType == RelationType::Parent ? $parent_relation : $child_relation;

         $result = $this->realQuery( $query );
               
        $fkMap = array();
        
        while ( $row = mysqli_fetch_row( $result ) )
        {
            $fk = $row[ 0 ];
            
            if ( ! array_key_exists( $fk, $fkMap ) )
            {
                $fkMap[ $row[ 0 ] ] = new RelationInfo( $table, $fk, $relationType );
                
                $fkMap[ $fk ]->RelatedTableName = $row[ 1 ];
                
                $relations[ ] = $fkMap[ $fk ];
            }
            
            $fkMap[ $fk ]->Columns[ ] = new RelationColumnInfo( $row[ 3 ], $row[ 2 ] );
        }
        
        return $relations;
     
    }
    
	public function getForeignKey( $database, $table, $column )
    {
        $query = "
            select referenced_table_schema, referenced_table_name,
                    referenced_column_name
            from information_schema.key_column_usage
            where table_schema = '" . $database . "' and table_name = '" . $table . "' and column_name = '" . $column . "' and referenced_table_schema is not null;";
        
        $result = $this->realQuery( $query );
        
        while ( $row = mysqli_fetch_row( $result ) )
        {
            $foreignKeyData = new ForeignKeyData( );
            $foreignKeyData->database = $row[ 0 ];
            $foreignKeyData->table = $row[ 1 ];
            $foreignKeyData->column = $row[ 2 ];

            return $foreignKeyData;
        }
        
        return null;
    }
    
    /**
     * I execute a raw query
     *
     * @param [string] $query
     * @return [link]
     */
    public function realQuery($query)
    {
        return $this->mysqli->query($query);
    }
	 
	/**
     * I execute a query and return the results as json.
     *
     * @param [string] $sql the query to be executed
     * @return [json] the result in json
     */
    private function queryToJSON($sql)
    {
        $result = $this->realQuery($sql);

        while ($row = mysqli_fetch_assoc($result))
        {
            $array[] = $row;
        }
        return json_encode($array);
    }

    /**
     * I execute a query and return the result as an array.
     *
     * @param [string] $sql the query to be executed
     * @return [array] the result array
     */
    public function queryToARRAY($sql)
    {
        $query = $this->realQuery($sql);
        $array = array ();

        while ($row = mysqli_fetch_assoc($query))
        {
            $array[] = $row;
        }

        return $array;
    }

    /**
     * I dump vars
     *
     * @param [string] $title the title of the dump
     * @param [var] $var the var
     */
    public function dump($title, $var)
    {
        print "<h4>$title</h4>";
        print "<pre>";
        print_r($var);
        print "</pre>";
    }

    /**
     * @return [link]
     */
    public function getMysqli()
    {
        return $this->mysqli;
    }

    /**
     * @param [link] $connection
     */
    public function setMysqli($connection)
    {
        $this->mysqli = $connection;
    }
}



?>