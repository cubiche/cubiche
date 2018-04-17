<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Object;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * ImplementsInterface class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ImplementsInterface extends Rule
{
    /**
     * @var string
     */
    protected $interfaceName;

    /**
     * SubclassOf constructor.
     *
     * @param string $interfaceName
     */
    public function __construct($interfaceName)
    {
        $this->interfaceName = $interfaceName;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function interfaceName()
    {
        return $this->interfaceName;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            json_encode($this->interfaceName)
        );
    }
}
