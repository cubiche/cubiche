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
use Cubiche\Domain\Tests\Generator\TestCaseGenerator;
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

        if (empty($classes)) {
            throw new \RuntimeException(
                sprintf(
                    'Could not find class in "%s".',
                    $fileName
                )
            );
        }

        $generators = [];
        foreach ($classes as $className => $classMetadata) {
            $testCaseGenerator = new TestCaseGenerator($className, $this->getTestsDirectoryName());
            if (!is_file($testCaseGenerator->getTargetSourceFile())) {
                $generators[] = $testCaseGenerator;
            }

            $generators[] = new TestGenerator($className, $this->getTestsDirectoryName());
        }

        return $generators;
    }
}
