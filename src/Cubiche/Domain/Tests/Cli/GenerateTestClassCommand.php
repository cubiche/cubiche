<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Cli;

use Cubiche\Domain\Tests\Generator\ClassUtils;
use Cubiche\Domain\Tests\Generator\TestGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * GenerateTestClassCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class GenerateTestClassCommand extends BaseCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('generate:test:class')
            ->setDescription('Generate a test class based on a class')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'The class file to generate a test class for'
            )
        ;

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function getGenerators(InputInterface $input)
    {
        $fileName = $input->getArgument('file');
        $classes = ClassUtils::getClassesInFile($fileName);

        $generators = [];
        foreach ($classes as $className => $classMetadata) {
            // for example: RealTests
            $components = explode('\\', $className);
            $targetClassName = array_pop($components).'Tests';

            // for example: Cubiche/Domain/Core
            $componentPath = $classMetadata['projectName'].DIRECTORY_SEPARATOR.
                $classMetadata['layerName'].DIRECTORY_SEPARATOR.$classMetadata['componentName']
            ;

            // for example: src
            $begining = substr($fileName, 0, strpos($fileName, $componentPath) - 1);

            // for example: src/Cubiche/Domain/Core/Tests/Units/RealTests.php
            $targetSourceFile = $begining.DIRECTORY_SEPARATOR.
                $componentPath.DIRECTORY_SEPARATOR.
                'Tests'.DIRECTORY_SEPARATOR.
                'Units'.DIRECTORY_SEPARATOR.
                $targetClassName.'.php'
            ;

            $generators[] = new TestGenerator($className, '', $targetClassName, $targetSourceFile);
        }

        return $generators;
    }
}
