<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class Lurker implements ContainerConfigInterface
{
    public function define(Container $di)
    {
        $di->setters["Lurker\\NeedsResourceWatcherTrait"]["setResourceWatcher"] = $di->lazyGet("resource_watcher");
        $di->set("resource_watcher", $di->lazyNew("Lurker\\ResourceWatcher", array("eventDispatcher" => $di->lazyGet("event_dispatcher"))));
    }

    public function modify(Container $di)
    {
    }
}
