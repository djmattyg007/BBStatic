<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class SymfonyFinder implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "Symfony\\Component\\Finder\\";

    public function define(Container $di)
    {
        $di->setters[self::ROOT_NS . "NeedsFinderFactoryTrait"]["setFinderFactory"] = $di->lazyGet("finder_factory");
        $di->set("finder_factory", $di->lazyNew(self::ROOT_NS . "FinderFactory"));
    }

    public function modify(Container $di)
    {
    }
}
