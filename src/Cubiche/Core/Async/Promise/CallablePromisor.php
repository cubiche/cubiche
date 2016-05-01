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

use Cubiche\Core\Delegate\Delegate;

/**
 * Callable Promisor class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CallablePromisor extends Delegate implements PromisorInterface
{
    /**
     * @var Delegate
     */
    protected $delegate;

    /**
     * @var DeferredInterface
     */
    protected $deferred = null;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        parent::__construct(array($this, 'execute'));

        $this->delegate = new Delegate($callable);
    }

    /**
     * {@inheritdoc}
     */
    public function promise()
    {
        return $this->deferred()->promise();
    }

    protected function execute()
    {
        try {
            $this->deferred()->resolve($this->delegate->invokeWith(\func_get_args()));
        } catch (\Exception $e) {
            $this->deferred()->reject($e);
        }
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
