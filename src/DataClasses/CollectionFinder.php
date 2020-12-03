<?php
//This file describes the collection finder class which is used to dynamically generated lists of
//collections and items.
//this class / singleton exists because it is not necersarry to store the same information in
//multiple places and so these lists should be generated dynamically
declare(strict_types=1);
namespace DataClasses;
require_once "Client.php";
require_once "../PHPIncludes/ListBind.php";
use function \PHPIncludes\ListBind as ListBind;
use usort, end;

//this class has a lot of very slow methods.
//The speed could (and should) be improved through caching the results / memoization.
class CollectionFinder
{
	private static ?CollectionFinder $instance = null;
	private array $clients;

	private function __construct()
	{
	}

	public function SetClients(array $clients): void
	{
		$this->clients = $clients;
	}

	public static function GetInstance(): CollectionFinder
	{
		if (self::$instance == null) {
			self::$instance = new CollectionFinder();
		}

		return self::$instance;
	}

	public function FindCollections(): array
	{
		$bindFunc = function (Client $clientIn) {
			return $clientIn->Collections;
		};
		return ListBind($this->clients, $bindFunc);
	}

	public function FindItems(): array
	{
		$collections = $this->FindCollections();
		$bindFunc = function (Collection $collectionIn) {
			return $collectionIn->Items;
		};
		return ListBind($collections, $bindFunc);
	}

	public function FindClients(): array
	{
		return $this->clients;
	}

	//this method is quite slow, it would be trivial to make the calls to averagereview from
	//O(n lg n) to O(n) but i can't be bothered rn (or averagereview could be memoized).
	private function sortReviewables(array $reviewables): array
	{
		$sortFunc = function (Reviewable $reviewableOne, Reviewable $reviewableTwo) {
			return $reviewableOne->AverageReview() <=> $reviewableTwo->AverageReview();
		};
		usort($reviewables, $sortFunc);
		return array_reverse($reviewables); //this is used for top items so it works better reversed
	}

	public function FindTopCollection(): Collection
	{
		$collections = $this->FindCollections();
		$best = $this->sortReviewables($collections)[0];
		return $best;
	}

	public function FindTopItems(int $n): array
	{
		$items = $this->FindItems();
		$sorted = $this->sortReviewables($items);
		return array_slice($sorted, 0, $n); 
	}

	public function GetCollectionById(int $id): Collection
	{
		$collections = $this->FindCollections();
		$checkFunc = function (Collection $collection) use ($id) : bool {
			return $collection->Id == $id;
		};
		$collections = array_values(array_filter($collections, $checkFunc));
		return $collections[0];
	}

	public function GetItemById(int $id): Item
	{
		$items = $this->FindItems();
		$checkFunc = function (Item $item) use ($id) : bool {
			return $item->Id == $id;
		};
		$items = array_values(array_filter($items, $checkFunc));
		return $items[0];
	}

	public function GetClientByName(string $name): Client
	{
		$checkFunc = function (Client $c) use ($name) : bool {
			return $c->Username == $name;
		};
		$tmp = array_values(array_filter($this->clients, $checkFunc));
		return $tmp[0];
	}

	public function GetCollectionByItem(Item $item): Collection
	{
		$collections = $this->FindCollections();
		$checkFunc = function (Collection $collection) use ($item) {
			return $collection->ContainsItem($item);
		};
		return array_values(array_filter($collections, $checkFunc))[0];
	}
}
