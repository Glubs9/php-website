<?php
//holds the client information
declare(strict_types=1);
namespace DataClasses;
require_once "User.php";
require_once "Collection.php";
require_once "Matchable.php";

//The Client holds the data for the client
class Client extends User implements Matchable
{
	public array $Collections;

	public function __construct(string $Username, string $Password, string $PhoneNumber, string $Email, string $Name, array $Collections)
	{
		$this->Collections = $Collections;
		parent::__construct($Username, $Password, $PhoneNumber, $Email, $Name);
	}

	public function AddCollection(Collection $CollectionIn): void
	{
		array_push($this->Collections, $CollectionIn);
	}

}
