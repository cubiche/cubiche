<?php

use Cubiche\Core\Enumerable\Enumerable;

require 'vendor/autoload.php';
require 'utils.php';

echo 'The distinct method example.'.PHP_EOL;

$sequence = sequence(15, 0, 5);
$distinct = Enumerable::from($sequence)->distinct();

echo '- The original sequence: '.sequenceToString($sequence).PHP_EOL;
echo '- The distinct sequence: '.sequenceToString($distinct).PHP_EOL;

/*#########################################################################*/
echo 'The except method example.'.PHP_EOL;

$first = sequence(15, 0, 5);
$second = sequence(5, 2, 4);
$except = Enumerable::from($first)->except($second);

echo '- The first sequence: '.sequenceToString($first).PHP_EOL;
echo '- The second sequence: '.sequenceToString($second).PHP_EOL;
echo '- The except sequence: '.sequenceToString($except).PHP_EOL;

/*#########################################################################*/
echo 'The intersect method example.'.PHP_EOL;

$first = sequence(15, 0, 5);
$second = sequence(5, 2, 4);
$intersect = Enumerable::from($first)->intersect($second);

echo '- The first sequence: '.sequenceToString($first).PHP_EOL;
echo '- The second sequence: '.sequenceToString($second).PHP_EOL;
echo '- The intersect sequence: '.sequenceToString($intersect).PHP_EOL;

/*#########################################################################*/
echo 'The union method example.'.PHP_EOL;

$first = sequence(10, 0, 5);
$second = sequence(10, 4, 8);
$union = Enumerable::from($first)->union($second);

echo '- The first sequence: '.sequenceToString($first).PHP_EOL;
echo '- The second sequence: '.sequenceToString($second).PHP_EOL;
echo '- The union sequence: '.sequenceToString($union).PHP_EOL;
