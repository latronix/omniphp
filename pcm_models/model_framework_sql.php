<?php
/**
 * [MODEL]
 * Filename: model_framework_sql.php
 * 
 * Database Abstraction Layer (eventually Object Relational Mapper).
 *
 * @todo I must recreate these libraries into a full-blown ORM, including a 'generator'/'scaffolder'.
 * @todo Add abstraction for Oracle, Postgre, and DB2. Ignore MonetDB.
 * 
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.3.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

define("RDBMS_MYSQL", "mysql"); //mysqli actually, there will be no implementation for the older procedural mysql as mysqli is a superior library.
define("RDBMS_PHPSQLDRV", "phpsqldrv"); //Microsoft's PHP SQL Driver. This is the official library for SQL Server created by Microsoft.
define("RDBMS_MSSQL", "mssql"); //old mssql implementation; that required ntwdblib.dll. Deprecated, use phpsqldrv instead.
/* TODO: add support for these RDBMS's
define("RDBMS_ORACLE", "oracle");
define("RDBMS_DB2", "db2");
define("RDBMS_SYBASE", "sybase");
define("RDBMS_POSTGRE", "postgre");
define("RDBMS_MONETDB", "monetdb");
others...?
*/

class Framework_SQL
{
	//===================
	// MEMBERS
	//===================
        private $mysqli;
	private $sqllink; //for MS SQL and the MS PHP SQL Driver and/or MySQL (older not mysqli)
	private $rdbms;
	private $bErrNotify;
	private $lastAffectedRows;
	private $lastError;
    
	//===================
	// METHODS
	//===================
	/*
	@method
	Constructor
	
	@desc
	Creates RDBMS server connection, instanciates objects and nullifies variables.
	
	@param bKillOnFail [bool]
	DEFAULT: TRUE
	kill (die/exit) the application if connection to the SQL RDBMS fails or important queries
	fail.
	
	@param bShowErr [bool]
	DEFAULT: FALSE
	show errors, should only be true for debugging purposes, production releases should always
	be set to false.
	
	@param rdbms [string (SEE CONSTANTS ABOVE)]
	the database engien to use (see above for available options), defaults to MySQL (mysqli library).
	*/
        /**
         * method: Constructor
         * Creates RDBMS server connection, instances objects and nullifies variables.
         * 
         * @param bool $bKillOnFail Set to true to kill entire script (die/exit) on fatal errors.
         * @param bool $bShowErr Set to true to display fatal errors (note: only do this for debugging, NEVER for production)
         * @param string $rdbms Set to the RDBMS engine to use [RDBMS_MYSQL (mysqli), RDBMS_PHPSQLDRV (ms new php driver), RDBMS_MSSQL (old mssql)]
         */
   function __construct($bKillOnFail = true, $bShowErr = false, $rdbms = RDBMS_MYSQL)
   {
       global $MySqlHost;
	   global $MySqlUser;
	   global $MySqlPass;
	   global $MySqlDB;
	   
	   global $SqlHost;
	   global $SqlUser;
	   global $SqlPass;
	   global $SqlDB;
	   
       $this->mysqli = NULL;
       $this->sqllink = NULL;
	   
	   $this->bErrNotify = $bShowErr;
	   $this->rdbms = $rdbms;
	   
	   $this->lastAffectedRows = NULL;
	   $this->lastError = NULL;
	   
       switch($this->rdbms)
       {
           case RDBMS_MYSQL:
           {
				$this->mysqli = new mysqli($MySqlHost, $MySqlUser, $MySqlPass, $MySqlDB);
				if(mysqli_connect_errno())
				{
				   if($bShowErr)
				   {
					   $errMsg = "Connection to the \"" . $MySqlDB . "\" database failed on host \"" . $MySqlHost . "\"<br />" . PHP_EOL;
					   $errMsg .= "Error #" . mysqli_connect_errno() . ": " . mysqli_connect_error() . "<br />" . PHP_EOL;
				   }
				   else
				   {
					   $errMsg = "Connection failed, please try again later.<br />" . PHP_EOL;
				   }
				   if($bKillOnFail)
				   {
				       die($errMsg);
				   }
				   else
				   {
				       echo $errMsg;
				   }
				}
           }
           break;
		   case RDBMS_PHPSQLDRV:
		   {
				$connectionInfo = array("Database" => $SqlDB, "UID" => $SqlUser, "PWD" => $SqlPass, "ReturnDatesAsStrings" => true);
				$this->sqllink = sqlsrv_connect($SqlHost, $connectionInfo);
				if($this->sqllink === false)
				{
				   if($bShowErr)
				   {
					   $errMsg = "Connection to the \"" . $SqlDB . "\" database failed on host \"" . $SqlHost . "\"<br />" . PHP_EOL;
					   $errMsg .= "Error:<br /><pre>" . print_r(sqlsrv_errors(), true) . "</pre><br />" . PHP_EOL;
				   }
				   else
				   {
					   $errMsg = "Connection failed, please try again later.<br />" . PHP_EOL;
				   }
				   if($bKillOnFail)
				   {
				       die($errMsg);
				   }
				   else
				   {
				       echo $errMsg;
				   }
				}
		   }
		   break;
           case RDBMS_MSSQL: //old MS SQL, outdated
           {
				$this->sqllink = mssql_connect($SqlHost, $SqlUser, $SqlPass);
				$bDB = mssql_select_db($SqlDB);
				if((!$this->sqllink) || (!$bDB))
				{
				   if($bShowErr)
				   {
					   $errMsg = "Connection to the \"" . $SqlDB . "\" database failed on host \"" . $SqlHost . "\"<br />" . PHP_EOL;
					   $errMsg .= "Error: " . mssql_get_last_message() . "<br />" . PHP_EOL;
				   }
				   else
				   {
					   $errMsg = "Connection failed, please try again later.<br />" . PHP_EOL;
				   }
				   if($bKillOnFail)
				   {
				       die($errMsg);
				   }
				   else
				   {
				       echo $errMsg;
				   }
				}
           }
           break;
       }
   }
   
