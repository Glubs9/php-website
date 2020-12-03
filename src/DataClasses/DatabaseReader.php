<?php
//this file describes the functions used to populate the objects with the information from the
//database.
//BE WARNED: THIS FILE IS UGLY
declare(strict_types=1);
namespace Dataclasses\DatabaseReader; //inner namespace for encapsulation without a class
require_once "DatabaseConnector.php";
require_once "Visitor.php";
require_once "Client.php";
require_once "Review.php";
require_once "Item.php";
require_once "Collection.php";
require_once "CollectionFinder.php";
require_once "AccountHandler.php";
require_once "API.php";
use function \DataClasses\DatabaseConnector\QueryFunc as QueryFunc; 
use \DataClasses\Visitor as Visitor; //the many use statements are an acceptable problem for the encapsulation
use \DataClasses\Client as Client;
use \DataClasses\Review as Review;
use \DataClasses\Item as Item;
use \DataClasses\Collection as Collection;
use \DataClasses\CollectionFinder as CollectionFinder;
use \DataClasses\AccountHandler as AccountHandler;
use \DataClasses\API as API;
//tod o:
//cleanup the file  
//  do this through modularizing the functions

//in a real production environment I would probably use a separate library to do this or
//I would write it in a more modular way.

//this function finds an item in an array that the property name matches the value supplied
//This function works with duck typing so it is necersarry that there is no type signature on val
//and the return.
//This function is useful for 'joining' objects in php in the same way that you would join tables in sql.
//The reason I do this over joining in sql is that it is faster to go from O(n) -> O(n^2) then it is
//to execute a database query.
function findOn(array $arr, $val, string $propertyName)
{
	$checkFunc = function(object $obj) use ($val, $propertyName): bool {
		return $obj->{$propertyName} == $val;
	};
	$filtArr = array_filter($arr, $checkFunc); //array_keys to reset the keys
	return end($filtArr); //although this function could return multiple valid results it is used for primary keys so it is easier to do this
}

//generates visitor objects
//I might be able to write a functions that generates these
//I could but I need to build all the relationships which doesn't work unless I manually write each
//one
function GenerateVisitors(): array
{
	$result = QueryFunc("SELECT * FROM Visitor");
	$visitors = []; //this should really be an assoc array indexed by visitorusername

	while ($row = $result->fetch_assoc()) {
		$visitor = new Visitor(
			$row["VisitorUsername"], 
			$row["VisitorPassword"],
			$row["PhoneNumber"],
			$row["Email"],
			$row["VisitorName"],
			$row["Banned"] == "1" //convert from string to bool
		);
		array_push($visitors, $visitor);
	}
	
	return $visitors;
}

//this is the ugliest function i have ever written
function GenerateItemReviews(array $visitors): array
{
	//the problem I have run into is that the reviews table depends on the items but the items
	//can't be made without reviews.
	//my guess is that I generate reviews and gropu them by review id and then when we generate
	//items we pass it the reviews assoc array and it adds reviews by that
	
	//if we only wanted to get itemreviews from non banned visitors purely through sql i would
	//do
	//select Rating, VisitorUsername, ReviewText from ItemReview, Visitor WHERE ItemReview.VisitorUsername =
	//Visitor.VisitorUsername AND Visitor.Banned != True;
	
	$result = QueryFunc("SELECT * FROM ItemReview");
	//I guess I could do more advanced sql:
	$reviews = [];

	while ($row = $result->fetch_assoc()) {
		if (!(array_key_exists($row["ItemID"], $reviews))) {
			$reviews[$row["ItemID"]] = [];
		}

		$visitor = findOn($visitors, $row["VisitorUsername"], "Username");
		//could be done in sql but I prefer php
		if ($visitor->IsBanned()) {
			continue;
		}

		array_push($reviews[$row["ItemID"]], new Review(
			(int) $row["Rating"],
			$visitor,
			$row["ReviewText"]
		));
	}

	return $reviews;
}

