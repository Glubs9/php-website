<?php
//this file describes the api class / singleton
//this class handles the interactions between the outside world / website and the object system / database.
//The api should be handling any changes that get made to the objects.
//The code outside the api MUST never make any changes to the objects.
declare(strict_types=1);
namespace DataClasses;
require_once "AccountHandler.php";
require_once "CollectionFinder.php";
require_once "Visitor.php";
require_once "Review.php";
use \DataClasses\Review as ReviewClass;
require_once "DatabaseFunctions.php";
require_once "DatabaseReader.php";
//TODO: Error handling and verification/validation

//doesn't really *need* to be a singleton as it should only get created once but it matters
//semantically
class API
{
	private static ?API $instance = null;

	private function __construct()
	{
		DatabaseReader\PopulateObjects();
	}

	public static function GetInstance(): API
	{
		if (self::$instance == null){
			self::$instance = new API();
		}

		return self::$instance;
	}

	//takes username and password and returns the account matching those
	public function Login(string $username, string $password): User
	{
		return AccountHandler::GetInstance()->Login($username, $password);
	}

	//takes parameters creates a new visitor, adds it to the account manager and returns the
	//visitor created.
	public function CreateAccount(string $Username, string $Password, string $Name, string $Email, string $PhoneNumber): Visitor
	{
		DatabaseFunctions\CreateAccount($Username, $Password, $Name, $Email, $PhoneNumber);
		$addVisitor = new Visitor($Username, $Password, $PhoneNumber, $Email, $Name);
		AccountHandler::GetInstance()->CreateAccount($addVisitor);
		return $addVisitor;
	}

	public function FindCollections(): array
	{
		return CollectionFinder::GetInstance()->FindCollections();
	}

	public function FindItems(): array
	{
		return CollectionFinder::GetInstance()->FindItems();
	}

	public function FindClients(): array
	{
		return CollectionFinder::GetInstance()->FindClients();
	}

	public function FindTopCollection(): Collection
	{
		return CollectionFinder::GetInstance()->FindTopCollection();
	}

	public function FindTopItems(): array
	{
		return CollectionFinder::GetInstance()->FindTopItems(5);
	}

	//reviewee has the type Reviewable, $reviewer has the type $reviewer.
	//there is no type hint here because the reviewable and visitor objects are sent through
	//json so the class information gets destroyed
	public function Review($reviewee, $reviewer, int $rating, string $reviewText): void
	{
		DatabaseFunctions\Review($reviewee, $reviewer, $rating, $reviewText);
		/*$review = new ReviewClass($rating, $reviewer, $reviewText);
		$reviewee->Review($review); */
		//I am having trouble with the jsonized objects so i got lazy and just repopulated
		//the objects after adding the review to the database
		DatabaseReader\PopulateObjects();
	}

	public function GetCollectionById(int $id): Collection
	{
		return CollectionFinder::GetInstance()->GetCollectionById($id);
	}

	public function GetItemById(int $id): Item
	{
		return CollectionFinder::GetInstance()->GetItemById($id);
	}

	public function GetClientByName(string $name): Client
	{
		return CollectionFinder::GetInstance()->GetClientByName($name);
	}

	public function GetCollectionByItem(Item $item): Collection
	{
		return CollectionFinder::GetInstance()->GetCollectionByItem($item);
	}

	//json decoded reviewable and user so we can't put any type hints beyond object
	public function AlreadyReviewed(object $reviewable, object $user): bool
	{
		return Reviewable::AlreadyReviewed($reviewable, $user);
	}
}
