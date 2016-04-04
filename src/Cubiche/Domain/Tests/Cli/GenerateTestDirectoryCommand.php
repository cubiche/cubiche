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
 * GenerateTestDirectoryCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class GenerateTestDirectoryCommand extends BaseCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('generate:test:directory')
            ->setDescription('Generate a test classes based on a directory')
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'The directory to generate a test classes for'
            )
        ;

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function getGenerators(InputInterface $input)
    {
        $directoryName = $input->getArgument('directory');
        $directory = realpath($directoryName);

        if (!is_dir($directory)) {
            throw new \RuntimeException(
                sprintf(
                    'Invalid directory path "%s".',
                    $directory
                )
            );
        }

        $objects = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $generators = [];
        foreach ($objects as $fileName => $object) {
            if (is_file($fileName) && !$this->isTestFile($fileName)) {
                $info = pathinfo($fileName);
                if ($info['extension'] !== 'php') {
                    continue;
                }

                $classes = ClassUtils::getClassesInFile($fileName);
                if (empty($classes)) {
                    $interfaces = ClassUtils::getInterfacesInFile($fileName);
                    if (!empty($interfaces)) {
                        continue;
                    }

                    throw new \RuntimeException(
                        sprintf(
                            'Could not find class in "%s".',
                            $fileName
                        )
                    );
                }

                foreach ($classes as $className => $classMetadata) {
                    $testCaseGenerator = new TestCaseGenerator($className, $this->getTestsDirectoryName());
                    if (!is_file($testCaseGenerator->getTargetSourceFile())) {
                        $generators[] = $testCaseGenerator;
                    }

                    $generators[] = new TestGenerator($className, $this->getTestsDirectoryName());
                }
            }
        }

        return $generators;
    }

    /**
     * @param $fileName
     *
     * @return bool
     */
    protected function isTestFile($fileName)
    {
        return strpos($fileName, $this->getTestsDirectoryName()) !== false;
    }
}
