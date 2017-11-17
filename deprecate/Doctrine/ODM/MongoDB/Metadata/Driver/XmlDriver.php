<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Driver;

use Cubiche\Core\Metadata\Driver\AbstractXmlDriver;

/**
 * XmlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class XmlDriver extends AbstractXmlDriver
{
    /**
     * {@inheritdoc}
     */
    protected function loadMappingFile($file)
    {
        $result = array();
        $xmlElement = simplexml_load_file($file);
        foreach (array('document', 'embedded-document', 'mapped-superclass') as $type) {
            if (isset($xmlElement->$type)) {
                foreach ($xmlElement->$type as $documentElement) {
                    $documentName = (string) $documentElement['name'];
                    $result[$documentName] = $documentElement;
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return '.mongodb.xml';
    }
}
