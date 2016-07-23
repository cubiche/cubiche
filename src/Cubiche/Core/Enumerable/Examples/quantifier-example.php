<?php

use Cubiche\Core\Enumerable\Enumerable;
use Cubiche\Core\Predicate\Criteria;

require 'vendor/autoload.php';
require 'utils.php';

echo 'The all method example.'.PHP_EOL;

$sequence = sequence(15, 0, 10, true);
$result = Enumerable::from($sequence)->all(Criteria::gte(0)->and(Criteria::lte(10)));

echo '- The sequence: '.sequenceToString($sequence).PHP_EOL;
echo '- all(Criteria::gte(0)->and(Criteria::lte(10)))? '.boolToString($result).PHP_EOL;

/*#########################################################################*/

echo 'The any method example.'.PHP_EOL;

$sequence = sequence(15, 0, 10, true);
$result1 = Enumerable::from($sequence)->any(Criteria::eq(5));
$result2 = Enumerable::from($sequence)->any(Criteria::eq(11));

echo '- The sequence: '.sequenceToString($sequence).PHP_EOL;
echo '- any(Criteria::eq(5))? '.boolToString($result1).PHP_EOL;
echo '- any(Criteria::eq(11))? '.boolToString($result2).PHP_EOL;

/*#########################################################################*/

echo 'The atLeast method example.'.PHP_EOL;

$sequence = sequence(15, 0, 10, true);
$result1 = Enumerable::from($sequence)->atLeast(5, Criteria::gte(2)->and(Criteria::lte(6)));
$result2 = Enumerable::from($sequence)->atLeast(2, Criteria::eq(5));

echo '- The sequence: '.sequenceToString($sequence).PHP_EOL;
echo '- atLeast(5, Criteria::gte(2)->and(Criteria::lte(6)))? '.boolToString($result1).PHP_EOL;
echo '- atLeast(2, Criteria::eq(5))? '.boolToString($result2).PHP_EOL;

/*#########################################################################*/

echo 'The contains method example.'.PHP_EOL;

$sequence = sequence(15, 0, 10, true);
$result1 = Enumerable::from($sequence)->contains(5);
$result2 = Enumerable::from($sequence)->contains(10);

echo '- The sequence: '.sequenceToString($sequence).PHP_EOL;
echo '- contains(5)? '.boolToString($result1).PHP_EOL;
echo '- contains(10)? '.boolToString($result2).PHP_EOL;