	/*
	@method
	Destructor
	
	@desc
	Destroys the RDBMS server connection and frees memory.
	*/
   function __destruct()
   {
       switch($this->rdbms)
       {
           case RDBMS_MYSQL:
           {
			   if($this->mysqli)
			   {
					$this->mysqli->close();
					$this->mysqli = NULL;
			   }
           }
           break;
		   case RDBMS_PHPSQLDRV:
		   {
			   if($this->sqllink)
			   {
				   sqlsrv_close($this->sqllink);
				   $this->sqllink = NULL;
			   }
		   }
		   break;
           case RDBMS_MSSQL: //old MS SQL, outdated
           {
			   if($this->sqllink)
			   {
				   mssql_close($this->sqllink);
				   $this->sqllink = NULL;
			   }
           }
           break;
       }
	   
	   $this->bErrNotify = NULL;
	   $this->rdbms = NULL;
	   $this->lastAffectedRows = NULL;
	   $this->lastError = NULL;
   }
   
	/*
	@method
	execute_query(...)
	
	@desc
	Performs a SELECT query with the given parameters (where clause) and return in the desired format;
	either as an HTML table, an object/array, or a counter of returned/retrieved rows.
	
	@param returnFormat [string]
	DEFAULT: HTML
	"HTML" = retrieves an HTML table (similar to a datagrid format) to be displayed.
	"OBJECT" = retrieves an object/array of all retrieved data to be handled via PHP code.
	"COUNTER" = retrieves a count (number of rows) of the executed query.
	"DATAGRID" = retrieves an HTML table with edit (SQL UPDATE) links for each record, the link will go to
				 the defined path in $dataEntryURL which is the data entry application for updating/inserting
				 records.
				 REQUIREMENT: requires a column of the name UID that is primary key and auto increment / identity.
				 If using columnsArr you will have to pass UID and it has to be the first column.
	
	@param dataEntryURL [string]
	DEFAULT: NULL
	The URL of the data entry application to be used with the created datagrid.
	NOTE: only pass the filename, i.e. omniphp_dataentry.php, it will be automatically converted to the absolute
	path, i.e. http(s)://hostname/omniphp_dataentry.php (HTTP 1.1 compliant).
	If your data entry app is inside a folder (i.e. customers) you can pass the argument like: "customers/omniphp_dataentry.php"
	and it will be converted to: http(s)://hostname/customers/omniphp_dataentry.php (do not use a starting /).
	
	@param table [string]
	DEFAULT: TRUE
	The table to query.
	
	@param columnsArr [array]
	DEFAULT: NULL
	An array of the columns to retrieve from the query (i.e. SELECT Column1, Column2 FROM TABLE_NAME...
	If NULL, it will be a SELECT * FROM TABLE.
	
	@param whereClause [string]
	DEFAULT: NULL
	REQUIRES: ORDER BY so that $order can work.
	The where condition for the SQL query. Does not have to be limited to the where itself, can
	also pass stuff like GROUP BY COLUMN(S) ORDER BY COLUMN(S), etc. If it is NULL the where clause
	will be ignored and it will run as a normal SELECT * FROM TABLE_NAME.
	
	@param order [string]
	DEFAULT: ASC
	Can be either ASC or DESC. For use with the ORDER BY clause.
	
	@param limit [int]
	DEFAULT: NULL
	Return n rows, MySQL LIMIT or MSSQL TOP or the equivalents.
	
	@return
	If returnFormat is OBJECT or COUNTER it will retrieve either an array or an integer. If
	it is HTML it will not retrieve anything.
	ON ERROR: It will return NULL or array(), call getLastError() for error information/details.
	*/
    public function execute_query($returnFormat = "HTML", $dataEntryURL = NULL, $table, $columnsArr = NULL, $whereClause = NULL, $order = "ASC", $limit = NULL)
    {
		//CREATE ABSOLUTE PATH FOR $dataEntryURL
		if( ($returnFormat === "DATAGRID") && (!empty($dataEntryURL)) )
		{
			if( (isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] === "on") )
			{
				$dataEntryURL = "https://" . $_SERVER['HTTP_HOST'] . "/" . $dataEntryURL; //correct: absolute path (HTTP/1.1 standard)
			}
			else
			{
				$dataEntryURL = "http://" . $_SERVER['HTTP_HOST'] . "/" . $dataEntryURL; //correct: absolute path (HTTP/1.1 standard)
			}
		}
	
