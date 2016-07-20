<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class Assetic implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Asset\\Assetic\\";

    public function define(Container $di)
    {
        if (getenv("ASSETIC_DEBUG") == 1) {
            $di->params["Assetic\\Factory\\AssetFactory"]["debug"] = true;
        }
        $di->setters["Assetic\\Factory\\AssetFactory"]["setFilterManager"] = $di->lazyGet("assetic_filter_manager");
        $assetFactoryRoot = $di->lazyGetCall("directory_manager", "getAssetContentDirectory");
        $assetFactoryAssetManager = $di->lazyGet("assetic_asset_manager");
        $di->set("assetic_asset_factory", $di->lazy(array($di->lazyNew(self::ROOT_NS . "AssetFactoryInit"), "init"), $assetFactoryRoot, $assetFactoryAssetManager));
        $di->set("assetic_asset_manager", $di->lazyNew("Assetic\\AssetManager"));
        $di->set("assetic_filter_manager", $di->lazy(array($di->lazyNew(self::ROOT_NS . "FilterManagerInit"), "init")));
    }

    public function modify(Container $di)
    {
    }
}
