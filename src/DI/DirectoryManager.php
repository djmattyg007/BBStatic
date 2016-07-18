<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class DirectoryManager implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\";

    public function define(Container $di)
    {
        $di->types[self::ROOT_NS . "DirectoryManager"] = $di->lazyGet("directory_manager");
        $di->params[self::ROOT_NS . "DirectoryManager"]["directories"] = $di->lazyGetCall("config", "getValue", "directories");
        $di->setters[self::ROOT_NS . "NeedsDirectoryManagerTrait"]["setDirectoryManager"] = $di->lazyGet("directory_manager");
        $di->set("directory_manager", $di->lazyNew(self::ROOT_NS . "DirectoryManager"));
    }

    public function modify(Container $di)
    {
    }
}
