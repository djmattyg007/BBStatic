<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util\Vendor;

use Icecave\Collections\RandomAccessInterface;
use Pagerfanta\Adapter\AdapterInterface;

class IcecaveCollectionPagerAdapter implements AdapterInterface
{
    /**
     * @var RandomAccessInterface
     */
    private $randomAccessCollection;

    /**
     * @param RandomAccessInterface $randomAccessCollection
     */
    public function __construct(RandomAccessInterface $randomAccessCollection)
    {
        $this->randomAccessCollection = $randomAccessCollection;
    }

    /**
     * @return RandomAccessCollection
     */
    public function getCollection() : RandomAccessInterface
    {
        return $this->randomAccessCollection;
    }

    /**
     * @return int
     */
    public function getNbResults() : int
    {
        return $this->randomAccessCollection->size();
    }

    /**
     * TODO: In PHP7.1, change return type to new iterable type
     * @param int $offset
     * @param int $length
     * @return array|\Traversable
     * @throws \Icecave\Collections\Exception\IndexException
     */
    public function getSlice($offset, $length)
    {
        return $this->randomAccessCollection->slice($offset, $length);
    }
}
