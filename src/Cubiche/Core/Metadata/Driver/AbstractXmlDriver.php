<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Metadata\Driver;

use Metadata\Driver\AbstractFileDriver;

/**
 * AbstractXmlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractXmlDriver extends AbstractFileDriver
{
    /**
     * @param \SimpleXMLElement $item
     * @param array             $default
     *
     * @return array
     */
    protected function extractElementAttributes(\SimpleXMLElement $item, array $default = array())
    {
        $mapping = $default;
        foreach ($item->attributes() as $key => $value) {
            $mapping[$key] = (string) $value;
        }

        return $mapping;
    }

    /**
     * Loads a mapping file with the given name and returns a map
     * from class/entity names to their corresponding file driver elements.
     *
     * @param string $file The mapping file to load.
     *
     * @return array
     */
    abstract protected function loadMappingFile($file);
}
