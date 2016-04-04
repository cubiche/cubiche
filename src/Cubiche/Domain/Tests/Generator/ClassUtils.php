<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Generator;

/**
 * ClassUtils class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassUtils
{
    /**
     * @param string $className
     * @param string $sourceFile
     * @param string $targetClassName
     *
     * @return array
     */
    public static function getTestedMethods($className, $sourceFile, $targetClassName)
    {
        $setUpVariables = array();
        $testedMethods = array();
        $classes = self::getClassesInFile($sourceFile);
        $testMethods = $classes[$className]['methods'];
        unset($classes);

        foreach ($testMethods as $name => $testMethod) {
            if (strtolower($name) == 'setup') {
                $setUpVariables = self::getVariablesThatReferenceClass(
                    $testMethod['tokens'],
                    $targetClassName
                );

                break;
            }
        }

        foreach ($testMethods as $name => $testMethod) {
            $argVariables = array();

            if (strtolower($name) == 'setup') {
                continue;
            }

            $start = strpos($testMethod['signature'], '(') + 1;
            $end = strlen($testMethod['signature']) - $start - 1;
            $args = substr($testMethod['signature'], $start, $end);

            foreach (explode(',', $args) as $arg) {
                $arg = explode(' ', trim($arg));

                if (count($arg) == 2) {
                    $type = $arg[0];
                    $var = $arg[1];
                } else {
                    $type = null;
                    $var = $arg[0];
                }

                if ($type == $targetClassName) {
                    $argVariables[] = $var;
                }
            }

            $variables = array_unique(
                array_merge(
                    $setUpVariables,
                    $argVariables,
                    self::getVariablesThatReferenceClass($testMethod['tokens'], $targetClassName)
                )
            );

            foreach ($testMethod['tokens'] as $i => $token) {
                if (is_array($token) && $token[0] == T_DOUBLE_COLON &&
                    is_array($testMethod['tokens'][$i - 1]) &&
                    $testMethod['tokens'][$i - 1][0] == T_STRING &&
                    $testMethod['tokens'][$i - 1][1] == $targetClassName &&
                    is_array($testMethod['tokens'][$i + 1]) &&
                    $testMethod['tokens'][$i + 1][0] == T_STRING &&
                    $testMethod['tokens'][$i + 2] == '(') {
                    // Class::method()
                    $testedMethods[] = $testMethod['tokens'][$i + 1][1];
                } elseif (is_array($token) && $token[0] == T_OBJECT_OPERATOR &&
                    in_array(self::getVariableName($testMethod['tokens'], $i), $variables) &&
                    is_array($testMethod['tokens'][$i + 2]) &&
                    $testMethod['tokens'][$i + 2][0] == T_OBJECT_OPERATOR &&
                    is_array($testMethod['tokens'][$i + 3]) &&
                    $testMethod['tokens'][$i + 3][0] == T_STRING &&
                    $testMethod['tokens'][$i + 4] == '(') {
                    // $this->object->method()
                    $testedMethods[] = $testMethod['tokens'][$i + 3][1];
                } elseif (is_array($token) && $token[0] == T_OBJECT_OPERATOR &&
                    in_array(self::getVariableName($testMethod['tokens'], $i), $variables) &&
                    is_array($testMethod['tokens'][$i + 1]) &&
                    $testMethod['tokens'][$i + 1][0] == T_STRING &&
                    $testMethod['tokens'][$i + 2] == '(') {
                    // $object->method()
                    $testedMethods[] = $testMethod['tokens'][$i + 1][1];
                }
            }
        }

        $testedMethods = array_unique($testedMethods);
        sort($testedMethods);

        return $testedMethods;
    }

    /**
     * @param string $sourceFile
     *
     * @return array
     */
    public static function getClassesInFile($sourceFile)
    {
        $result = array();

        $tokens = token_get_all(
            file_get_contents($sourceFile)
        );
        $numTokens = count($tokens);
        $blocks = array();
        $line = 1;
        $currentBlock = false;
        $currentProject = false;
        $currentLayer = false;
        $currentComponent = false;
        $currentNamespace = false;
        $currentClass = false;
        $currentFunction = false;
        $currentFunctionStartLine = false;
        $currentFunctionTokens = array();
        $currentDocComment = false;
        $currentSignature = false;
        $currentSignatureStartToken = false;

        for ($i = 0; $i < $numTokens; ++$i) {
            if ($currentFunction !== false) {
                $currentFunctionTokens[] = $tokens[$i];
            }

            if (is_string($tokens[$i])) {
                if ($tokens[$i] == '{') {
                    if ($currentBlock == T_CLASS) {
                        $block = $currentClass;
                    } elseif ($currentBlock == T_FUNCTION) {
                        $currentSignature = '';

                        for ($j = $currentSignatureStartToken; $j < $i; ++$j) {
                            if (is_string($tokens[$j])) {
                                $currentSignature .= $tokens[$j];
                            } else {
                                $currentSignature .= $tokens[$j][1];
                            }
                        }

                        $currentSignature = trim($currentSignature);

                        $block = $currentFunction;
                        $currentSignatureStartToken = false;
                    } else {
                        $block = false;
                    }

                    array_push($blocks, $block);

                    $currentBlock = false;
                } elseif ($tokens[$i] == '}') {
                    $block = array_pop($blocks);

                    if ($block !== false && $block !== null) {
                        if ($block == $currentFunction) {
                            if ($currentDocComment !== false) {
                                $docComment = $currentDocComment;
                                $currentDocComment = false;
                            } else {
                                $docComment = '';
                            }

                            $tmp = array(
                                'docComment' => $docComment,
                                'signature' => $currentSignature,
                                'startLine' => $currentFunctionStartLine,
                                'endLine' => $line,
                                'tokens' => $currentFunctionTokens,
                            );

                            if ($currentClass !== false) {
                                $result[$currentClass]['methods'][$currentFunction] = $tmp;
                            }

                            $currentFunction = false;
                            $currentFunctionStartLine = false;
                            $currentFunctionTokens = array();
                            $currentSignature = false;
                        } elseif ($block == $currentClass) {
                            $result[$currentClass]['endLine'] = $line;

                            $currentClass = false;
                        }
                    }
                }

                continue;
            }

            switch ($tokens[$i][0]) {
                case T_HALT_COMPILER:
                    return;
                    break;

                case T_NAMESPACE:
                    $currentNamespace = $tokens[$i + 2][1];

                    for ($j = $i + 3; $j < $numTokens; $j += 2) {
                        if ($tokens[$j][0] == T_NS_SEPARATOR) {
                            $currentNamespace .= '\\'.$tokens[$j + 1][1];
                        } else {
                            break;
                        }
                    }

                    $components = explode('\\', $currentNamespace);
                    if (count($components) > 0) {
                        $currentProject = $components[0];

                        if (count($components) > 1) {
                            $currentLayer = $components[1];

                            if (count($components) > 2) {
                                $currentComponent = $components[2];
                            }
                        }
                    }
                    break;

                case T_CURLY_OPEN:
                    $currentBlock = T_CURLY_OPEN;
                    array_push($blocks, $currentBlock);
                    break;

                case T_DOLLAR_OPEN_CURLY_BRACES:
                    $currentBlock = T_DOLLAR_OPEN_CURLY_BRACES;
                    array_push($blocks, $currentBlock);
                    break;

                case T_CLASS:
                    $currentBlock = T_CLASS;

                    if ($currentNamespace === false) {
                        $currentClass = $tokens[$i + 2][1];
                    } else {
                        $currentClass = $currentNamespace.'\\'.
                            $tokens[$i + 2][1];
                    }

                    if ($currentDocComment !== false) {
                        $docComment = $currentDocComment;
                        $currentDocComment = false;
                    } else {
                        $docComment = '';
                    }

                    $result[$currentClass] = array(
                        'projectName' => $currentProject,
                        'layerName' => $currentLayer,
                        'componentName' => $currentComponent,
                        'namespace' => $currentNamespace,
                        'methods' => array(),
                        'docComment' => $docComment,
                        'startLine' => $line,
                    );
                    break;

                case T_FUNCTION:
                    if (!((is_array($tokens[$i + 2]) &&
                            $tokens[$i + 2][0] == T_STRING) ||
                        (is_string($tokens[$i + 2]) &&
                            $tokens[$i + 2] == '&' &&
                            is_array($tokens[$i + 3]) &&
                            $tokens[$i + 3][0] == T_STRING))) {
                        continue;
                    }

                    $currentBlock = T_FUNCTION;
                    $currentFunctionStartLine = $line;

                    $done = false;
                    $currentSignatureStartToken = $i - 1;

                    do {
                        switch ($tokens[$currentSignatureStartToken][0]) {
                            case T_ABSTRACT:
                            case T_FINAL:
                            case T_PRIVATE:
                            case T_PUBLIC:
                            case T_PROTECTED:
                            case T_STATIC:
                            case T_WHITESPACE:
                                $currentSignatureStartToken--;
                                break;

                            default:
                                $currentSignatureStartToken++;
                                $done = true;
                        }
                    } while (!$done);

                    if (isset($tokens[$i + 2][1])) {
                        $functionName = $tokens[$i + 2][1];
                    } elseif (isset($tokens[$i + 3][1])) {
                        $functionName = $tokens[$i + 3][1];
                    }

                    if ($currentNamespace === false) {
                        $currentFunction = $functionName;
                    } else {
                        $currentFunction = $currentNamespace.'\\'.
                            $functionName;
                    }
                    break;

                case T_DOC_COMMENT:
                    $currentDocComment = $tokens[$i][1];
                    break;
            }

            $line += substr_count($tokens[$i][1], "\n");
        }

        return $result;
    }

    /**
     * @param string $sourceFile
     *
     * @return array
     */
    public static function getInterfacesInFile($sourceFile)
    {
        $result = array();

        $tokens = token_get_all(
            file_get_contents($sourceFile)
        );
        $numTokens = count($tokens);

        for ($i = 0; $i < $numTokens; ++$i) {
            if (is_string($tokens[$i])) {
                continue;
            }

            switch ($tokens[$i][0]) {
                case T_INTERFACE:
                    $currentInterface = $tokens[$i + 2][1];
                    $result[] = $currentInterface;
                    break;
            }
        }

        return $result;
    }

    /**
     * Returns the variables used in test methods
     * that reference the class under test.
     *
     * @param array $tokens
     *
     * @return array
     */
    protected static function getVariablesThatReferenceClass(array $tokens, $targetClassName)
    {
        $inNew = false;
        $variables = array();

        foreach ($tokens as $i => $token) {
            if (is_string($token)) {
                if (trim($token) == ';') {
                    $inNew = false;
                }

                continue;
            }

            list($token, $value) = $token;

            switch ($token) {
                case T_NEW:
                    $inNew = true;
                    break;

                case T_STRING:
                    if ($inNew) {
                        if ($value == $targetClassName) {
                            $variables[] = self::getVariableName($tokens, $i);
                        }
                    }

                    $inNew = false;
                    break;
            }
        }

        return $variables;
    }

    /**
     * Finds the variable name of the object for the method call
     * that is currently being processed.
     *
     * @param array $tokens
     * @param int   $start
     *
     * @return mixed
     */
    protected static function getVariableName(array $tokens, $start)
    {
        for ($i = $start - 1; $i >= 0; --$i) {
            if (is_array($tokens[$i]) && $tokens[$i][0] == T_VARIABLE) {
                $variable = $tokens[$i][1];

                if (is_array($tokens[$i + 1]) &&
                    $tokens[$i + 1][0] == T_OBJECT_OPERATOR &&
                    $tokens[$i + 2] != '(' &&
                    $tokens[$i + 3] != '(') {
                    $variable .= '->'.$tokens[$i + 2][1];
                }

                return $variable;
            }
        }

        return false;
    }
}
