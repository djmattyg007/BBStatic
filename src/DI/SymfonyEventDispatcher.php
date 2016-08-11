<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class SymfonyEventDispatcher implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "Symfony\\Component\\EventDispatcher\\";

    public function define(Container $di)
    {
        $di->set("event_dispatcher", $di->lazyNew(self::ROOT_NS . "EventDispatcher"));
    }

    public function modify(Container $di)
    {
    }
}
