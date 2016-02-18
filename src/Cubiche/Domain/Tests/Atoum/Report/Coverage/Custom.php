<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Atoum\Report\Coverage;

use mageekguy\atoum;
use mageekguy\atoum\cli\colorizer;
use mageekguy\atoum\fs\path;
use mageekguy\atoum\report\fields\runner\coverage\cli as Report;
use mageekguy\atoum\score\coverage;
use mageekguy\atoum\template;
use mageekguy\atoum\template\parser;

/**
 * Custom class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Custom extends Report
{
    /**
     * Const.
     */
    const HTML_EXTENSION_FILE = '.html';

    /**
     * @var colorizer
     */
    protected $urlColorizer = null;

    /**
     * @var string
     */
    protected $rootUrl = '';

    /**
     * @var string
     */
    protected $projectName = '';

    /**
     * @var path
     */
    protected $sourceDirectory = null;

    /**
     * @var string
     */
    protected $templatesDirectory = null;

    /**
     * @var string
     */
    protected $destinationDirectory = null;

    /**
     * @var parser
     */
    protected $templateParser = null;

    /**
     * @var \closure
     */
    protected $reflectionClassInjector = null;

    /**
     * Custom constructor.
     *
     * @param $projectName
     * @param $destinationDirectory
     * @param $sourceDirectory
     */
    public function __construct($projectName, $destinationDirectory, $sourceDirectory)
    {
        parent::__construct();

        $this->sourceDirectory = new path($sourceDirectory);

        $this
            ->setProjectName($projectName)
            ->setDestinationDirectory($destinationDirectory)
            ->setUrlColorizer()
            ->setTemplatesDirectory()
            ->setTemplateParser()
            ->setRootUrl('/');
    }

    public function __toString()
    {
        $string = '';
        if (sizeof($this->coverage) > 0) {
            try {
                // clean directory
                $this->cleanDestinationDirectory();

                // generate assets
                $this->generateAssets();

                // get coverage report
                $report = $this->getCoverageReport($this->coverage);

                // generate index page for each directory
                foreach ($report['directories'] as $directoryPath => $directoryData) {
                    // build page
                    $this->buildDirectoryPage(
                        $directoryPath,
                        $directoryData,
                        $report['directories']['/']['relevantLines'],
                        $report['directories']['/']['coveredLines'],
                        $report['directories']['/']['totalLines'],
                        $report['directories']['/']['coverage']
                    );
                }

                // generate class page for each file
                foreach ($report['sources'] as $classData) {
                    // build page
                    $this->buildClassPage(
                        $classData,
                        $report['directories']['/']['relevantLines'],
                        $report['directories']['/']['coveredLines'],
                        $report['directories']['/']['totalLines'],
                        $report['directories']['/']['coverage']
                    );
                }
            } catch (\exception $exception) {
                $string .= $this->urlColorizer->colorize(
                    $this->locale->_(
                        'Unable to generate code coverage at %s: %s.',
                        $this->rootUrl,
                        $exception->getMessage()
                    )
                ).PHP_EOL;
            }
        }

        return $string;
    }

    public function setUrlColorizer(colorizer $colorizer = null)
    {
        $this->urlColorizer = $colorizer ?: new colorizer();

        return $this;
    }

    public function getUrlColorizer()
    {
        return $this->urlColorizer;
    }

    /**
     * @param string $projectName
     *
     * @return $this
     */
    public function setProjectName($projectName)
    {
        $this->projectName = (string) $projectName;

        return $this;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @return path|null
     */
    public function getSourceDirectory()
    {
        return $this->sourceDirectory;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setDestinationDirectory($path)
    {
        $this->destinationDirectory = (string) $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationDirectory()
    {
        return $this->destinationDirectory;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setTemplatesDirectory($path = null)
    {
        $this->templatesDirectory = atoum\directory.DIRECTORY_SEPARATOR.'resources'.
            DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'coverage'
        ;

        if ($path !== null) {
            $this->templatesDirectory = $path;
        };

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplatesDirectory()
    {
        return $this->templatesDirectory;
    }

    /**
     * @param parser $parser
     *
     * @return $this
     */
    public function setTemplateParser(parser $parser = null)
    {
        $this->templateParser = $parser ?: new parser();

        return $this;
    }

    /**
     * @return parser
     */
    public function getTemplateParser()
    {
        return $this->templateParser;
    }

    /**
     * @param string $rootUrl
     *
     * @return $this
     */
    public function setRootUrl($rootUrl)
    {
        $this->rootUrl = (string) $rootUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getRootUrl()
    {
        return $this->rootUrl;
    }

    /**
     * @param coverage $coverage
     *
     * @return array
     */
    protected function getCoverageReport(coverage $coverage)
    {
        $sources = [];
        $directoriesCoverage = [];

        foreach ($coverage->getClasses() as $class => $file) {
            $path = new path($file);

            $source = $this->getSourceCode($path);
            $fileName = $this->getFileName($path);
            $hierarchy = $this->getHierarchy($fileName);

            $linesCoverage = $this->getLinesCoverage($coverage->getCoverageForClass($class));
            foreach ($hierarchy as $item) {
                $itemPath = $item['path'];
                $itemName = $item['name'];
                $itemType = $item['type'];

                if (isset($directoriesCoverage[$itemPath])) {
                    $directoriesCoverage[$itemPath]['relevantLines'] += $linesCoverage['relevantLines'];
                    $directoriesCoverage[$itemPath]['coveredLines'] += $linesCoverage['coveredLines'];
                    $directoriesCoverage[$itemPath]['totalLines'] += count($linesCoverage['lines']);
                } else {
                    $directoriesCoverage[$itemPath] = [
                        'directories' => [],
                        'files' => [],
                        'relevantLines' => $linesCoverage['relevantLines'],
                        'coveredLines' => $linesCoverage['coveredLines'],
                        'totalLines' => count($linesCoverage['lines']),
                    ];
                }

                if ($itemType == 'directory') {
                    $directoriesCoverage[$itemPath]['directories'][$itemName] = [
                        'relevantLines' => 0,
                        'coveredLines' => 0,
                        'totalLines' => 0,
                    ];
                } else {
                    $directoriesCoverage[$itemPath]['files'][$itemName] = [
                        'relevantLines' => $linesCoverage['relevantLines'],
                        'coveredLines' => $linesCoverage['coveredLines'],
                        'totalLines' => count($linesCoverage['lines']),
                        'coverage' => (float) (
                            $linesCoverage['coveredLines'] / $linesCoverage['relevantLines']
                        ),
                    ];
                }
            }

            foreach ($directoriesCoverage as $path => &$directoryCoverageItem) {
                $directoryCoverageItem['coverage'] = (float) (
                    $directoryCoverageItem['coveredLines'] / $directoryCoverageItem['relevantLines']
                );

                foreach ($directoryCoverageItem['directories'] as $directoryName => &$directoryCoverage) {
                    $directoryCoverage['relevantLines'] = $directoriesCoverage[$path.$directoryName.'/']
                        ['relevantLines']
                    ;

                    $directoryCoverage['coveredLines'] = $directoriesCoverage[$path.$directoryName.'/']
                        ['coveredLines']
                    ;

                    $directoryCoverage['totalLines'] = $directoriesCoverage[$path.$directoryName.'/']
                        ['totalLines']
                    ;

                    $directoryCoverage['coverage'] = (float) (
                        $directoryCoverage['coveredLines'] / $directoryCoverage['relevantLines']
                    );
                }
            }

            $sources[] = [
                'name' => $fileName,
                'className' => str_replace(array('.php', '/'), array('', '\\'), $fileName),
                'source' => $source,
                'coverage' => $this->getLinesCoverage($coverage->getCoverageForClass($class)),
            ];
        }

        return [
            'sources' => $sources,
            'directories' => $directoriesCoverage,
            'relevantLines' => $directoriesCoverage['/']['relevantLines'],
            'coveredLines' => $directoriesCoverage['/']['coveredLines'],
        ];
    }

    /**
     * @param array $coverage
     *
     * @return array
     */
    protected function getLinesCoverage(array $coverage)
    {
        $lines = [];
        $relevantLines = 0;
        $coveredLines = 0;

        foreach ($coverage as $method) {
            foreach ($method as $number => $line) {
                if ($number > 1) {
                    while (sizeof($lines) < ($number - 1)) {
                        $lines[] = null;
                    }
                }

                if ($line === 1) {
                    ++$relevantLines;
                    ++$coveredLines;
                    $lines[] = 1;
                } elseif ($line >= -1) {
                    ++$relevantLines;
                    $lines[] = 0;
                }
            }
        }

        return [
            'totalLines' => count($lines),
            'relevantLines' => $relevantLines,
            'coveredLines' => $coveredLines,
            'coverage' => (float) ($coveredLines / $relevantLines),
            'lines' => $lines,
        ];
    }

    /**
     * @param path $path
     *
     * @return string
     */
    protected function getFileName(path $path)
    {
        return ltrim((string) $path->relativizeFrom($this->sourceDirectory), './');
    }

    /**
     * @param path $path
     *
     * @return mixed
     */
    protected function getSourceCode(path $path)
    {
        return $this->adapter->file_get_contents((string) $path->resolve());
    }

    /**
     * @param string $fileName
     *
     * @return array
     */
    protected function getHierarchy($fileName)
    {
        $directories = explode('/', $fileName);
        //unset($directories[count($directories) - 1]);

        $path = '/';
        $result = [];
        foreach ($directories as $directory) {
            $type = strpos($directory, '.') === false ? 'directory' : 'file';

            $result[] = array(
                'name' => $directory,
                'type' => $type,
                'path' => $path,
            );

            $path = $path.$directory.'/';
        }

        return $result;
    }

    /**
     * @param $directories
     * @param $path
     * @param $level
     *
     * @return array
     */
    protected function getLevel($directories, $path, $level)
    {
        $tree = array(
            'name' => $directories[$level],
            'path' => $path,
            'children' => [],
        );

        if (($level + 1) < count($directories) - 1) {
            $path = $path.$directories[$level].'/';
            ++$level;

            $tree['children'][] = $this->getLevel($directories, $path, $level);
        }

        return $tree;
    }

    /**
     * @return \recursiveIteratorIterator
     */
    protected function getDestinationDirectoryIterator()
    {
        return new \recursiveIteratorIterator(
            new \recursiveDirectoryIterator(
                $this->destinationDirectory,
                \filesystemIterator::KEY_AS_PATHNAME |
                \filesystemIterator::CURRENT_AS_FILEINFO |
                \filesystemIterator::SKIP_DOTS
            ),
            \recursiveIteratorIterator::CHILD_FIRST
        );
    }

    /**
     * @return $this
     */
    protected function cleanDestinationDirectory()
    {
        try {
            foreach ($this->getDestinationDirectoryIterator() as $inode) {
                if ($inode->isDir() === false) {
                    $this->adapter->unlink($inode->getPathname());
                } elseif (($pathname = $inode->getPathname()) !== $this->destinationDirectory) {
                    $this->adapter->rmdir($pathname);
                }
            }
        } catch (\exception $exception) {
        }

        return $this;
    }

    /**
     * Generate the assets.
     */
    protected function generateAssets()
    {
        // copy assets
        $this->adapter->copy(
            $this->templatesDirectory.DIRECTORY_SEPARATOR.'screen.css',
            $this->destinationDirectory.DIRECTORY_SEPARATOR.'screen.css'
        );

        $this->adapter->copy(
            $this->templatesDirectory.DIRECTORY_SEPARATOR.'application.js',
            $this->destinationDirectory.DIRECTORY_SEPARATOR.'application.js'
        );

        $this->adapter->copy(
            $this->templatesDirectory.DIRECTORY_SEPARATOR.'logo.png',
            $this->destinationDirectory.DIRECTORY_SEPARATOR.'logo.png'
        );
    }

    /**
     * @param $template
     * @param $projectCoverage
     * @param $itemCoverage
     * @param $itemPath
     */
    protected function buildCommonTemplate($template, $projectCoverage, $itemCoverage, $itemPath)
    {
        // global variables
        $template->projectName = $this->projectName;
        $template->rootUrl = $this->rootUrl;
        $template->relativeRootUrl = rtrim(str_repeat('../', substr_count($itemPath, '\\')), DIRECTORY_SEPARATOR).
            DIRECTORY_SEPARATOR
        ;

        $template->relevantLines = $projectCoverage['relevantLines'];
        $template->coveredLines = $projectCoverage['coveredLines'];

        $template->itemTotalLines = $itemCoverage['totalLines'];
        $template->itemRelevantLines = $itemCoverage['relevantLines'];
        $template->itemCoveredLines = $itemCoverage['coveredLines'];

        // breadcrumb
        $pathTemplate = $template->pathTemplate;
        $pathItemTemplate = $pathTemplate->pathItem;
        $pathItemLastTemplate = $pathTemplate->pathItemLast;

        $breadcrumb = array_filter(explode('/', $itemPath));
        if (count($breadcrumb) > 0) {
            $pathItemTemplate->build(array(
                'pathItemName' => 'Home',
                'pathItemUrl' => DIRECTORY_SEPARATOR,
            ));
        }

        $pathItemUrl = '';
        $i = 0;
        foreach ($breadcrumb as $bread) {
            if ($i++ == count($breadcrumb) - 1) {
                $pathItemLastTemplate->build(array(
                    'pathItemName' => $bread,
                ));
            } else {
                $pathItemUrl .= DIRECTORY_SEPARATOR.$bread;
                $pathItemTemplate->build(array(
                    'pathItemName' => $bread,
                    'pathItemUrl' => $pathItemUrl,
                ));
            }
        }

        $pathTemplate->build();

        // global coverage
        if ($projectCoverage['coverage'] === null) {
            $template->coverageUnavailable->build();
        } else {
            $template->coverageAvailable->build(
                array(
                    'coverageValue' => round($projectCoverage['coverage'] * 100, 2),
                    'uncoverageValue' => 100 - round($projectCoverage['coverage'] * 100, 2),
                )
            );
        }

        // item coverage
        if ($itemCoverage['coverage'] === null) {
            $template->itemCoverageUnavailable->build();
        } else {
            $template->itemCoverageAvailable->build(
                array(
                    'itemCoverageValue' => round($itemCoverage['coverage'] * 100, 2),
                    'itemUncoverageValue' => 100 - round($itemCoverage['coverage'] * 100, 2),
                )
            );
        }
    }

    /**
     * @param string $directoryPath
     * @param array  $directoryData
     * @param int    $relevantLines
     * @param int    $coveredLines
     * @param int    $totalLines
     * @param int    $coverage
     */
    protected function buildDirectoryPage(
        $directoryPath,
        array $directoryData,
        $relevantLines,
        $coveredLines,
        $totalLines,
        $coverage
    ) {
        // get template
        $template = $this->templateParser->parseFile(
            $this->templatesDirectory.DIRECTORY_SEPARATOR.
            ($directoryPath == '/' ? 'index.tpl' : 'directory.tpl')
        );

        // build common
        $this->buildCommonTemplate(
            $template,
            array(
                'totalLines' => $totalLines,
                'relevantLines' => $relevantLines,
                'coveredLines' => $coveredLines,
                'coverage' => $coverage,
            ),
            $directoryData,
            $directoryPath
        );

        // directories coverage
        $itemsTemplate = $template->items;
        $itemTemplate = $itemsTemplate->item;

        foreach ($directoryData['directories'] as $directoryName => $directoryValues) {
            $itemTemplate->build(array(
                'itemName' => $directoryName,
                'itemUrl' => $directoryPath.$directoryName,
                'itemType' => 'directory',
                'itemTotalLines' => $directoryValues['totalLines'],
                'itemRelevantLines' => $directoryValues['relevantLines'],
                'itemCoveredLines' => $directoryValues['coveredLines'],
                'itemCoverage' => round($directoryValues['coverage'] * 100, 2),
                'itemCoverageRounded' => ceil($directoryValues['coverage'] * 100),
            ));
        }

        foreach ($directoryData['files'] as $fileName => $fileValues) {
            $itemTemplate->build(array(
                'itemName' => $fileName,
                'itemUrl' => str_replace('.php', self::HTML_EXTENSION_FILE, $directoryPath.$fileName),
                'itemType' => 'file',
                'itemTotalLines' => $fileValues['totalLines'],
                'itemRelevantLines' => $fileValues['relevantLines'],
                'itemCoveredLines' => $fileValues['coveredLines'],
                'itemCoverage' => round($fileValues['coverage'] * 100, 2),
                'itemCoverageRounded' => ceil($fileValues['coverage'] * 100),
            ));
        }

        $itemsTemplate->build();

        $file = $this->destinationDirectory.$directoryPath.'index.html';
        $directory = $this->adapter->dirname($file);
        if ($this->adapter->is_dir($directory) === false) {
            $this->adapter->mkdir($directory, 0777, true);
        }

        $this->adapter->file_put_contents($file, (string) $template->build());
    }

    /**
     * @param array $classData
     * @param int   $relevantLines
     * @param int   $coveredLines
     * @param int   $totalLines
     * @param int   $coverage
     */
    protected function buildClassPage(
        $classData,
        $relevantLines,
        $coveredLines,
        $totalLines,
        $coverage
    ) {
        // get template
        $template = $this->templateParser->parseFile(
            $this->templatesDirectory.DIRECTORY_SEPARATOR.'class.tpl'
        );

        // build common
        $this->buildCommonTemplate(
            $template,
            array(
                'totalLines' => $totalLines,
                'relevantLines' => $relevantLines,
                'coveredLines' => $coveredLines,
                'coverage' => $coverage,
            ),
            $classData['coverage'],
            $classData['name']
        );

        $methodsTemplates = $template->methods;
        $methodTemplates = $methodsTemplates->method;

        $methodCoverageAvailableTemplates = $methodTemplates->methodCoverageAvailable;
        $methodCoverageUnavailableTemplates = $methodTemplates->methodCoverageUnavailable;

        $sourceFileTemplates = $template->sourceFile;
        $lineTemplates = $sourceFileTemplates->line;
        $coveredLineTemplates = $sourceFileTemplates->coveredLine;
        $notCoveredLineTemplates = $sourceFileTemplates->notCoveredLine;

        $className = $classData['className'];
        $template->className = $className;
        $methods = $this->coverage->getCoverageForClass($className);

        $reflectedMethods = array();
        $reflectionClassMethods = $this->getReflectionClass($className)->getMethods();
        foreach (array_filter($reflectionClassMethods, function ($reflectedMethod) use ($className) {
            return $reflectedMethod->isAbstract() === false &&
            $reflectedMethod->getDeclaringClass()->getName() === $className;
        }) as $reflectedMethod) {
            $reflectedMethods[$reflectedMethod->getName()] = $reflectedMethod;
        }

        if (sizeof($reflectedMethods) > 0) {
            foreach (array_intersect(array_keys($reflectedMethods), array_keys($methods)) as $methodName) {
                $methodCoverageValue = $this->coverage->getValueForMethod($className, $methodName);

                if ($methodCoverageValue === null) {
                    $methodCoverageUnavailableTemplates->build(array('methodName' => $methodName));
                } else {
                    $methodCoverageAvailableTemplates->build(array(
                        'methodName' => $methodName,
                        'methodCoverageValue' => round($methodCoverageValue * 100, 2),
                        'methodCoverageRounded' => ceil($methodCoverageValue * 100),
                    ));
                }

                $methodTemplates->build();

                $methodCoverageAvailableTemplates->resetData();
                $methodCoverageUnavailableTemplates->resetData();
            }

            $methodsTemplates->build();
            $methodTemplates->resetData();
        }

        $srcFile = $this->adapter->fopen(
            $this->sourceDirectory->getRealPath()->__toString().DIRECTORY_SEPARATOR.$classData['name'],
            'r'
        );

        if ($srcFile !== false) {
            $methodLines = array();

            foreach ($reflectedMethods as $reflectedMethodName => $reflectedMethod) {
                $methodLines[$reflectedMethod->getStartLine()] = $reflectedMethodName;
            }

            for ($currentMethod = null, $lineNumber = 1, $line = $this->adapter->fgets($srcFile);
                 $line !== false;
                 $lineNumber++, $line = $this->adapter->fgets($srcFile)) {
                if (isset($methodLines[$lineNumber]) === true) {
                    $currentMethod = $methodLines[$lineNumber];
                }

                switch (true) {
                    case isset($methods[$currentMethod]) === false || (
                            isset($methods[$currentMethod][$lineNumber]) === false ||
                            $methods[$currentMethod][$lineNumber] == -2):
                        $lineTemplateName = 'lineTemplates';
                        break;

                    case isset($methods[$currentMethod]) === true &&
                        isset($methods[$currentMethod][$lineNumber]) === true &&
                        $methods[$currentMethod][$lineNumber] == -1:
                        $lineTemplateName = 'notCoveredLineTemplates';
                        break;

                    default:
                        $lineTemplateName = 'coveredLineTemplates';
                }

                ${$lineTemplateName}->lineNumber = $lineNumber;
                ${$lineTemplateName}->code = htmlentities($line, ENT_QUOTES, 'UTF-8');

                if (isset($methodLines[$lineNumber]) === true) {
                    foreach (${$lineTemplateName}->anchor as $anchorTemplate) {
                        $anchorTemplate->resetData();
                        $anchorTemplate->method = $currentMethod;
                        $anchorTemplate->build();
                    }
                }

                ${$lineTemplateName}
                    ->addToParent()
                    ->resetData();
            }

            $this->adapter->fclose($srcFile);
        }

        $file = $this->destinationDirectory.DIRECTORY_SEPARATOR.
            str_replace('\\', '/', $className).self::HTML_EXTENSION_FILE
        ;

        $directory = $this->adapter->dirname($file);
        if ($this->adapter->is_dir($directory) === false) {
            $this->adapter->mkdir($directory, 0777, true);
        }

        $this->adapter->file_put_contents($file, (string) $template->build());
    }

    /**
     * @param \closure $reflectionClassInjector
     *
     * @return $this
     *
     * @throws exceptions\logic\invalidArgument
     */
    public function setReflectionClassInjector(\closure $reflectionClassInjector)
    {
        $closure = new \reflectionMethod($reflectionClassInjector, '__invoke');

        if ($closure->getNumberOfParameters() != 1) {
            throw new exceptions\logic\invalidArgument('Reflection class injector must take one argument');
        }

        $this->reflectionClassInjector = $reflectionClassInjector;

        return $this;
    }

    /**
     * @param $class
     *
     * @return \reflectionClass
     *
     * @throws exceptions\runtime\unexpectedValue
     */
    public function getReflectionClass($class)
    {
        if ($this->reflectionClassInjector === null) {
            $reflectionClass = new \reflectionClass($class);
        } else {
            $reflectionClass = $this->reflectionClassInjector->__invoke($class);

            if ($reflectionClass instanceof \reflectionClass === false) {
                throw new exceptions\runtime\unexpectedValue(
                    'Reflection class injector must return a \reflectionClass instance'
                );
            }
        }

        return $reflectionClass;
    }
}
