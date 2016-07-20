<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class TemplateHelper implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Template\\Helper\\";

    public function define(Container $di)
    {
        $di->params[self::ROOT_NS . "Asset"]["assetManager"] = $di->lazyGet("asset_manager");
        $di->params[self::ROOT_NS . "Date"]["timezone"] = $di->lazyGetCall("config", "getValue", "site/timezone", "UTC");
        $di->params[self::ROOT_NS . "Date"]["dateFormats"] = $di->lazyGetCall("config", "getValue", "theme/date_formats", array());
        $di->params[self::ROOT_NS . "URL"]["baseUrl"] = $di->lazyGetCall("url_manager", "getBaseUrl");
    }

    public function modify(Container $di)
    {
    }
}
