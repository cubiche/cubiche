<?php

use Cubiche\Core\Comparable\Direction;
use Cubiche\Core\Enumerable\Enumerable;
use Cubiche\Core\Enumerable\Order;

require 'vendor/autoload.php';
require 'utils.php';

echo 'The sorted method example.'.PHP_EOL;

$sequence = sequence(15, 0, 10, true);
$sorted = Enumerable::from($sequence)->sorted();
$reverse = Enumerable::from($sequence)->sorted(Order::defaultComparator()->reverse());

echo '- The original sequence: '.sequenceToString($sequence).PHP_EOL;
echo '- The sorted sequence: '.sequenceToString($sorted).PHP_EOL;
echo '- The reverse sorted sequence: '.sequenceToString($reverse).PHP_EOL;

/*#########################################################################*/
echo 'The sorting persons example.'.PHP_EOL;

$sequence = persons(10);
$result1 = Enumerable::from($sequence)->sorted(Order::by('age'));
$result2 = Enumerable::from($sequence)->sorted(Order::by('height', Direction::DESC()));
$result3 = Enumerable::from($sequence)->sorted(
    Order::by('age')
    ->otherwise(Order::by('height', Direction::DESC()))
    ->otherwise(Order::by('weight'))
);

echo '- The original sequence: '.PHP_EOL.sequenceToString($sequence, true).PHP_EOL;
echo "- The sorted(Order::by('age')) sequence: ".PHP_EOL.sequenceToString($result1, true).PHP_EOL;
echo "- The sorted(Order::by('height', Direction::DESC())) sequence: ".PHP_EOL.sequenceToString($result2, true).PHP_EOL;
echo '- The sequence sorted by age or by height(DESC) or by weight: '.PHP_EOL.sequenceToString($result3, true).PHP_EOL;
