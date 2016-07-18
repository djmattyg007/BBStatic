<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class Post implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Content\\Post\\";

    public function define(Container $di)
    {
        $di->types[self::ROOT_NS . "PostGathererInterface"] = $di->lazyGet("post_gatherer");
        $di->params[self::ROOT_NS . "Post"]["blogUrlPath"] = $di->lazyGetCall("url_manager", "getBlogUrlPath");
        $di->setters[self::ROOT_NS . "NeedsPostBuilderTrait"]["setPostBuilder"] = $di->lazyGet("post_builder");
        $di->setters[self::ROOT_NS . "NeedsPostFactoryTrait"]["setPostFactory"] = $di->lazyGet("post_factory");
        $di->setters[self::ROOT_NS . "NeedsPostGathererInterfaceTrait"]["setPostGatherer"] = $di->lazyGet("post_gatherer");
        $di->setters[self::ROOT_NS . "NeedsPostRendererTrait"]["setPostRenderer"] = $di->lazyGet("post_renderer");
        $di->set("post_builder", $di->lazyNew(self::ROOT_NS . "PostBuilder"));
        $di->set("post_factory", $di->lazyNew(self::ROOT_NS . "PostFactory"));
        $di->set("post_gatherer", $di->lazyNew(self::ROOT_NS . "PostGatherer"));
        $di->set("post_renderer", $di->lazyNew(self::ROOT_NS . "PostRenderer"));
    }

    public function modify(Container $di)
    {
    }
}
