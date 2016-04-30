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
 * Done Resolver class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DoneResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve($value = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function reject($reason = null)
    {
        if ($reason === null || !$reason instanceof \Exception) {
            $reason = new RejectionException($reason);
        }

        throw $reason;
    }

    /**
     * {@inheritdoc}
     */
    public function notify($state = null)
    {
    }
}
