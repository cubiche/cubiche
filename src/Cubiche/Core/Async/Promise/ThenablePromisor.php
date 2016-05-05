<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Promise;

/**
 * Thenable Promisor class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ThenablePromisor implements PromisorInterface
{
    /**
     * @var DeferredInterface
     */
    protected $deferred = null;

    /*
     * @param ThenableInterface $thenable
     */
    public function __construct(ThenableInterface $thenable)
    {
        $thenable->then(function ($value) {
            $this->deferred()->resolve($value);
        }, function ($reason) {
            $this->deferred()->reject($reason);
        }, function ($state) {
            $this->deferred()->notify($state);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function promise()
    {
        return $this->deferred()->promise();
    }

    /**
     * @return \Cubiche\Core\Async\Promise\DeferredInterface
     */
    protected function deferred()
    {
        if ($this->deferred === null) {
            $this->deferred = new Deferred();
        }

        return $this->deferred;
    }
}
