<?php
//this file describes the account handler class / singleton
declare(strict_types=1);
namespace DataClasses;
require_once "User.php";
require_once "Visitor.php";
require_once "CollectionFinder.php";

//although singletones are usually bad practice I thought that it would be important to ensure
//only one account handler exists at once. This is because the accounts contains in the account
//handler should be all the accounts and if we have more than one it breaks that important
//require_oncement.
//tldr; it matters enough semantically
class AccountHandler
{
	private static ?AccountHandler $instance = null;
	public array $Accounts;
	private CollectionFinder $collectionFinder; //change name

	private function __construct()
	{
		$this->collectionFinder = CollectionFinder::GetInstance();
	}

	public function SetAccounts(array $Accounts): void
	{
		$this->Accounts = $Accounts;
	}

	public static function GetInstance(): AccountHandler
	{
		if (self::$instance == null){
			self::$instance = new AccountHandler();
		}
		return self::$instance;
	}

	public function Login(string $username, string $password): User
	{
		$tmpArr = $this->Accounts;
		$checkFunc = function(User $test) use ($username, $password) : bool {
			return $test->Validate($username, $password);
		};

		$tmpArr = array_values(array_filter($tmpArr, $checkFunc));
		$user = $tmpArr[0];
		return $user;
	}

	public function CreateAccount(Visitor $visitorIn): void
	{
		//check username
		array_push($this->Accounts, $visitorIn);
		//update database
	}

	public function BanAccount(Visitor $visitorIn): void
	{
		$tmpArr = $this->Accounts;
		$checkFunc = function(User $test): bool {
			return User::match($test, $visitorIn);
		};
		$user = array_values(array_filter($tmpArr, $checkFunc))[0];
		$user->Banned = True;
	}
}
