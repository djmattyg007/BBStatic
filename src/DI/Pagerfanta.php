<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class Pagerfanta implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Util\\Vendor\\";

    public function define(Container $di)
    {
        $di->setters[self::ROOT_NS . "NeedsIcecaveCollectionPagerFactoryTrait"]["setIcecaveCollectionPagerFactory"] = $di->lazyGet("icecave_collection_pager_factory");
        $di->set("icecave_collection_pager_factory", $di->lazyNew(self::ROOT_NS . "IcecaveCollectionPagerFactory"));
    }

    public function modify(Container $di)
    {
    }
}
