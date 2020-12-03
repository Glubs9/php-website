<?php
//This document defines the reviewable abstract class
declare(strict_types=1);
namespace DataClasses;
require_once "Review.php";

//this abstract class could be thought of as an improper use of inheritance.
//this is because the ability for something to be reviewed is really more of an action then a
//class and as such should be an interface or composition.
//Though as this is a single person project the code-reuse alone is worth the semantic issues.
abstract class Reviewable
{
	public array $Reviews;
	public int $Id;

	public function __construct(array $Reviews, int $Id)
	{
		$this->Reviews = $Reviews;
		$this->Id = $Id;
	}

	public function Review(Review $reviewIn): void
	{
		array_push($this->Reviews, $reviewIn);
		//add to database
	}

	//this method removes a review from the list
	//it is used in the case that someone gets banned and the review needs to be removed from
	//the objects. Although this breaks the direct representation of the database that the
	//objects are aiming to achieve, it mirrors the meaning of having a banned users Reviews
	//removed from consideration.
	//this method may not be necersarry
	public function RemoveReview(Review $reviewIn): void
	{
		$checkFunc = function(Review $testReview) use ($reviewIn) : bool {
			return Review::Match($testReview, $reviewIn);
		};

		//this only works becuase each review is unique
		$this->Reviews = array_keys(array_filter($this->Reviews, $checkFunc));
	}

	public function AverageReview(): float
	{
		$ratings = array_map(function (Review $r): int {
			return $r->Rating;
		}, $this->Reviews);

		if (count($this->Reviews) == 0) { //avoids divide by 0 error
			return 0.0;
		} else {
			return array_sum($ratings) / count($this->Reviews);
		}
	}

	//this function is static because the reviewable object has been passed through json so
	//the class information is not retained, same with user
	public static function AlreadyReviewed(object $reviewable, object $user): bool
	{
		//r has the type of review but the class got erased for the same reason as above
		$checkFunc = function(object $r) use ($user) : bool {
			return User::Match($r->Reviewer, $user);
		};
		return count(array_filter($reviewable->Reviews, $checkFunc)) == 1; //doesn't check for two reviews but that shouldn't be possible
	}
}
