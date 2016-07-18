<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class CLIUtils implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\";

    public function define(Container $di)
    {
        $di->setters[self::ROOT_NS . "CLI\\Vendor\\NeedsProgressBarFactoryTrait"]["setProgressBarFactory"] = $di->lazyGet("progress_bar_factory");
        $di->set("progress_bar_factory", $di->lazyNew(self::ROOT_NS . "CLI\\Vendor\\ProgressBarFactory"));
    }

    public function modify(Container $di)
    {
    }
}
