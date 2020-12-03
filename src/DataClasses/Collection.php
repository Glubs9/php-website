<?php
//this file describes the Collection class which holds all the information for collections
declare(strict_types=1);
namespace DataClasses;
require_once "Reviewable.php";
require_once "Review.php";

class Collection extends Reviewable
{
	public array $Items; //although items could be private it is far more convient to have it public (it really should be for general encapsulation purposes)
	public string $Name;
	public string $Description;
	public string $ImageUrl;
	public string $DateCreated; //I think sql stores dates as strings???

	public function __construct(array $Items, array $reviews, string $Name, string $Description, string $ImageUrl, string $DateCreated, int $Id)
	{
		$this->Items = $Items;
		$this->Name = $Name;
		$this->Description = $Description;
		$this->ImageUrl = "../Images/$ImageUrl";
		$this->DateCreated = $DateCreated;
		parent::__construct($reviews, $Id);
	}

	public function ContainsItem(Item $item): bool
	{
		//average case time complexity of this function could be improved but i liked
		//higher order funcitons enough to ignore it.
		$checkFunc = function (Item $n) use ($item) {
			return Item::Match($n, $item);
		};
		return count(array_filter($this->Items, $checkFunc)) == 1; //shouldn't be higher than one
	}
}
