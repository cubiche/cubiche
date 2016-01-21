<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\Async;

use Cubiche\Domain\System\Delegate;

/**
 * Promise Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface PromiseInterface
{
    /**
     * @param Delegate $succeed
     * @param Delegate $rejected
     * @param Delegate $notify
     *
     * @return PromiseInterface
     */
    public function then(Delegate $succeed = null, Delegate $rejected = null, Delegate $notify = null);

    /**
     * @param Delegate $catch
     *
     * @return PromiseInterface
     */
    public function otherwise(Delegate $catch);

    /**
     * @param Delegate $finally
     *
     * @return PromiseInterface
     */
    public function always(Delegate $finally, Delegate $notify = null);
}
