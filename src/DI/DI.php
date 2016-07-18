<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class DI implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\";

    public function define(Container $di)
    {
        $di->types["Aura\\Di\\Container"] = $di;
    }

    public function modify(Container $di)
    {
    }
}
