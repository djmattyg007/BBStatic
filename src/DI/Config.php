<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;
use MattyG\BBStatic\BBStatic;

class Config implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Util\\";

    public function define(Container $di)
    {
        $di->types[self::ROOT_NS . "ConfigFactory"] = $di->lazyGet("config_factory");
        $di->setters[self::ROOT_NS . "NeedsConfigTrait"]["setConfig"] = $di->lazyGet("config");
        $di->set("config", $di->lazyNew(self::ROOT_NS . "Config", array("filename" => BBStatic::CONFIG_FILENAME)));
        $di->set("config_factory", $di->lazyNew(self::ROOT_NS . "ConfigFactory"));

        $di->set("config_folder", $di->lazyNew(self::ROOT_NS . "ConfigFolder", array(
            "folder" => $di->lazyGetCall("directory_manager", "getConfigFolderDirectory")
        )));
    }

    public function modify(Container $di)
    {
    }
}
