<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util\Vendor;

trait NeedsFinderFactoryTrait
{
    /**
     * @var FinderFactory
     */
    protected $finderFactory = null;

    /**
     * @param FinderFactory $finderFactory
     */
    public function setFinderFactory(FinderFactory $finder)
    {
        $this->finderFactory = $finderFactory;
    }
}
