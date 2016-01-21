<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\Async;

/**
 * Deferred Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface DeferredInterface
{
    /**
     * @return DeferredInterface
     */
    public static function defer();

    /**
     * @return PromiseInterface
     */
    public function promise();

    /**
     * @param mixed $value
     */
    public function resolve($value = null);

    /**
     * @param mixed $reason
     */
    public function reject($reason = null);

    /**
     * @param mixed $state
     */
    public function notify($state = null);

    /**
     * @return bool
     */
    public function cancel();
}
