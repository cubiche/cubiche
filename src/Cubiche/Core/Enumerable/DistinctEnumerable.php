<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable;

use Cubiche\Core\Equatable\EqualityComparer;
use Cubiche\Core\Equatable\EqualityComparerInterface;

/**
 * Distinct Enumerable class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DistinctEnumerable extends FilteredEnumerable
{
    use Cacheable;

    /**
     * @var mixed[][];
     */
    private $slots;

    /**
     * @var EqualityComparerInterface
     */
    protected $equalityComparer;

    /**
     * @param array|\Traversable $enumerable
     * @param callable           $equalityComparer
     */
    public function __construct($enumerable, callable $equalityComparer = null)
    {
        parent::__construct($enumerable, function ($value) {
            return !$this->find($value, $this->equalityComparer, true);
        });

        $this->slots = [];
        $this->equalityComparer = EqualityComparer::ensure($equalityComparer);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($value, callable $equalityComparer = null)
    {
        $equalityComparer = EqualityComparer::ensure($equalityComparer);
        if ($this->initialized() && $equalityComparer->hashCode($value) === $this->equalityComparer->hashCode($value)) {
            return $this->find($value, $equalityComparer);
        }

        return parent::contains($value, $equalityComparer);
    }

    /**
     * @param mixed                     $value
     * @param EqualityComparerInterface $equalityComparer
     * @param bool                      $add
     *
     * @return bool
     */
    private function find($value, EqualityComparerInterface $equalityComparer, $add = false)
    {
        $hashCode = $equalityComparer->hashCode($value);
        if (!isset($this->slots[$hashCode])) {
            if (!$add) {
                return false;
            }
            $this->slots[$hashCode] = array();
        }

        foreach ($this->slots[$hashCode] as $item) {
            if ($equalityComparer->equals($item, $value)) {
                return true;
            }
        }

        if ($add) {
            $this->slots[$hashCode][] = $value;
        }

        return false;
    }
}
