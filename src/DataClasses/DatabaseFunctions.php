<?php
//this file describes a the database functions namespace
//This namespace handles all the database calls.
//the reason I have this as a separate namespace instead of having the database being updated by the
//objects is that this allows me to track the database calls much better.
//the disadvantage to this approach is that it makes the relationship between the objects and the
//database weaker. It would also introduce loads of new global variables if I didn't use the
//namespace.
//This also introduces a lot of non-oop code but that is not such a big deal in php.
declare(strict_types=1);
namespace DataClasses\DatabaseFunctions; //inner namespace for encapsulation without a class
require_once "Visitor.php";
require_once "Reviewable.php";
require_once "Collection.php";
require_once "Item.php";
require_once "DatabaseConnector.php";
use function \DataClasses\DatabaseConnector\QueryFunc as QueryFunc;

//TODO:
//  error handling (might be a wrapper)

function CreateAccount(string $Username, string $Password, string $Name, string $Email, string $PhoneNumber): void
{
	QueryFunc("INSERT INTO Visitor VALUES ('$Username', '$Password', '$PhoneNumber', '$Email', '$Name', False)"); //Banned = False
}

//the types of reviewee and reviewer are explained in API->Review
function Review($reviewee, $reviewer, int $rating, string $reviewText): void
{
	$tableName = property_exists($reviewee, 'Items') ? "CollectionReview" : "ItemReview";
	//code doesn't work
	QueryFunc("INSERT INTO $tableName VALUES ($reviewee->Id, '$reviewer->Username', $rating, '$reviewText')");
}
