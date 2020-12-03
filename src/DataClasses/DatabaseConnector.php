<?php
//this file describes the connection to the database and the function used to query the database, QueryFunc
//to properly use this file write the following
//  require_once "DatabaseConnector.php";
//  use DataClasses\DatabaseConnector\QueryFunc;
declare(strict_types=1);
namespace DataClasses\DatabaseConnector; //namespace exists so I can have encapsulation without a class.
use \mysqli;
use \Exception;

//this function generates the function that is used to make sql calls.
//This is done like this to make use of a closure to only need to make a datbase connection once.
//I would make this private if namespace visibility was a thing
function GenerateQueryFunc(
	string $serverName,
	string $username,
	string $password,
	string $dbName
): callable {
	$conn = new mysqli($serverName, $username, $password, $dbName);

	//check for failed connection
	if ($conn->connect_error) {
		throw new Exception("Connection Failed to mysql database");
	}

	//select queries return objects whereas update and insert queries return bools
	//this was finished 10 days before php 8, if i was able to use the union types introduced by
	//php 8 i would have had the return type hint be object|bool
	//(although i could use phpdoc)
	return function(string $query) use ($conn) {
		$tmp = $conn->query($query);
		return $tmp;
	};
	//where do I close the connection?
}

//I would make this private if php had namespace visibility
//I can't make this a constant because constants have to be known at compile time
//These values are replaced to the real values when the files are uploaded to awardspace
$queryFunc = GenerateQueryFunc("localhost", "root", "password", "Collections");

//this function exists because I like to have a consistent way of calling functions and using $queryFunc by itself would break that consistency.
//note: QueryFunc could be considered hungarian notation, if this is true change to DatabaseQuery;
function QueryFunc(string $query)
{
	global $queryFunc;
	return $queryFunc($query);
}
