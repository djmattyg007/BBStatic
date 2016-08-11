<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class CLI implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\";

    public function define(Container $di)
    {
        $di->set("console_application", $di->lazyNew("Webmozart\\Console\\ConsoleApplication", array(
            "config" => $di->lazyNew(self::ROOT_NS . "CLI\\Config", array("eventDispatcher" => $di->lazyGet("event_dispatcher")), array("addCommands" => $di)),
        )));
    }

    public function modify(Container $di)
    {
    }
}
