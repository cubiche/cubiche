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
 * PropertyMetadata interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface PropertyMetadataInterface extends \Serializable
{
    /**
     * @return string
     */
    public function className();

    /**
     * @return string
     */
    public function propertyName();

    /**
     * @return \ReflectionProperty
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
     *
     * @return mixed
     */
    public function getValue($obj);

    /**
     * @param object $obj
     * @param string $value
     */
    public function setValue($obj, $value);
}
