<?php
//This file describes the list monad bind function/operator.
//This function might exist within the php standard library but I can't find it.
//This function is complicated to describe in words so please try to understand the code above the
//explanation.
//The bind function takes a list and a function and calls that function on each item in the list.
//The list is then flattened, i.e: [[1,2],[3]] -> [1,2,3].
declare(strict_types=1);
namespace PHPIncludes;

function flatten(array $list): array
{
	$ret = [];
	foreach($list as $n) {
		array_push($ret, ...$n);
	}
	return $ret;
}

function ListBind(array $list, callable $f): array
{
	$tmp = [];
	foreach($list as $n) {
		array_push($tmp, $f($n));
	}
	return flatten($tmp);
}

?>
