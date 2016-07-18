<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class Blog implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Content\\";

    public function define(Container $di)
    {
        $di->params[self::ROOT_NS . "Blog"]["contentPath"] = $di->lazyGetCall("directory_manager", "getBlogContentDirectory");
        $di->params[self::ROOT_NS . "Blog"]["outputPath"] = $di->lazyGetCall("directory_manager", "getBlogOutputDirectory");
        $di->params[self::ROOT_NS . "Blog"]["urlPath"] = $di->lazyGetCall("url_manager", "getBlogUrlPath");
        $di->setters[self::ROOT_NS . "NeedsBlogFactoryTrait"]["setBlogFactory"] = $di->lazyGet("blog_factory");
        $di->setters[self::ROOT_NS . "NeedsBlogBuilderTrait"]["setBlogBuilder"] = $di->lazyGet("blog_builder");
        $di->set("blog_factory", $di->lazyNew(self::ROOT_NS . "BlogFactory"));
        $di->set("blog_builder", $di->lazyNew(self::ROOT_NS . "BlogBuilder"));
    }

    public function modify(Container $di)
    {
    }
}
