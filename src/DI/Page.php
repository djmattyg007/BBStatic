<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class Page implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Content\\Page\\";

    public function define(Container $di)
    {
        $di->setters[self::ROOT_NS . "NeedsIndexPageFactoryTrait"]["setIndexPageFactory"] = $di->lazyGet("index_page_factory");
        $di->setters[self::ROOT_NS . "NeedsPageBuilderTrait"]["setPageBuilder"] = $di->lazyGet("page_builder");
        $di->setters[self::ROOT_NS . "NeedsPageFactoryTrait"]["setPageFactory"] = $di->lazyGet("page_factory");
        $di->setters[self::ROOT_NS . "NeedsPageGathererTrait"]["setPageGatherer"] = $di->lazyGet("page_gatherer");
        $di->setters[self::ROOT_NS . "NeedsPageRendererTrait"]["setPageRenderer"] = $di->lazyGet("page_renderer");
        $di->set("index_page_factory", $di->lazyNew(self::ROOT_NS . "IndexPageFactory"));
        $di->set("page_builder", $di->lazyNew(self::ROOT_NS . "PageBuilder"));
        $di->set("page_factory", $di->lazyNew(self::ROOT_NS . "PageFactory"));
        $di->set("page_gatherer", $di->lazyNew(self::ROOT_NS . "PageGatherer"));
        $di->set("page_renderer", $di->lazyNew(self::ROOT_NS . "PageRenderer"));
    }

    public function modify(Container $di)
    {
    }
}
