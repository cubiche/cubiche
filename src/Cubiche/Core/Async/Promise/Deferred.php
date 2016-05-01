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
 * Deferred class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Deferred extends ObservableResolver implements DeferredInterface
{
    use CancelDeferredTrait;

    /**
     * @var PromiseInterface
     */
    protected $promise;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(null, null, null);

        $this->promise = null;
    }

    /**
     * {@inheritdoc}
     */
    public function promise()
    {
        if ($this->promise === null) {
            $this->promise = new Promise(
                function (callable $resolveCallback) {
                    $this->resolveCallback = $resolveCallback;
                },
                function (callable $rejectCallback) {
                    $this->rejectCallback = $rejectCallback;
                },
                function (callable $notifyCallback) {
                    $this->notifyCallback = $notifyCallback;
                }
            );
        }

        return $this->promise;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($value = null)
    {
        $this->promise();

        parent::resolve($value);
    }

    /**
     * {@inheritdoc}
     */
    public function reject($reason = null)
    {
        $this->promise();

        parent::reject($reason);
    }

    /**
     * {@inheritdoc}
     */
    public function notify($state = null)
    {
        $this->promise();

        parent::notify($state);
    }
}
