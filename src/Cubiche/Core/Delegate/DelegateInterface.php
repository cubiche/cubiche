<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Delegate;

/**
 * Delegate Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface DelegateInterface extends CallableInterface
{
    /**
     * @return mixed
     */
    public function __invoke();

    /**
     * @param array $args
     *
     * @return mixed
     */
    public function invokeWith(array $args);
}
