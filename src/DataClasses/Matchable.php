<?php
//this file describes the matchable interface which ensures that a class can match two objects
//although there is no case where a match might have to be called without knowing the original type
//this behaviour is still shared between multiple classes so it should exist
declare(strict_types=1);
namespace DataClasses;

interface Matchable
{
	public static function Match(object $ObjectOne, object $ObjectTwo): bool; //could work without the object type hints
}
