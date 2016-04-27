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
 * Promisor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface PromisorInterface
{
    /**
     * @return PromiseInterface
     */
    public function promise();
}
