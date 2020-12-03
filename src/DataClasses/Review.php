<?php
//holds the review information
declare(strict_types=1);
namespace DataClasses;
require_once "Visitor.php"; //this might cause a circular dependency
require_once "Matchable.php";

class Review implements Matchable
{
	public int $Rating;
	public string $ReviewText;
	public object $Reviewer; //reviewer has type of Visitor. this has no type hint as the reviewer gets passed around in json so it loses it's class information

	public function __construct(int $Rating, $Reviewer, ?string $ReviewText)
	{
		$this->Rating = $Rating;
		$this->Reviewer = $Reviewer;
		$this->ReviewText = is_null($ReviewText) ? "" : $ReviewText; //the database can return null for reviews without text
	}

	//note: as I am not keeping track of the items review id this function has a chance of
	//matching two different reviews. Although thsi should not happen as match should only be
	//called with two reviews on the same object.
	//This means I should move this to reviewable.
	public static function Match($reviewOne, $reviewTwo): bool
	{
		return ($reviewOne->Rating == $reviewTwo->ReviewerName)
		    && ($reviewOne->ReviewText == $reviewTwo->ReviewText)
		    && Visitor::Match($reviewOne->Reviewer, $reviewTwo->Reviewer);
	}
}
