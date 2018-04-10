<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules;

/**
 * RelationalRule class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RelationalRule extends Rule
{
    /**
     * @var string
     */
    protected $reference;

    /**
     * @var Rule
     */
    protected $validator;

    /**
     * RelationalRule constructor.
     *
     * @param string $reference
     * @param Rule   $validator
     */
    public function __construct($reference, Rule $validator)
    {
        $this->reference = $reference;
        $this->validator = $validator;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function reference()
    {
        return $this->reference;
    }

    /**
     * @return Rule
     */
    public function validator()
    {
        return $this->validator;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s-%s',
            $this->shortClassName(),
            $this->reference,
            $this->validator->id()
        );
    }
}
