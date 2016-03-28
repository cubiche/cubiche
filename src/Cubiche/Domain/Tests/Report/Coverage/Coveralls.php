<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Report\Coverage;

use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\reports\asynchronous\coveralls as Report;
use mageekguy\atoum\score\coverage as Coverage;

/**
 * Coveralls class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Coveralls extends Report
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * Coveralls constructor.
     *
     * @param string       $sourceDir
     * @param string       $repositoryToken
     * @param string       $rootDir
     * @param Adapter|null $adapter
     */
    public function __construct($sourceDir, $repositoryToken, $rootDir = null, Adapter $adapter = null)
    {
        parent::__construct($sourceDir, $repositoryToken, $adapter);

        $this->rootDir = $rootDir;
    }

    /**
     * {@inheritdoc}
     */
    protected function makeSourceElement(Coverage $coverage)
    {
        $sources = parent::makeSourceElement($coverage);

        if ($this->rootDir !== null) {
            foreach ($sources as &$source) {
                $source['name'] = $this->rootDir.'/'.$source['name'];
            }
        }

        return $sources;
    }
}
