<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class URLManager implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\";

    public function define(Container $di)
    {
        $di->types[self::ROOT_NS . "URLManager"] = $di->lazyGet("url_manager");
        $di->params[self::ROOT_NS . "URLManager"]["config"] = $di->lazyGet("config");
        $di->setters[self::ROOT_NS . "NeedsURLManagerTrait"]["setURLManager"] = $di->lazyGet("url_manager");
        $di->set("url_manager", $di->lazyNew(self::ROOT_NS . "URLManager"));
    }

    public function modify(Container $di)
    {
    }
}
