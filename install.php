<?php

/**
 * Open a connection via PDO to create a
 * new database and table with structure.
 *
 */

require "dbpath.php";

try 
{
	$connection = new PDO($dsn);
	$connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$createTable = file_get_contents("queries/createTable.sql");
	$connection->exec($createTable);
	header('Location: app');	
}

catch(PDOException $error)
{
	echo $sql . "<br>" . $error->getMessage();
}