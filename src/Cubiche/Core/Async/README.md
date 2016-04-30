# Cubiche/Async
[![Build Status](https://travis-ci.org/cubiche/async.svg?branch=master)](https://travis-ci.org/cubiche/async) [![Coverage Status](https://coveralls.io/repos/github/cubiche/async/badge.svg?branch=master)](https://coveralls.io/github/cubiche/async?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cubiche/async/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cubiche/async/?branch=master) 

An Event loop abstraction layer,  built on top of [React PHP/Event Loop](https://github.com/reactphp/event-loop), and
a lightweight implementation of [Promises/A+](https://promisesaplus.com/) for PHP.

## Installation

Via [Composer](http://getcomposer.org/)

```bash
$ composer require cubiche/async:dev-master
```

## The Promise API
The [Promises/A+](https://promisesaplus.com/) proposal describes a promise as an interface for interacting with an object that represents the result of an action that is performed asynchronously, and may or may not be finished at any given point in time.
The purpose of the promise object is to allow for interested parties to get access to the result of the deferred task when it completes.

Methods:

  * `PromiseInterface::then(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)` –  regardless of when the promise was or will be resolved or rejected, then calls one of the `$onFulfilled` or `$onRejected` callbacks asynchronously as soon as the result is available. The callbacks are called with a single argument: the result value or rejection reason. Additionally, the `$onNotify` callback may be called zero or more times to provide a progress indication, before the promise is resolved or rejected.
  
  This method returns a new promise which is resolved or rejected via the return value of the `$onFulfilled` or `$onRejected` callbacks.
  * `PromiseInterface::otherwise(callable $onRejected)` – shorthand for `PromiseInterface::then(null, $onRejected)`.
  * `PromiseInterface::always(callable $onFulfilledOrRejected, callable $onNotify = null)` – allows you to observe either the fulfillment or rejection of a promise, but to do so without modifying the final value. This is useful to release resources or do some clean-up that needs to be done whether the promise was rejected or resolved.
  * `PromiseInterface::state()` – returns the promise state (`State::PENDING()`, `State::FULFILLED()`, or `State::REJECTED()`).

## The Deferred API

The purpose of the deferred object is to expose the associated Promise instance as well as APIs that can be used for signaling the successful or unsuccessful completion, as well as the status of the task.

Methods:

  * `DeferredInterface::resolve($value = null)` – resolves the derived promise with the `$value`.
  * `DeferredInterface::reject($reason = null)` – rejects the derived promise with the `$reason`.
  * `DeferredInterface::notify($state = null)` – provides updates on the status of the promise's execution. This may be called multiple times before the promise is either resolved or rejected.
  * `DeferredInterface::promise()` – returns the promise object associated with this deferred.

## Basic usage

Usage example with [React PHP/Event Loop](https://github.com/reactphp/event-loop)
```php
<?php

require 'vendor/autoload.php';

use Cubiche\Core\Async\Promise\Promises;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\EventLoop\Timer\Timer;

$loop = Factory::create();
$promise = asyncTask($loop);

$promise->then(function($message){
    echo $message. ' Done!'. PHP_EOL; 
});

$loop->run();

function asyncTask(LoopInterface $loop)
{
    $deferred = Promises::defer();
    
    $loop->addTimer(1, function(Timer $timer) use ($deferred) {
        $deferred->resolve('hello world!');
    });
    
    return $deferred->promise();
}
```
Same usage example with [Cubiche/Async](https://github.com/cubiche/async)
```php
<?php

require 'vendor/autoload.php';

use Cubiche\Core\Async\Loop\Loop;
use Cubiche\Core\Async\Loop\LoopInterface;
use Cubiche\Core\Async\Loop\Timer\TimerInterface;

$loop = new Loop();

//timer is a Promise
$timer = asyncTask($loop);

$timer->then(function($message){
    echo $message. ' Done!'. PHP_EOL; 
});

$loop->run();

function asyncTask(LoopInterface $loop)
{
    return $loop->timeout(function(TimerInterface $timer) {
        return 'hello world!';
    }, 1);
}
```
## Matrix multiplication example

```php
<?php

require 'vendor/autoload.php';

use Cubiche\Core\Async\Loop\Loop;
use Cubiche\Core\Async\Loop\LoopInterface;
use Cubiche\Core\Async\Promise\Promises;

$loop = new Loop();

$n = 10;
$a = matrix($n);
$b = matrix($n);

$promise = mult($a, $b, $n, $loop);

$promise->then(function($c) use ($n){
    echo 'Result matrix:'. PHP_EOL;
    for ($i = 0; $i < $n; $i++){
        echo '('. implode(',', $c[$i]). ')' . PHP_EOL; 
    }
    echo 'Done!'. PHP_EOL; 
});

$loop->run();

function mult($a, $b, $n, LoopInterface $loop)
{
    $promises = array();
    $c = array();
    for ($i = 0; $i < $n; $i++){
        $c[$i] = array();
        for ($j = 0; $j < $n; $j++){
            //scheduling in random order
            $delay = (float)\rand()/(float)\getrandmax();
            $timer = $loop->timeout(function() use ($a, $b, $i, $j, $n){
                return multRowCol($a, $b, $i, $j, $n);
            }, $delay);
            
            $promises[] = $timer->then(function ($value) use(&$c, $i, $j){
                echo 'seting c['.$i. ', ' . $j . '] = '. $value . PHP_EOL;
                $c[$i][$j] = $value; 
            });
        }
    }
    
    return Promises::all($promises)->then(function () use (&$c){
        return $c;
    });
}

function multRowCol($a, $b, $i, $j, $n)
{
    echo 'calculating c['.$i. ', ' . $j . ']' . PHP_EOL;
    $sum = 0;
    for ($k = 0; $k < $n; $k++) {
        $sum += $a[$i][$k] * $b[$k][$j];
    }
    return $sum;
}

function matrix($n)
{
    $matrix = array();
    for ($i = 0; $i < $n; $i++){
        $matrix[$i] = array();
        for ($j = 0; $j < $n; $j++){
            $matrix[$i][$j] = \rand(1, 10);
        }
    }
    return $matrix;
}
```
## React PHP Adapter example

To run this example you need to install [React PHP/Dns](https://github.com/reactphp/dns)
```bash
$ composer require react/dns:~0.4.0
```

```php
<?php

require 'vendor/autoload.php';

use Cubiche\Core\Async\Loop\LoopAdapter;
use Cubiche\Core\Async\Promise\DeferredInterface;
use Cubiche\Core\Async\Promise\Promises;
use Cubiche\Core\Async\Promise\PromisorInterface;
use Cubiche\Core\Async\Promise\State;
use React\Dns\Resolver\Factory;
use React\Promise\PromiseInterface as ReactPromiseInterface;

class PromisorAdapter implements PromisorInterface{

    /**
     * @var DeferredInterface
     */
    protected $deferred;

    /**
     * @param ReactPromiseInterface $promise
     */
    public function __construct(ReactPromiseInterface $promise)
    {
        $this->deferred = Promises::defer();
        $promise->then(function($value){
            $this->deferred->resolve($value);
        }, function ($reason){
            $this->deferred->reject($reason);
        }, function ($state){
            $this->deferred->notify($state);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function promise()
    {
        return $this->deferred->promise();
    }
}

$loop = new LoopAdapter();
$factory = new Factory();
$dns = $factory->create('8.8.8.8', $loop);

$domains = array('github.com', 'google.com', 'amazon.com');
$promises = array();
foreach ($domains as $domain) {
    $reactPromise = $dns->resolve($domain);
    $promisor = new PromisorAdapter($reactPromise);
    $promises[$domain] = $promisor->promise();
}

//waiting for all promises and get the result
$hots = Promises::get(Promises::all($promises), $loop);
foreach ($hots as $domain => $host) {
    echo 'Domain: '. $domain. ' Host: '. $host. PHP_EOL;
}
```


##Authors

* [Karel Osorio Ramírez](https://github.com/osorioramirez)
* [Ivannis Suárez Jérez](https://github.com/ivannis)