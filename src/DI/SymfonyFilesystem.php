<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class SymfonyFilesystem implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "Symfony\\Component\\Filesystem\\";

    public function define(Container $di)
    {
        $di->types[self::ROOT_NS . "Filesystem"] = $di->lazyGet("filesystem");
        $di->setters[self::ROOT_NS . "NeedsFilesystemTrait"]["setFilesystem"] = $di->lazyGet("filesystem");
        $di->set("filesystem", $di->lazyNew(self::ROOT_NS . "Filesystem"));
    }

    public function modify(Container $di)
    {
    }
}
