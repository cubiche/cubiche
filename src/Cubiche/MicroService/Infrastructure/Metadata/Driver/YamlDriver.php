<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\Metadata\Driver;

use Cubiche\Core\Metadata\ClassMetadataInterface;
use Cubiche\Core\Metadata\Driver\AbstractYamlDriver;
use Cubiche\Core\Metadata\MethodMetadata;

/**
 * YamlDriver class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class YamlDriver extends AbstractYamlDriver
{
    /**
     * {@inheritdoc}
     */
    protected function addMetadataFor(array $config, ClassMetadataInterface $classMetadata)
    {
        if (isset($config['permissions'])) {
            $permissions = $config['permissions'];
            if (!is_array($config['permissions'])) {
                $permissions = array($config['permissions']);
            }

            $classMetadata->addMetadata('permissions', $permissions);
        }

        if (isset($config['methods'])) {
            foreach ($config['methods'] as $methodName => $mapping) {
                $permissions = $mapping;
                if (!is_array($mapping)) {
                    $permissions = array($mapping);
                }

                $methodMedatada = new MethodMetadata($classMetadata->className(), $methodName);
                $methodMedatada->addMetadata('permissions', $permissions);

                $classMetadata->addMethodMetadata($methodMedatada);
            }
        }
    }
}
