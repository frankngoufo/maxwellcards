<?php
/*
Data Manager base class.
Data managers are responsible for handling all get (get_data()) and set (set_data())
operations with the database or other backend system.
If getting data will need any other implementation different from this, extend
the DataManager class and override any necessary methods, including connecting
to the database (selecting a different database for example)
*/
class DataManager {
	protected $dbh;

	public function __construct() {
		try {
			$this->dbh = new PDO('mysql:host=localhost;dbname=DB_NAME','DB_USER','DB_PASS', array(
				PDO::ATTR_PERSISTENT => true //cache and avoid reconnecting. See doc for PDO connection management
			));
		}
		catch (PDOException $e) {
			print "Error!: ";
			//print "Error!: ";
			die();
		}
	}

	/* Single end-point for retrieving all data in the entire web application
	not to be confused with load_data() in our Page base class (see page.php) */

	public function get_data( $args, &$data, &$stat ) {
		/* $args is an array: here are its values:
		0 => prepared query
		1 => array( //array of parameters to execute for each prepared query. Thus, we can cache and reuse queries
			--either--
			0 => parameter 1,
			1 => parameter 2 ...
			-- for queries occuring once, or --
			0 => array( parameter 1, parameter 2, ..)
			1 => array( parameter 1, parameter 2, ...) ...
			-- for named parameters (:p) and queries to cached--
			) 
		*/
		$prepared_query = $args[0];
		$param_array = $args[1];

		//normalize
		if (!is_array($param_array[0])) {
			$param_array = array($param_array);
		}

		// sql to fetch data
		foreach ($param_array as $k) {
			$sql = $this->dbh->prepare( $prepared_query );

			if($sql->execute( $k )) {
				$stat = [];

				// Assign data based on whether query was single or multiple; condition introduced for compatibility
				count($param_array) == 1 ? $data = $this->order_results($sql) : $data[] = $this->order_results($sql);
			} else {
				//Assign error info to status variable. also return empty array as data received
				$stat = $sql->errorInfo();
				$data = [];
			}
		}
	}

	// Each instance of DataManager will have its own way of presenting its results
	// Here is the default. It returns an array populated with the entire results from fetch()
	public function order_results($sql) {
		$a = array();
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$a[] = $row;
		}
		return $a;
	}
	
	/* Single end-point for saving all data in the entire web application
	not to be confused with save_data() in our Page base class (see page.php) */

	// We combine all queries so as to rollback() if any query has an error
	public function set_data ( &$stat, $args, &$data = array() ) {
		$this->dbh->beginTransaction();

		/* We need to normalize arguments as multi-dimensionl array, because arguments can either be:
			args[0] = query statement,
			args[1] = parameters

			or (in which case we don't normalize)

			args = array(
				0 => array(
					0 => query statement,
					1 => parameters
					)
				),
				1 => array(
					0 => query statement,
					1 => parameters
					),
				...
		*/

		if (!is_array( $args[0] )) {
			$args = array($args);
		}

		// Arguments are stored in $my_args
		foreach ($args as $k) {
			$prepared_query = $k[0];
			$param_array = $k[1];

			//sql to save data
			if (empty($param_array)) {
				// A query without parameters
				if ($this->dbh->exec($prepared_query) === false) {
					$stat[] = $this->dbh->errorInfo();
					$this->dbh->rollBack();
					break;
				}
			} else {
				$sql = $this->dbh->prepare( $prepared_query );
				// store error to status variable; rollback transaction
				if(!$sql->execute( $param_array )) {
					$stat[] = $sql->errorInfo();
					$this->dbh->rollBack();
					break;
				}
				$data[] = $sql->rowCount(); // Send rows affected
			}

			if(!empty($this->dbh->lastInsertId())) {
				$_SESSION["lastInsertId"] = $this->dbh->lastInsertId();
			}
		}
		// Commit of no errors from all queries
		if (empty($stat)) {
			$this->dbh->commit();
		}
	}
}