<?php
$DB = null;

function getLastInsertId($name = '')
{
	global $DB;
	return $DB->lastInsertId($name);
}

function sqlQuery()
{
	global $DB;

	if(is_null($DB)) {
		try {
			$DB = new PDO('pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME,
						   DB_USER, DB_PASSWORD);

			$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $error) {
			error_log ('DB Initialization Error: '.$error->getMessage());

			if (function_exists('displayError'))
				displayError (_('Could not connect to the database, sorry.'));
			else
				die(_('Could not connect to the database, sorry.'));
		}
	}

	$args = func_get_args();
	if(empty($args))
		return;

	try {
		$request = $DB->prepare($args[0]);
		$request->setFetchMode(PDO::FETCH_ASSOC);

		if(sizeof($args) > 1) {
			$args = array_splice($args, 1);
			$request->execute($args);
		} else
			$request->execute();
			
	} catch(PDOException $error) {
		error_log ('DB Query Error: '.$error->getMessage());

		if (function_exists('displayError'))
			displayError (_('Could not execute the database query.'));
		else
		{
			die(_('Could not execute the database query.'));
		}
	}
	
	return $request;
}?>