function GenerateCollectionReviews(array $visitors): array
{
	$result = QueryFunc("SELECT * FROM CollectionReview");
	$reviews = [];

	while ($row = $result->fetch_assoc()) {
		if (!array_key_exists($row["CollectionID"], $reviews)) {
			$reviews[$row["CollectionID"]] = [];
		}

		$visitor = findOn($visitors, $row["VisitorUsername"], "Username");
		//could be doen in sql but i prefer php
		if ($visitor->IsBanned()) {
			continue;
		}

		array_push($reviews[$row["CollectionID"]], new Review(
			(int) $row["Rating"],
			$visitor,
			$row["ReviewText"]
		));
	}

	return $reviews;
}

//the item contsructor needs an array of reviews
//The reason I chose to get the reviews beforehand and join the 'tables' in php rather than sql
//is because it's much faster to match on an associative array than it is to run a query for each
//item although it would be trivial to do this
function GenerateItems(array $reviews): array
{
	$result = QueryFunc("SELECT * FROM Item");
	$items = [];

	while ($row = $result->fetch_assoc()) {
		if (!(array_key_exists($row["CollectionID"], $items))) {
			$items[$row["CollectionID"]] = [];
		}

		array_push($items[$row["CollectionID"]], new Item(
			$row["ItemName"],
			$row["ItemDescription"],
			$row["ItemCreationDate"],
			$row["ItemCollectedDate"],
			$row["ItemImage"],
			$reviews[$row["ItemID"]], // 'joining' tables in php
			(int) $row["ItemID"]
		));
	}

	return $items;
}

function GenerateCollections(array $items, array $reviews): array
{
	$result = QueryFunc("SELECT * FROM Collection");
	$collections = [];

	while ($row = $result->fetch_assoc()) {
		if (!array_key_exists($row["ClientUsername"], $collections)) {
			$collections[$row["ClientUsername"]] = [];
		}

		array_push($collections[$row["ClientUsername"]], new Collection(
			$items[$row["CollectionID"]],
			$reviews[$row["CollectionID"]],
			$row["CollectionName"],
			$row["CollectionDescription"],
			$row["CollectionImage"],
			$row["DateCreated"],
			(int) $row["CollectionID"]
		));
	}

	return $collections;
}

function GenerateClients(array $collections): array
{
	$result = QueryFunc("SELECT * FROM Client");
	$clients = [];
	while ($row = $result->fetch_assoc()) {
		$client = new Client(
			$row["ClientUsername"], 
			$row["ClientPassword"],
			$row["PhoneNumber"],
			$row["Email"],
			$row["ClientName"],
			$collections[$row["ClientUsername"]]
		);
		array_push($clients, $client);
	}
	return $clients;
}

function GenerateCollectionFinder(array $clients): CollectionFinder
{
	$finder = CollectionFinder::GetInstance();
	$finder->SetClients($clients);
	return $finder; //don't really need to return since singleton
}

function GenerateAccountHandler(array $accounts): AccountHandler
{
	$handler = AccountHandler::GetInstance();
	$handler->SetAccounts($accounts);
	return $handler; //don't really need to return since singleton
}

//this function does not need to exist
function GenerateAPI(): API
{
	//nothing needs to be set for the api
	//due to singletons
	return API::GetInstance();
}

//if namespace visibility was thing this would be the only public function
function PopulateObjects(): void
{
	$visitors = GenerateVisitors();
	$itemReviews = GenerateItemReviews($visitors);
	$items = GenerateItems($itemReviews);
	$collectionReviews = GenerateCollectionReviews($visitors);
	$collections = GenerateCollections($items, $collectionReviews);
	$clients = GenerateClients($collections);
	$collectionFinder = GenerateCollectionFinder($clients);
	$users = array_merge($visitors, $clients);
	$accountHandler = GenerateAccountHandler($users);
}

$tmp = GenerateVisitors();
$tmp2 = GenerateItemReviews($tmp);
$tmp3 = GenerateItems($tmp2);
$tmp4 = GenerateCollectionReviews($tmp);
$tmp5 = GenerateCollections($tmp3, $tmp4);
$tmp6 = GenerateClients($tmp5);
$tmp7 = GenerateCollectionFinder($tmp6);
$tmp8 = array_merge($tmp, $tmp6);
$tmp9 = GenerateAccountHandler($tmp8);
