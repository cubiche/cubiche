<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\Exception;

/**
 * Not Implemented Exception.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotImplementedException extends \RuntimeException
{
    /**
     * @param string     $className
     * @param string     $method
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($className, $method, $code = 0, $previous = null)
    {
        parent::__construct(
            \sprintf('The %s::%s method has not been implemented', $className, $method),
            $code,
            $previous
        );
    }
}
