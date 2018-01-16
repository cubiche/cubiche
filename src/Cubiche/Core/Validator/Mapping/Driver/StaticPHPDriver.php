<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Mapping\Driver;

use Cubiche\Core\Metadata\Driver\StaticDriver;
use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * StaticPHPDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class StaticPHPDriver extends StaticDriver
{
    /**
     * @var string
     */
    protected $defaultGroup;

    /**
     * StaticPHPDriver constructor.
     *
     * @param string $methodName
     * @param string $defaultGroup
     * @param array  $paths
     */
    public function __construct(
        $methodName = 'loadValidatorMetadata',
        $defaultGroup = Assert::DEFAULT_GROUP,
        array $paths = array()
    ) {
        parent::__construct($methodName, $paths);

        $this->defaultGroup = $defaultGroup;
    }

    /**
     * {@inheritdoc}
     */
    protected function createClassMetadata($className)
    {
        $classMetadata = new ClassMetadata($className);
        $classMetadata->defaultGroup = $this->defaultGroup;

        return $classMetadata;
    }
}
