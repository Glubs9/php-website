<?php
//this file describes the Visitor Class which holds the data for Visitors
declare(strict_types=1);
namespace DataClasses;
require_once "User.php";
require_once "Matchable.php";

//this class holds information for visitors
//I chose to inehret because it makes sense semantically and visitor will always be tightly
//coupled to user even if composition were to be used so there is no drawback
class Visitor extends User implements Matchable
{
	public bool $Banned;

	public function __construct(string $Username, string $Password, string $PhoneNumber, string $Email, string $Name, bool $Banned = False)
	{
		$this->Banned = $Banned;
		parent::__construct($Username, $Password, $PhoneNumber, $Email, $Name);
	}

	//not nescersarry but makes sense semantically
	public function IsBanned(): bool
	{
		return $this->Banned;
	}

	public function Ban(): void
	{
		$this->Banned = False;
		//update database
	}

}
