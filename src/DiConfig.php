<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;

final class DiConfig
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\DI\\";

    /**
     * @return Container
     */
    public function createContainer() : Container
    {
        $diConfigurers = array(
            self::ROOT_NS . "Asset",
            self::ROOT_NS . "Assetic",
            self::ROOT_NS . "BBCode",
            self::ROOT_NS . "Blog",
            self::ROOT_NS . "CLI",
            self::ROOT_NS . "CLIUtils",
            self::ROOT_NS . "Config",
            self::ROOT_NS . "DI",
            self::ROOT_NS . "DirectoryManager",
            self::ROOT_NS . "IcecaveCollections",
            self::ROOT_NS . "IcecaveParity",
            self::ROOT_NS . "Page",
            self::ROOT_NS . "Pagerfanta",
            self::ROOT_NS . "Post",
            self::ROOT_NS . "Signing",
            self::ROOT_NS . "SymfonyFilesystem",
            self::ROOT_NS . "SymfonyFinder",
            self::ROOT_NS . "TemplateEngine",
            self::ROOT_NS . "TemplateHelper",
            self::ROOT_NS . "URLManager",
        );

        $di = (new ContainerBuilder())->newConfiguredInstance($diConfigurers, true);
        return $di;
    }
}
