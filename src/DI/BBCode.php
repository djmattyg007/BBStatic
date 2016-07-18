<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class BBCode implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\BBCode\\";

    public function define(Container $di)
    {
        $di->types["Nbbc\\BBCode"] = $di->lazyGet("bbcode_parser");
        $di->setters[self::ROOT_NS . "NeedsBBCodeRendererTrait"]["setBBCodeRenderer"] = $di->lazyGet("bbcode_renderer");
        $di->set("bbcode_renderer", $di->lazyNew(self::ROOT_NS . "BBCodeRenderer"));
        $di->set("bbcode_parser", $di->lazy(array($di->lazyNew(self::ROOT_NS . "Init"), "init")));

        $di->params[self::ROOT_NS . "Rule\\URLMap"]["urlMap"] = $di->lazyGetCall("config_folder", "getValue", "url_map");
    }

    public function modify(Container $di)
    {
    }
}
