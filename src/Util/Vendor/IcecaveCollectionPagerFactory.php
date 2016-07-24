<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util\Vendor;

use Aura\Di\Container as DiContainer;
use Icecave\Collections\RandomAccessInterface;
use Pagerfanta\Pagerfanta;

class IcecaveCollectionPagerFactory
{
    /**
     * @var DiContainer
     */
    private $diContaner;

    /**
     * @param DiContainer $diContainer
     */
    public function __construct(DiContainer $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * @param RandomAccessInterface $collection
     * @return Pagerfanta
     */
    public function create(RandomAccessInterface $collection) : Pagerfanta
    {
        $collectionAdapter = $this->diContainer->newInstance(IcecaveCollectionPagerAdapter::class, array("randomAccessCollection" => $collection));
        return $this->diContainer->newInstance(Pagerfanta::class, array("adapter" => $collectionAdapter));
    }
}
