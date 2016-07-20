<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class Asset implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Asset\\";

    public function define(Container $di)
    {
        $di->setters[self::ROOT_NS . "NeedsAssetManagerTrait"]["setAssetManager"] = $di->lazyGet("asset_manager");
        $urlPackage = $di->lazyNew("Symfony\\Component\\Asset\\UrlPackage", array("baseUrls" => $di->lazyGetCall("url_manager", "getAssetUrl")));
        $di->set("asset_manager", $di->lazyNew(self::ROOT_NS . "AssetManager", array(
            "assetFactory" => $di->lazyGet("assetic_asset_factory"),
            "assetWriter" => $di->lazyGet("asset_writer"),
            "assetFileConfig" => $di->lazyGetCall("config_folder", "getValue", "asset_files", array()),
            "baseAssetUrl" => $di->lazyGetCall("url_manager", "getAssetUrl"),
        )));
        $di->set("asset_writer", $di->lazyNew(self::ROOT_NS . "Assetic\\AssetWriter", array("outputDirectory" => $di->lazyGetCall("directory_manager", "getAssetOutputDirectory"))));
    }

    public function modify(Container $di)
    {
    }
}
