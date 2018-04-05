<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer;

use Cubiche\Core\Serializer\Context\ContextInterface;

/**
 * Serializer interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface SerializerInterface
{
    /**
     * @param mixed                 $data
     * @param ContextInterface|null $context
     *
     * @return mixed
     */
    public function serialize($data, ContextInterface $context = null);

    /**
     * @param mixed                 $data
     * @param string                $type
     * @param ContextInterface|null $context
     *
     * @return mixed
     */
    public function deserialize($data, $type, ContextInterface $context = null);
}
