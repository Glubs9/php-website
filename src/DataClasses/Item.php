<?php
//this file describes the item class used to hold items
declare(strict_types=1);
namespace DataClasses;
require_once "Reviewable.php";
require_once "Review.php";
require_once "Matchable.php";

//holds item information
class Item extends Reviewable implements Matchable
{
	public string $ItemName;
	public string $ItemDescription;
	public string $ItemCreationDate; //are dates stored as strings in sql, how do php dates work?
	public string $ItemCollectedDate;
	public string $ItemImageUrl;

	public function __construct(string $ItemName, string $ItemDescription, string $ItemCreationDate, string $ItemCollectedDate, string $ItemImageUrl, array $reviews, int $Id)
	{
		$this->ItemName = $ItemName;
		$this->ItemDescription = $ItemDescription;
		$this->ItemCreationDate = $ItemCreationDate;
		$this->ItemCollectedDate = $ItemCollectedDate;
		$this->ItemImageUrl = "../Images/$ItemImageUrl";
		parent::__construct($reviews, $Id);
	}

	//should probably be implemented on reviewable???
	public static function Match($item1, $item2): bool
	{
		return $item1->Id == $item2->Id;
	}
}
