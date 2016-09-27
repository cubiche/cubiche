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

/**
 * Template class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Template
{
    /**
     * @var string
     */
    protected $template = '';

    /**
     * @var string
     */
    protected $openCharacter = '{';

    /**
     * @var string
     */
    protected $closeCharacter = '}';

    /**
     * @var array
     */
    protected $values = array();

    /**
     * Template constructor.
     *
     * @param string $file
     * @param string $openCharacter
     * @param string $closeCharacter
     */
    public function __construct($file = '', $openCharacter = '{', $closeCharacter = '}')
    {
        $this->setFile($file);

        $this->openCharacter = $openCharacter;
        $this->closeCharacter = $closeCharacter;
    }

    /**
     * @param $file
     *
     * @throws InvalidArgumentException
     */
    public function setFile($file)
    {
        $distFile = $file.'.dist';

        if (file_exists($file)) {
            $this->template = file_get_contents($file);
        } elseif (file_exists($distFile)) {
            $this->template = file_get_contents($distFile);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Template file %s could not be loaded.',
                $distFile
            ));
        }
    }

    /**
     * Sets one or more template variables.
     *
     * @param array $values
     * @param bool  $merge
     */
    public function setVar(array $values, $merge = true)
    {
        if (!$merge || empty($this->values)) {
            $this->values = $values;
        } else {
            $this->values = array_merge($this->values, $values);
        }
    }

    /**
     * Renders the template and returns the result.
     *
     * @return string
     */
    public function render()
    {
        $keys = array();

        foreach (\array_keys($this->values) as $key) {
            $keys[] = $this->openCharacter.$key.$this->closeCharacter;
        }

        return str_replace($keys, $this->values, $this->template);
    }

    /**
     * Renders the template and writes the result to a file.
     *
     * @param $target
     *
     * @throws RuntimeException
     */
    public function renderTo($target)
    {
        $fp = @fopen($target, 'wt');

        if ($fp) {
            fwrite($fp, $this->render());
            fclose($fp);
        } else {
            $error = error_get_last();

            throw new \RuntimeException(
                sprintf(
                    'Could not write to %s: %s',
                    $target,
                    substr(
                        $error['message'],
                        strpos($error['message'], ':') + 2
                    )
                )
            );
        }
    }
}
