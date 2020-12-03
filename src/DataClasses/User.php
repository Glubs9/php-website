<?php
//this file describe the user class which holds all of the data common to users.
//NOTE I AM REALLY TIRED RN SO MY COMMENTS ARE SHIT COME BACK AND FIX LATER
declare(strict_types=1);
namespace DataClasses;
require_once "Matchable.php";

//this abstract class describes the User which handles the repeated behavior of all users
abstract class User implements Matchable
{
	public string $Username;
	public string $Password; //should be private but match is easier to write if it's public
	public string $PhoneNumber;
	public string $Email;
	public string $Name;

	public function __construct(string $Username, string $Password, string $PhoneNumber, string $Email, string $Name)
	{
		$this->Username = $Username;
		$this->Password = $Password;
		$this->PhoneNumber = $PhoneNumber;
		$this->Email = $Email;
		$this->Name = $Name;
	}

	public function Validate(string $username, string $password): bool
	{
		return $username == $this->Username && $password == $this->Password;
	}

	//although static functions are not the best within an abstract class, this is the best
	//place to put this
	public static function Match($userOne, $userTwo): bool
	{
		return $userOne->Username == $userTwo->Username
		    && get_class($userOne) == get_class($userTwo);
	}
}
