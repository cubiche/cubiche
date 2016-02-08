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

use mageekguy\atoum\report\fields\runner\coverage\html as Report;

/**
 * Html class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Html extends Report
{
    /**
     * Html constructor.
     *
     * @param $projectName
     * @param $destinationDirectory
     */
    public function __construct($projectName, $destinationDirectory)
    {
        parent::__construct($projectName, $destinationDirectory);
    }

    public function __toString()
    {
        $string = '';

        if (sizeof($this->coverage) > 0) {
            try {
                $this->cleanDestinationDirectory();

                $this->adapter->copy(
                    $this->templatesDirectory.DIRECTORY_SEPARATOR.'screen.css',
                    $this->destinationDirectory.DIRECTORY_SEPARATOR.'screen.css'
                );

                $this->adapter->copy(
                    $this->templatesDirectory.DIRECTORY_SEPARATOR.'logo.png',
                    $this->destinationDirectory.DIRECTORY_SEPARATOR.'logo.png'
                );

                $classes = $this->coverage->getClasses();

                $indexTemplate = $this->templateParser->parseFile(
                    $this->templatesDirectory.DIRECTORY_SEPARATOR.'index.tpl'
                );
                $indexTemplate->projectName = $this->projectName;
                $indexTemplate->rootUrl = $this->rootUrl;

                $coverageValue = $this->coverage->getValue();

                if ($coverageValue === null) {
                    $indexTemplate->coverageUnavailable->build();
                } else {
                    $indexTemplate->coverageAvailable->build(array('coverageValue' => round($coverageValue * 100, 2)));
                }

                $classCoverageTemplates = $indexTemplate->classCoverage;

                $classCoverageAvailableTemplates = $classCoverageTemplates->classCoverageAvailable;
                $classCoverageUnavailableTemplates = $classCoverageTemplates->classCoverageUnavailable;

                ksort($classes, \SORT_STRING);

                foreach ($classes as $className => $classFile) {
                    $classCoverageTemplates->className = $className;
                    $classCoverageTemplates->classUrl = str_replace('\\', '/', $className).self::htmlExtensionFile;

                    $classCoverageValue = $this->coverage->getValueForClass($className);

                    $classCoverageAvailableTemplates->build(
                        array('classCoverageValue' => round($classCoverageValue * 100, 2))
                    );

                    $classCoverageTemplates->build();

                    $classCoverageAvailableTemplates->resetData();
                    $classCoverageUnavailableTemplates->resetData();
                }

                $this->adapter->file_put_contents(
                    $this->destinationDirectory.DIRECTORY_SEPARATOR.'index.html',
                    (string) $indexTemplate->build()
                );

                $classTemplate = $this->templateParser->parseFile(
                    $this->templatesDirectory.DIRECTORY_SEPARATOR.'class.tpl'
                );

                $classTemplate->rootUrl = $this->rootUrl;
                $classTemplate->projectName = $this->projectName;

                $classCoverageAvailableTemplates = $classTemplate->classCoverageAvailable;
                $classCoverageUnavailableTemplates = $classTemplate->classCoverageUnavailable;

                $methodsTemplates = $classTemplate->methods;
                $methodTemplates = $methodsTemplates->method;

                $methodCoverageAvailableTemplates = $methodTemplates->methodCoverageAvailable;
                $methodCoverageUnavailableTemplates = $methodTemplates->methodCoverageUnavailable;

                $sourceFileTemplates = $classTemplate->sourceFile;

                $lineTemplates = $sourceFileTemplates->line;
                $coveredLineTemplates = $sourceFileTemplates->coveredLine;
                $notCoveredLineTemplates = $sourceFileTemplates->notCoveredLine;

                foreach ($this->coverage->getMethods() as $className => $methods) {
                    $classTemplate->className = $className;

                    if (substr_count($className, '\\') >= 1) {
                        $classTemplate->relativeRootUrl = rtrim(
                            str_repeat('../', substr_count($className, '\\')),
                            DIRECTORY_SEPARATOR
                        ).DIRECTORY_SEPARATOR;
                    }

                    $classCoverageValue = $this->coverage->getValueForClass($className);

                    if ($classCoverageValue === null) {
                        $classCoverageUnavailableTemplates->build();
                    } else {
                        $classCoverageAvailableTemplates->build(
                            array('classCoverageValue' => round($classCoverageValue * 100, 2))
                        );
                    }

                    $reflectedMethods = array();

                    $reflectionClassMethods = $this->getReflectionClass($className)->getMethods();
                    foreach (array_filter($reflectionClassMethods, function ($reflectedMethod) use ($className) {
                        return $reflectedMethod->isAbstract() === false &&
                            $reflectedMethod->getDeclaringClass()->getName() === $className
                        ;
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
                                ));
                            }

                            $methodTemplates->build();

                            $methodCoverageAvailableTemplates->resetData();
                            $methodCoverageUnavailableTemplates->resetData();
                        }

                        $methodsTemplates->build();

                        $methodTemplates->resetData();
                    }

                    $srcFile = $this->adapter->fopen($classes[$className], 'r');

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
                        str_replace('\\', '/', $className).self::htmlExtensionFile
                    ;

                    $directory = $this->adapter->dirname($file);

                    if ($this->adapter->is_dir($directory) === false) {
                        $this->adapter->mkdir($directory, 0777, true);
                    }

                    $this->adapter->file_put_contents($file, (string) $classTemplate->build());

                    $classTemplate->resetData();

                    $classCoverageAvailableTemplates->resetData();
                    $classCoverageUnavailableTemplates->resetData();

                    $methodsTemplates->resetData();

                    $sourceFileTemplates->resetData();
                }

                $string .= $this->urlPrompt.
                    $this->urlColorizer->colorize(
                        $this->locale->_('Details of code coverage are available at %s.', $this->rootUrl)
                    ).PHP_EOL;
            } catch (\exception $exception) {
                $string .= $this->urlPrompt.
                    $this->urlColorizer->colorize(
                        $this->locale->_(
                            'Unable to generate code coverage at %s: %s.',
                            $this->rootUrl,
                            $exception->getMessage()
                        )
                    ).PHP_EOL;
            }
        }

        return $this->cliToString().$string;
    }

    protected function cliToString()
    {
        return $this->prompt.
        sprintf(
            '%s: %s.',
            $this->titleColorizer->colorize($this->locale->_('Code coverage')),
            $this->coverageColorizer->colorize(
                $this->coverage === null
                    ?
                    $this->locale->_('unknown')
                    :
                    $this->locale->_('%3.2f%%', round($this->coverage->getValue() * 100, 2))
            )
        ).
        PHP_EOL;
    }
}