		//SET THE COLUMNS TO RETRIEVE
		$cols = "";
		if(is_null($columnsArr) || empty($columnsArr))
		{
			$cols = "*"; //ALL
		}
		else //Conditional as passed when calling the method
		{
			foreach($columnsArr as $colName)
			{
				if($this->rdbms === RDBMS_MYSQL)
				{
					$cols .= "`" . $colName . "`, ";
				}
				else if( ($this->rdbms === RDBMS_MSSQL) || ($this->rdbms === RDBMS_PHPSQLDRV) )
				{
					$cols .= "[" . $colName . "], ";
				}
			}
			$colName = NULL;
			$cols = trim($cols);
			$cols = substr($cols, 0, -1); //eliminate the last comma
		}
		
		//==============================================
		// MySQL (using MySQL Improved, mysqli library)
		//==============================================
		if($this->rdbms === RDBMS_MYSQL)
		{
			$query = "SELECT " . $cols . " FROM `" . $table . "`";
			if(!is_null($whereClause))
			{
				$query .= " WHERE " . $whereClause;
				$query .= " " . $order . " ";
			}
			if(!is_null($limit))
			{
				$query .= " LIMIT " . $limit;
			}
			if($result = $this->mysqli->query($query))
			{
				if( ($returnFormat === "HTML") || (($returnFormat === "DATAGRID") && (!empty($dataEntryURL))) )
				{
					echo "<div class='omniphp_query_fetched'>" . PHP_EOL;
					echo "Fetched rows: " . $this->mysqli->affected_rows . "<br />" . PHP_EOL;
					echo "</div>" . PHP_EOL;
					echo "<table class='omniphp_query_table' border='0' cellpadding='2' cellspacing='2'>" . PHP_EOL;
					
					//fields
					echo "<tr class='omniphp_query_header'>" . PHP_EOL;
					if( ($returnFormat === "DATAGRID") && (!empty($dataEntryURL)) )
					{
						echo "<td>&nbsp;</td>"; //for edit
						echo "<td>&nbsp;</td>"; //for delete
					}
					$finfo = $result->fetch_fields();
					foreach ($finfo as $v)
					{
						echo "<td>" . $v->name . "</td>";
					}
					$v = NULL;
					echo "</tr>" . PHP_EOL;
					
					//rows
					$tmpCounter = 0;
					if(($returnFormat === "DATAGRID") && (!empty($dataEntryURL))) //for delete
					{
						$delIdx = 0;
						global $lg_lastVal;
					}
					while ($row = $result->fetch_row())
					{
						if(($tmpCounter % 2) == 0)
						{
							echo "<tr class='omniphp_query_even'>" . PHP_EOL;
						}
						else
						{
							echo "<tr class='omniphp_query_odd'>" . PHP_EOL;
						}
						foreach($row as $i => $v)
						{
							//Create Edit and Delete
							if( ($i == "UID") && (ctype_digit($v)) && ($returnFormat === "DATAGRID") && (!empty($dataEntryURL)) )
							{
								echo "<td>";
								echo "<a class='omniphp_query_button' href='" . $dataEntryURL . "?UID=" . $v . "'>EDIT</a>";
								echo "</td>" . PHP_EOL;
								
								echo "<td style='vertical-align: top; text-align: center;'>";
								echo "<input type='checkbox' name='frmDelRow[" . $delIdx . "]' value='" . $v . "' ";
								if( (isset($lg_lastVal['frmDelRow'][$delIdx])) && ($lg_lastVal['frmDelRow'][$delIdx] == $v) )
									echo "checked='checked' ";
								echo "/> Delete";
								echo "</td>" . PHP_EOL;
								
								$delIdx++;
							}
							
							if(strlen(trim($v)) > 0)
							{
								echo "<td>" . htmlspecialchars($v, ENT_QUOTES) . "</td>";
							}
							else
							{
								echo "<td>&nbsp;</td>";
							}
						}
						$i = NULL;
						$v = NULL;
						echo "</tr>" . PHP_EOL;
						
						$tmpCounter++; //for even/odd rows.
					}
					
					echo "</table><br />" . PHP_EOL;
					$result->close();
				}
				else if($returnFormat === "OBJECT")
				{
					unset($arrData);
					$currRow = 0;
					while ($row = $result->fetch_assoc())
					{
						foreach($row as $i => $v)
						{
							$arrData[$currRow][$i] = $v;
						}
						$i = NULL;
						$v = NULL;
						$currRow++;
					}
					$result->close();
					if(!isset($arrData) || empty($arrData))
					{
						$this->lastError = "";
						if($this->bErrNotify)
						{
							$this->lastError .= "SQL Query Failed: <br />" . $query . "<br />";
							$this->lastError .= "Error #" . $this->mysqli->errno . ": " . $this->mysqli->error . "<br />" . PHP_EOL;
						}
						else
						{
							$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
						}
						return array();
					}
					return $arrData;
				}
				else if($returnFormat === "COUNTER")
				{
					$counter = $this->mysqli->affected_rows;
					$result->close();
					return $counter;
				}
				else
				{
					$this->lastError = "Error: Incorrect parameter to returnFormat.<br />" . PHP_EOL;
					$result->close();
					return NULL;
				}
			}
			else
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "Query Failed: <br />" . $query . "<br />";
					$this->lastError .= "Error #" . $this->mysqli->errno . ": " . $this->mysqli->error . "<br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
		}
		//=======================================================================
		// SQL Server Driver for PHP (using Microsoft's official driver/library)
		//=======================================================================
		else if($this->rdbms === RDBMS_PHPSQLDRV)
		{
			if(!is_null($limit))
			{
				$tsql = "SELECT TOP(" . $limit . ") " . $cols . " FROM [" . $table . "]";
			}
			else
			{
				$tsql = "SELECT " . $cols . " FROM [" . $table . "]";
			}
			if(!is_null($whereClause))
			{
				$tsql .= " WHERE " . $whereClause;
				$tsql .= " " . $order . " ";
			}
			$stmt = sqlsrv_query($this->sqllink, $tsql);
			if($stmt === false)
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "Query Failed: <br />" . $tsql . "<br />";
					$this->lastError .= "Error executing query:<br /><pre>" . print_r(sqlsrv_errors(), true) . "</pre><br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
			else
			{
				if( ($returnFormat === "HTML") || (($returnFormat === "DATAGRID") && (!empty($dataEntryURL))) )
				{
					echo "<div class='omniphp_query_fetched'>" . PHP_EOL;
					//echo "Fetched rows: " . sqlsrv_num_rows($stmt) . "<br />" . PHP_EOL; //this will not work with FORWARD datasets so ignore it.
					echo "Fetched rows: " . $this->execute_query("COUNTER", NULL, $table, NULL, NULL, NULL, NULL) . "<br />";
					echo "</div>" . PHP_EOL;
					echo "<table class='omniphp_query_table' border='0' cellpadding='2' cellspacing='2'>" . PHP_EOL;
					
					//metadata / fields
					echo "<tr class='omniphp_query_header'>" . PHP_EOL;
					if( ($returnFormat === "DATAGRID") && (!empty($dataEntryURL)) )
					{
						echo "<td>&nbsp;</td>"; //for edit
						echo "<td>&nbsp;</td>"; //for delete
					}
					foreach(sqlsrv_field_metadata($stmt) as $meta)
					{
						echo "<td>" . $meta['Name'] . "</td>";
					}
					$meta = array();
					echo "</tr>" . PHP_EOL;
					
					//rows
					$tmpCounter = 0;
					if(($returnFormat === "DATAGRID") && (!empty($dataEntryURL))) //for delete
					{
						$delIdx = 0;
						global $lg_lastVal;
					}
					while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC))
					{
						if(($tmpCounter % 2) == 0)
						{
							echo "<tr class='omniphp_query_even'>" . PHP_EOL;
						}
						else
						{
							echo "<tr class='omniphp_query_odd'>" . PHP_EOL;
						}
						foreach($row as $i => $v)
						{
							//Create Edit and Delete
							if( ($i == "UID") && (ctype_digit($v)) && ($returnFormat === "DATAGRID") && (!empty($dataEntryURL)) )
							{
								echo "<td>";
								echo "<a class='omniphp_query_button' href='" . $dataEntryURL . "?UID=" . $v . "'>EDIT</a>";
								echo "</td>" . PHP_EOL;
								
								echo "<td style='vertical-align: top; text-align: center;'>";
								echo "<input type='checkbox' name='frmDelRow[" . $delIdx . "]' value='" . $v . "' ";
								if( (isset($lg_lastVal['frmDelRow'][$delIdx])) && ($lg_lastVal['frmDelRow'][$delIdx] == $v) )
									echo "checked='checked' ";
								echo "/> Delete";
								echo "</td>" . PHP_EOL;
								
								$delIdx++;
							}
							
							if(strlen(trim($v)) > 0)
							{
								echo "<td>" . htmlspecialchars($v, ENT_QUOTES) . "</td>";
							}
							else
							{
								echo "<td>&nbsp;</td>";
							}
						}
						$i = NULL;
						$v = NULL;
						echo "</tr>" . PHP_EOL;
						
						$tmpCounter++; //for even/odd rows.
					}
					
					echo "</table><br />" . PHP_EOL;
					sqlsrv_free_stmt($stmt);
				}
				else if($returnFormat === "OBJECT")
				{
					unset($arrData);
					$currRow = 0;
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
					{
						foreach($row as $i => $v)
						{
							$arrData[$currRow][$i] = $v;
						}
						$i = NULL;
						$v = NULL;
						$currRow++;
					}
					sqlsrv_free_stmt($stmt);
					if(!isset($arrData) || empty($arrData))
					{
						$this->lastError = "";
						if($this->bErrNotify)
						{
							$this->lastError .= "SQL Query Failed: <br />" . $tsql . "<br />";
							$this->lastError .= "Error executing query:<br /><pre>" . print_r(sqlsrv_errors(), true) . "</pre><br />" . PHP_EOL;
						}
						else
						{
							$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
						}
						return array();
					}
					return $arrData;
				}
				else if($returnFormat === "COUNTER")
				{
					$tsql2 = "SELECT COUNT(*) FROM [" . $table . "]";
					if(!is_null($whereClause))
					{
						$pos = stripos($whereClause, "ORDER BY");
						$whereClause = substr($whereClause, 0, ($pos - 1));
						$tsql2 .= " WHERE " . $whereClause;
					}
					$stmt2 = sqlsrv_query($this->sqllink, $tsql2);
					$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC);
					return $row2[0];
				}
				else
				{
					$this->lastError = "Error: Incorrect parameter to returnFormat.<br />" . PHP_EOL;
					sqlsrv_free_stmt($stmt);
					return NULL;
				}
			}	
		}
		//====================================================================
		// MS SQL (old library, not recommended, use RDBMS_PHPSQLDRV instead)
		//====================================================================
		else if($this->rdbms === RDBMS_MSSQL)
		{
			if(!is_null($limit))
			{
				$query = "SELECT TOP(" . $limit . ") " . $cols . " FROM [" . $table . "]";
			}
			else
			{
				$query = "SELECT " . $cols . " FROM [" . $table . "]";
			}
			if(!is_null($whereClause))
			{
				$query .= " WHERE " . $whereClause;
				$query .= " " . $order . " ";
			}
			if($result = mssql_query($query))
			{
				if( ($returnFormat === "HTML") || (($returnFormat === "DATAGRID") && (!empty($dataEntryURL))) )
				{
					echo "<div class='omniphp_query_fetched'>" . PHP_EOL;
					echo "Fetched rows: " . mssql_rows_affected($this->sqllink). "<br />" . PHP_EOL;
					echo "</div>" . PHP_EOL;
					echo "<table class='omniphp_query_table' border='0' cellpadding='2' cellspacing='2'>" . PHP_EOL;
					
					//fields
					echo "<tr class='omniphp_query_header'>" . PHP_EOL;
					if( ($returnFormat === "DATAGRID") && (!empty($dataEntryURL)) )
					{
						echo "<td>&nbsp;</td>"; //for edit
						echo "<td>&nbsp;</td>"; //for delete
					}
					while($finfo = mssql_fetch_field($result))
					{
						echo "<td>" . $finfo->name . "</td>";
					}
					echo "</tr>" . PHP_EOL;
					
					//rows
					$tmpCounter = 0;
					if(($returnFormat === "DATAGRID") && (!empty($dataEntryURL))) //for delete
					{
						$delIdx = 0;
						global $lg_lastVal;
					}
					while ($row = mssql_fetch_row($result))
					{
						if(($tmpCounter % 2) == 0)
						{
							echo "<tr class='omniphp_query_even'>" . PHP_EOL;
						}
						else
						{
							echo "<tr class='omniphp_query_odd'>" . PHP_EOL;
						}
						foreach($row as $i => $v)
						{
							//Create Edit and Delete
							if( ($i == "UID") && (ctype_digit($v)) && ($returnFormat === "DATAGRID") && (!empty($dataEntryURL)) )
							{
								echo "<td>";
								echo "<a class='omniphp_query_button' href='" . $dataEntryURL . "?UID=" . $v . "'>EDIT</a>";
								echo "</td>" . PHP_EOL;
								
								echo "<td style='vertical-align: top; text-align: center;'>";
								echo "<input type='checkbox' name='frmDelRow[" . $delIdx . "]' value='" . $v . "' ";
								if( (isset($lg_lastVal['frmDelRow'][$delIdx])) && ($lg_lastVal['frmDelRow'][$delIdx] == $v) )
									echo "checked='checked' ";
								echo "/> Delete";
								echo "</td>" . PHP_EOL;
								
								$delIdx++;
							}
							
							if(strlen(trim($v)) > 0)
							{
								echo "<td>" . htmlspecialchars($v, ENT_QUOTES) . "</td>";
							}
							else
							{
								echo "<td>&nbsp;</td>";
							}
						}
						$i = NULL;
						$v = NULL;
						echo "</tr>" . PHP_EOL;
						
						$tmpCounter++; //for even/odd rows.
					}
					
					echo "</table><br />" . PHP_EOL;
					mssql_free_result($result);
				}
				else if($returnFormat === "OBJECT")
				{
					unset($arrData);
					$currRow = 0;
					while ($row = mssql_fetch_assoc($result))
					{
						foreach($row as $i => $v)
						{
							$arrData[$currRow][$i] = $v;
						}
						$i = NULL;
						$v = NULL;
						$currRow++;
					}
					mssql_free_result($result);
					if(!isset($arrData) || empty($arrData))
					{
						$this->lastError = "";
						if($this->bErrNotify)
						{
							$this->lastError .= "SQL Query Failed: <br />" . $query . "<br />";
							$this->lastError .= "Error: " . mssql_get_last_message() . "<br />" . PHP_EOL;
						}
						else
						{
							$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
						}
						return array();
					}
					return $arrData;
				}
				else if($returnFormat === "COUNTER")
				{
					$counter = mssql_rows_affected($this->sqllink);
					mssql_free_result($result);
					return $counter;
				}
				else
				{
					$this->lastError = "Error: Incorrect parameter to returnFormat.<br />" . PHP_EOL;
					mssql_free_result($result);
					return NULL;
				}
			}
			else
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "Query Failed: <br />" . $query . "<br />";
					$this->lastError .= "Error: " . mssql_get_last_message() . "<br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
		}
		//No other RDBMS is supported yet, in the future support for: Oracle, DB2, Sybase, PostGRE, etc. will be added
		else
		{
			die("FATAL ERROR executing query: INVALID RDBMS!<br />" . PHP_EOL);
		}
    }
    
	/*
	@method
	execute_command(...)
	
	@desc
	Performs an SQL command (non query: INSERT, UPDATE, DELETE).
	
	@param nonquery [string]
	The full nonquery SQL command to execute (INSERT, UPDATE, or DELETE).
	
	@return
	Returns 1 on success, NULL on error. Call getLastError() for details on error if
	NULL.
	*/
	public function execute_command($nonquery)
	{
		$this->lastAffectedRows = NULL;
		
		/* IMPORTANT NOTE ON ENCODING:
		If the charset encoding is not a standard: ASCII or UTF-8 the characters MIGHT be corrupted or
		the query may fail altogether. Try using a standard encoding if possible, otherwise you might
		need to perform conversions to your default format. By default this will work perfectly for English
		and Spanish (also for most European languages). Eastern European, Asian, African, and Arabian languages
		should be tested before any production release.
		*/
		//check character encoding and set to UTF-8 if not UTF-8:
		if(mb_check_encoding($nonquery, "UTF-8") === false)
		{
			//$nonquery = utf8_encode($nonquery); //probably not the best way as non ISO-8859-1 might be corrupted
			$nonquery = mb_convert_encoding($nonquery, "UTF-8", "auto"); //note: better than above, but might not be an all around solution either.
		}
		
		if($this->rdbms === RDBMS_MYSQL)
		{
			if($this->mysqli->query($nonquery))
			{
				$countAffected = $this->mysqli->affected_rows;
				$this->lastAffectedRows = $countAffected;
				if($countAffected <= 0)
				{
					$this->lastError = "";
					if($this->bErrNotify)
					{
						$this->lastError .= "SQL Command Failed: <br />" . $nonquery . "<br />";
						$this->lastError .= "Error #" . $this->mysqli->errno . ": " . $this->mysqli->error . "<br />" . PHP_EOL;
					}
					else
					{
						$this->lastError .= "Error executing command: Command Failed!<br />" . PHP_EOL;
					}
					return NULL;
				}
				else
				{
					return 1;
				}
			}
			else
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "SQL Command Failed: <br />" . $nonquery . "<br />";
					$this->lastError .= "Error #" . $this->mysqli->errno . ": " . $this->mysqli->error . "<br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing command: Command Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
		}
		else if($this->rdbms === RDBMS_PHPSQLDRV)
		{
			$stmt = sqlsrv_query($this->sqllink, $nonquery);
			if($stmt)
			{
				$countAffected = sqlsrv_rows_affected($stmt);
				$this->lastAffectedRows = $countAffected;
				if(($countAffected <= 0) || ($countAffected === false))
				{
					$this->lastError = "";
					if($this->bErrNotify)
					{
						$this->lastError .= "SQL Command Failed: <br />" . $nonquery . "<br />";
						$this->lastError .= "Error executing query:<br /><pre>" . print_r(sqlsrv_errors(), true) . "</pre><br />" . PHP_EOL;
					}
					else
					{
						$this->lastError .= "Error executing command: Command Failed!<br />" . PHP_EOL;
					}
					return NULL;
				}
				else
				{
					return 1;
				}
			}
			else
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "SQL Command Failed: <br />" . $nonquery . "<br />";
					$this->lastError .= "Error executing query:<br /><pre>" . print_r(sqlsrv_errors(), true) . "</pre><br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing command: Command Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
		}
		else if($this->rdbms === RDBMS_MSSQL)
		{
			if(mssql_query($nonquery))
			{
				$countAffected = mssql_rows_affected($this->sqllink);
				$this->lastAffectedRows = $countAffected;
				if($countAffected <= 0)
				{
					$this->lastError = "";
					if($this->bErrNotify)
					{
						$this->lastError .= "SQL Command Failed: <br />" . $nonquery . "<br />";
					$this->lastError .= "Error: " . mssql_get_last_message() . "<br />" . PHP_EOL;
					}
					else
					{
						$this->lastError .= "Error executing command: Command Failed!<br />" . PHP_EOL;
					}
					return NULL;
				}
				else
				{
					return 1;
				}
			}
			else
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "SQL Command Failed: <br />" . $nonquery . "<br />";
					$this->lastError .= "Error: " . mssql_get_last_message() . "<br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing command: Command Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
		}
		
		//default to error:
		$this->lastError = "FATAL ERROR: NO EXECUTE COMMAND, INCORRECT RDBMS.";
		return NULL;
	}
	
	/*
	@method
	execute_literal_query(...)
	
	@desc
	Allows performing a literal query (i.e. SELECT, SHOW, SP_, etc) that returns a result set. This
	is almost identical to calling execute_query with the OBJECT parameter, except that it is not
	limited to the SELECT command. That is basically the only difference between this and
	execute_query(...). In most cases it is recommended to use execute_query(...) instead of this.
	
	@return
	It will return an array object containing the result of the query (identical to calling
	execute_query with the "OBJECT" parameter).
	ON ERROR: It will return NULL or array(), call getLastError() for error information/details.
	*/
        /**
         * method: execute_literal_query(...)
         * 
         * Perform a literal query (i.e. SELECT, SHOW, SP_, etc) that returns a result set.
         * This is almost identical to calling execute_query with the OBJECT parameter except
         * that it is not limited to the SELECT command. For further abstraction the execute_query
         * is recommended over this.
         * 
         * @param type $query
         * @return array Returns an array object containing the result of the query. On error it
         * will return NULL or array(), call getLastError() for error information/details.
         */
	public function execute_literal_query($query)
    {
		//==============================================
		// MySQL (using MySQL Improved, mysqli library)
		//==============================================
		if($this->rdbms === RDBMS_MYSQL)
		{
			if($result = $this->mysqli->query($query))
			{
				unset($arrData);
				$currRow = 0;
				while ($row = $result->fetch_assoc())
				{
					foreach($row as $i => $v)
					{
						$arrData[$currRow][$i] = htmlentities($v, ENT_QUOTES);
					}
					$i = NULL;
					$v = NULL;
					$currRow++;
				}
				$result->close();
				if(!isset($arrData) || empty($arrData))
				{
					$this->lastError = "";
					if($this->bErrNotify)
					{
						$this->lastError .= "SQL Query Failed: <br />" . $query . "<br />";
						$this->lastError .= "Error #" . $this->mysqli->errno . ": " . $this->mysqli->error . "<br />" . PHP_EOL;
					}
					else
					{
						$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
					}
					return array();
				}
				return $arrData;
			}
			else
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "Query Failed: <br />" . $query . "<br />";
					$this->lastError .= "Error #" . $this->mysqli->errno . ": " . $this->mysqli->error . "<br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
		}
		//=======================================================================
		// SQL Server Driver for PHP (using Microsoft's official driver/library)
		//=======================================================================
		else if($this->rdbms === RDBMS_PHPSQLDRV)
		{
			$tsql = $query;
			$stmt = sqlsrv_query($this->sqllink, $tsql);
			if($stmt === false)
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "Query Failed: <br />" . $tsql . "<br />";
					$this->lastError .= "Error executing query:<br /><pre>" . print_r(sqlsrv_errors(), true) . "</pre><br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
			else
			{
				unset($arrData);
				$currRow = 0;
				while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
				{
					foreach($row as $i => $v)
					{
                                                $arrData[$currRow][$i] = htmlentities($v, ENT_QUOTES);
					}
					$i = NULL;
					$v = NULL;
					$currRow++;
				}
				sqlsrv_free_stmt($stmt);
				if(!isset($arrData) || empty($arrData))
				{
					$this->lastError = "";
					if($this->bErrNotify)
					{
						$this->lastError .= "SQL Query Failed: <br />" . $tsql . "<br />";
						$this->lastError .= "Error executing query:<br /><pre>" . print_r(sqlsrv_errors(), true) . "</pre><br />" . PHP_EOL;
					}
					else
					{
						$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
					}
					return array();
				}
				return $arrData;
			}	
		}
		//====================================================================
		// MS SQL (old library, not recommended, use RDBMS_PHPSQLDRV instead)
		//====================================================================
		else if($this->rdbms === RDBMS_MSSQL)
		{
			if($result = mssql_query($query))
			{
				unset($arrData);
				$currRow = 0;
				while ($row = mssql_fetch_assoc($result))
				{
					foreach($row as $i => $v)
					{
                                                $arrData[$currRow][$i] = htmlentities($v, ENT_QUOTES);
					}
					$i = NULL;
					$v = NULL;
					$currRow++;
				}
				mssql_free_result($result);
				if(!isset($arrData) || empty($arrData))
				{
					$this->lastError = "";
					if($this->bErrNotify)
					{
						$this->lastError .= "SQL Query Failed: <br />" . $query . "<br />";
						$this->lastError .= "Error: " . mssql_get_last_message() . "<br />" . PHP_EOL;
					}
					else
					{
						$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
					}
					return array();
				}
				return $arrData;
			}
			else
			{
				$this->lastError = "";
				if($this->bErrNotify)
				{
					$this->lastError .= "Query Failed: <br />" . $query . "<br />";
					$this->lastError .= "Error: " . mssql_get_last_message() . "<br />" . PHP_EOL;
				}
				else
				{
					$this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
				}
				return NULL;
			}
		}
		//No other RDBMS is supported yet, in the future support for: Oracle, DB2, Sybase, PostGRE, etc. will be added
		else
		{
			die("FATAL ERROR executing query: INVALID RDBMS!<br />" . PHP_EOL);
		}
    }
	
    
        //hack: added 2011-12-14
        //note: only implemented for MySQL
        /**
         * method: retrieve_distinct
         * Retrieves a DISTINCT on the given table for the given column.
         * 
         * @param string $table SQL Table Name
         * @param string $column The column
         * @param string $whereClause Applicable where clause, if not, leave empty or assign NULL
         * @param string $order The order clause if where exists
         * @param int $limit The limit of the query, i.e. 
         * 
         * @return object An array object containing the results.
         */
        public function retrieve_distinct($table, $column, $whereClause = NULL, $order = "ASC", $limit = NULL)
        {
            //==============================================
            // MySQL (using MySQL Improved, mysqli library)
            //==============================================
            if($this->rdbms === RDBMS_MYSQL)
            {
                $query = "SELECT DISTINCT `" . $column . "` FROM `" . $table . "`";
                if(!is_null($whereClause))
                {
                    $query .= " WHERE " . $whereClause;
                    $query .= " " . $order . " ";
                }
                if(!is_null($limit))
                {
                    $query .= " LIMIT " . $limit;
                }

                if($result = $this->mysqli->query($query))
                {
                    unset($arrData);
                    $currRow = 0;
                    while ($row = $result->fetch_assoc())
                    {
                        foreach($row as $i => $v)
                        {
                            $arrData[$currRow][$i] = $v;
                        }
                        $i = NULL;
                        $v = NULL;
                        $currRow++;
                    }
                    $result->close();
                    if(!isset($arrData) || empty($arrData))
                    {
                        $this->lastError = "";
                        if($this->bErrNotify)
                        {
                            $this->lastError .= "SQL Query Failed: <br />" . $query . "<br />";
                            $this->lastError .= "Error #" . $this->mysqli->errno . ": " . $this->mysqli->error . "<br />" . PHP_EOL;
                        }
                        else
                        {
                            $this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
                        }
                        return array();
                    }
                    return $arrData;
                }
                else
                {
                    $this->lastError = "";
                    if($this->bErrNotify)
                    {
                            $this->lastError .= "Query Failed: <br />" . $query . "<br />";
                            $this->lastError .= "Error #" . $this->mysqli->errno . ": " . $this->mysqli->error . "<br />" . PHP_EOL;
                    }
                    else
                    {
                            $this->lastError .= "Error executing query: Query Failed!<br />" . PHP_EOL;
                    }
                    return NULL;
                }
            }
        }
        
        //real_escape_string
        //hack: added 2011-12-15
        //note: only implemented for MySQL
        /**
         * method: safe_str(...)
         * Retrieves a safe (escaped) string for usage in SQL queries, to avoid
         * SQL injections. NOTE: Does not prepend/append single quotes, so add 
         * them manually (if applicable).
         * 
         * @param string $str String to be escaped/safe outputted
         * 
         * @return string|NULL The properly escaped string or NULL in case of error.
         */
        public function safe_str($str)
        {
            //==============================================
            // MySQL (using MySQL Improved, mysqli library)
            //==============================================
            if($this->rdbms === RDBMS_MYSQL)
            {
                return $this->mysqli->real_escape_string($str);
            }
            return NULL;
        }
        
        /**
         * method: getLastError()
         * 
         * Returns the last error (for last query/command only). Only call this when
         * execute_query or execute_command return NULL.
         * 
         * @return string Returns the error messages obtained from the last query or command. If no error it will return empty.
         */
	public function getLastError()
	{
            return $this->lastError;
	}
	
        /**
         * method: getAffectedRows()
         * 
         * Returns the numbers of rows affected (i.e. inserted, updated, deleted, selected/queried).
         * Only for last query/command.
         * 
         * @return int The number of rows affected by last query/command.
         */
	public function getAffectedRows()
	{
            return $this->lastAffectedRows;
	}
        
        /**
         * method: getInsertID()
         * 
         * Returns the last ID inserted to a table, only for INSERT/UPDATE. 
         * Call this only after execute_command() with INSERT/UPDATE statements.
         * 
         * @return int|string Get the ID of the last inserted row. Returns 0 when fail.
         */
        public function getInsertID()
        {
            return $this->mysqli->insert_id;
        }
        
        /**
         * method: get_last_error()
         * alias of getLastError()
         */
        public function get_last_error()
        {
            return $this->getLastError();
        }
        
        /**
         * method: get_affected_rows()
         * alias of getAffectedRows()
         */
        public function get_affected_rows()
        {
            return $this->getAffectedRows();
        }
        
        /**
         * method: get_insert_id()
         * alias of getInsertID()
         */
        public function get_insert_id()
        {
            return $this->getInsertID();
        }
        
}

//fix me (add me)
class Framework_SQL_DataSets
{
    //use this class to handle data sets instead of retrieving and handling
    //objects directly from $arrData. This would serve the purpose of properly
    //creating and handling a dataset.
    
}

?>
