<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util\Vendor;

use Icecave\Parity\Comparator\ComparatorInterface;
use Icecave\Parity\Exception\NotComparableException;

class NullComparator implements ComparatorInterface
{
    /**
     * @param mixed $lhs
     * @param mixed $rhs
     */
    public function compare($lhs, $rhs)
    {
        throw new NotComparableException("Cannot compare these items");
    }

    /**
     * @param mixed $lhs
     * @param mixed $rhs
     */
    public function __invoke($lhs, $rhs)
    {
        return $this->compare($lhs, $rhs);
    }
}
