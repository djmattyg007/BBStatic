<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class IcecaveCollections implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "Icecave\\Collections\\";

    public function define(Container $di)
    {
        $di->setters[self::ROOT_NS . "NeedsVectorFactoryTrait"]["setVectorFactory"] = $di->lazyGet("vector_collection_factory");
        $di->set("vector_collection_factory", $di->lazyNew(self::ROOT_NS . "VectorFactory"));
    }

    public function modify(Container $di)
    {
    }
}
