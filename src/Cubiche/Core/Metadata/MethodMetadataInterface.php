<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata;

/**
 * MethodMetadata interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MethodMetadataInterface extends \Serializable
{
    /**
     * @return string
     */
    public function className();

    /**
     * @return string
     */
    public function methodName();

    /**
     * @return \ReflectionMethod
     */
    public function reflection();

    /**
     * @return array
     */
    public function metadata();

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function addMetadata($key, $value);

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getMetadata($key);

    /**
     * @param object $obj
     * @param array  $args
     *
     * @return mixed
     */
    public function invoke($obj, array $args = array());
}
