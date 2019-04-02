<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\GraphQL;

use Cubiche\Core\Validator\Exception\ValidationException;
use Youshido\GraphQL\Exception\Interfaces\LocationableExceptionInterface;
use Youshido\GraphQL\Schema\AbstractSchema;

/**
 * ApplicationSchema class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class ApplicationSchema extends AbstractSchema
{
    /**
     * @param array $errors
     *
     * @return array
     */
    public static function formatErrors(array $errors)
    {
        $result = [];
        foreach ($errors as $error) {
            if ($error instanceof ValidationException) {
                $validationErrors = array(
                    'message' => $error->getMessage(),
                    'code' => $error->getCode(),
                    'errors' => array(),
                );

                foreach ($error->getErrorExceptions() as $errorException) {
                    $validationErrors['errors'][] = array(
                        'message' => $errorException->getMessage(),
                        'code' => $errorException->getCode(),
                        'path' => $errorException->getPropertyPath(),
                    );
                }

                $result[] = $validationErrors;
            } elseif ($error instanceof LocationableExceptionInterface) {
                $result[] = array_merge(
                    ['message' => $error->getMessage()],
                    $error->getLocation() ? ['locations' => [$error->getLocation()->toArray()]] : [],
                    $error->getCode() ? ['code' => $error->getCode()] : []
                );
            } else {
                $result[] = array_merge(
                    ['message' => $error->getMessage()],
                    $error->getCode() ? ['code' => $error->getCode()] : []
                );
            }
        }

        return $result;
    }
}
