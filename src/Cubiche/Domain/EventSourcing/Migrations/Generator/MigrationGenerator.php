<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations\Generator;

use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\EventSourcing\Versioning\VersionIncrementType;

/**
 * MigrationGenerator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MigrationGenerator
{
    /**
     * @var string
     */
    protected $migrationsDirectory;

    /**
     * MigrationGenerator constructor.
     *
     * @param string $migrationsDirectory
     */
    public function __construct($migrationsDirectory)
    {
        $this->migrationsDirectory = $migrationsDirectory;
    }

    /**
     * @param string               $aggregateClassName
     * @param Version              $version
     * @param VersionIncrementType $type
     *
     * @return int
     */
    public function generate($aggregateClassName, Version $version, VersionIncrementType $type)
    {
        $file = $this->targetSourceFile($aggregateClassName, $version);
        if (!is_file($file)) {
            $directory = dirname($file);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            return file_put_contents($file, $this->render($aggregateClassName, $version, $type));
        }

        $shortFileName = str_replace($this->migrationsDirectory, '...', $file);

        throw new \RuntimeException('The file '.$shortFileName.' already exists.');
    }

    /**
     * @param Version $version
     *
     * @return bool
     */
    public function existsDirectory(Version $version)
    {
        $directory = $this->targetDirectory($version);

        return is_dir($directory);
    }

    /**
     * @param string               $aggregateClassName
     * @param Version              $version
     * @param VersionIncrementType $type
     *
     * @return string
     */
    protected function render($aggregateClassName, Version $version, VersionIncrementType $type)
    {
        $template = new Template(sprintf(
            '%s/Templates/MigrationClass.tpl',
            __DIR__
        ));

        $namespace = 'namespace '.$this->getNamespace($aggregateClassName, $version).";\n";
        $shortClassName = $this->shortClassName($aggregateClassName);
        $migrationClassName = $shortClassName.'Migration';

        $aggregateClassNamespace = $this->getAggregateClassNamespace($aggregateClassName);
        if ($aggregateClassNamespace != '') {
            $aggregateClassNamespace = 'use '.$aggregateClassNamespace.";\n";
        }

        // If class name doesn't has a namespace separator
        if (strpos($aggregateClassName, '\\') === false) {
            $aggregateClassName = '\\'.$shortClassName;
        } else {
            $aggregateClassName = $shortClassName;
        }

        $migrationType = 'VersionIncrementType::MINOR()';
        if ($type == VersionIncrementType::MAJOR()) {
            $migrationType = 'VersionIncrementType::MAJOR()';
        }

        $template->setVar(
            array(
                'namespace' => $namespace,
                'migrationClassName' => $migrationClassName,
                'use' => $aggregateClassNamespace,
                'migrationType' => $migrationType,
                'aggregateClassName' => $aggregateClassName,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
            )
        );

        return $template->render();
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $version
     *
     * @return string
     */
    protected function getNamespace($aggregateClassName, Version $version)
    {
        $reflector = new \ReflectionClass($aggregateClassName);
        if (empty($reflector->getNamespaceName())) {
            return sprintf(
                '%s',
                $this->versionToDirectory($version)
            );
        }

        return sprintf(
            '%s\%s',
            $this->versionToDirectory($version),
            $reflector->getNamespaceName()
        );
    }

    /**
     * @param string $aggregateClassName
     *
     * @return string
     */
    protected function getAggregateClassNamespace($aggregateClassName)
    {
        // If class name has a namespace separator
        if (strpos($aggregateClassName, '\\') !== false) {
            return $aggregateClassName;
        }

        return '';
    }

    /**
     * @param Version $version
     *
     * @return string
     */
    protected function versionToDirectory(Version $version)
    {
        return sprintf(
            'V%s',
            str_replace('.', '_', $version->__toString())
        );
    }

    /**
     * @param string $aggregateClassName
     *
     * @return string
     */
    protected function shortClassName($aggregateClassName)
    {
        // If class name has a namespace separator, only take last portion
        if (strpos($aggregateClassName, '\\') !== false) {
            return substr($aggregateClassName, strrpos($aggregateClassName, '\\') + 1);
        }

        return $aggregateClassName;
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $version
     *
     * @return string
     */
    protected function targetSourceFile($aggregateClassName, Version $version)
    {
        return sprintf(
            '%s/%s',
            $this->targetDirectory($version),
            str_replace('\\', '/', $aggregateClassName).'Migration.php'
        );
    }

    /**
     * @param Version $version
     *
     * @return string
     */
    protected function targetDirectory(Version $version)
    {
        return sprintf(
            '%s/%s',
            $this->migrationsDirectory,
            $this->versionToDirectory($version)
        );
    }
}
