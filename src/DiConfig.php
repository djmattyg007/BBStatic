<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use MattyG\BBStatic\BBStatic;
use MattyG\BBStatic\Util\Config;

final class DiConfig
{
    /**
     * @return Container
     */
    public function createContainer() : Container
    {
        $di = (new ContainerBuilder())->newInstance(true);
        $rootNs = "MattyG\\BBStatic\\";

        // Necessary evil, we need to be able to resolve objects based on runtime configuration
        $di->types["Aura\\Di\\Container"] = $di;

        $this->initConfigConfig($di, $rootNs);
        $this->initDirectoryManagerConfig($di, $rootNs);
        $this->initBBCodeConfig($di, $rootNs);
        $this->initSigningConfig($di, $rootNs);
        $this->initPageConfig($di, $rootNs);
        $this->initTemplateEngineConfig($di, $rootNs);
        $this->initSymfonyFilesystemConfig($di, $rootNs);

        return $di;
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initConfigConfig(Container $di, string $rootNs)
    {
        $di->types[$rootNs . "Util\\ConfigFactory"] = $di->lazyGet("config_factory");
        $di->setters[$rootNs . "Util\\NeedsConfigTrait"]["setConfig"] = $di->lazyGet("config");
        $di->set("config", $di->lazyNew($rootNs . "Util\\Config", array("filename" => BBStatic::CONFIG_FILENAME)));
        $di->set("config_factory", $di->lazyNew($rootNs . "Util\\ConfigFactory"));

        $di->set("config_folder", $di->lazyNew($rootNs . "Util\\ConfigFolder", array(
            "folder" => $di->lazyGetCall("directory_manager", "getConfigFolderDirectory")
        )));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initDirectoryManagerConfig(Container $di, string $rootNs)
    {
        $di->types[$rootNs . "DirectoryManager"] = $di->lazyGet("directory_manager");
        $di->params[$rootNs . "DirectoryManager"]["config"] = $di->lazyGet("config");
        $di->setters[$rootNs . "NeedsDirectoryManagerTrait"]["setDirectoryManager"] = $di->lazyGet("directory_manager");
        $di->set("directory_manager", $di->lazyNew($rootNs . "DirectoryManager"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initBBCodeConfig(Container $di, string $rootNs)
    {
        $di->types["Nbbc\\BBCode"] = $di->lazy(array($di->lazyNew($rootNs . "BBCode\\Init"), "init"));
        $di->setters[$rootNs . "BBCode\\NeedsBBCodeRendererTrait"]["setBBCodeRenderer"] = $di->lazyGet("bbcode_renderer");
        $di->set("bbcode_renderer", $di->lazyNew($rootNs . "BBCode\\BBCodeRenderer"));

        $di->params[$rootNs . "BBCode\\Rules\\URLMap"]["urlMap"] = $di->lazyGetCall("config_folder", "get", "url_map");
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initSigningConfig(Container $di, string $rootNs)
    {
        $di->types[$rootNs . "Signing\\SigningAdapterInterface"] = $di->lazyGet("signer");
        $di->params[$rootNs . "Signing\\GnuPGAdapter"]["options"] = $di->lazyGetCall("config", "getValue", "signing/gnupg", array());
        $di->setters[$rootNs . "Signing\\NeedsSigningAdapterInterfaceTrait"]["setSigningAdapter"] = $di->lazyGet("signer");
        $di->set("signer", $di->lazyNew($rootNs . "Signing\\SigningAdapterInterfaceSharedProxy"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initPageConfig(Container $di, string $rootNs)
    {
        $di->setters[$rootNs . "Page\\NeedsPageFactoryTrait"]["setPageFactory"] = $di->lazyGet("page_factory");
        $di->setters[$rootNs . "Page\\NeedsPageRendererTrait"]["setPageRenderer"] = $di->lazyGet("page_renderer");
        $di->set("page_factory", $di->lazyNew($rootNs . "Page\\PageFactory"));
        $di->set("page_renderer", $di->lazyNew($rootNs . "Page\\PageRenderer"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initTemplateEngineConfig(Container $di, string $rootNs)
    {
        $di->params["Mustache_Cache_FilesystemCache"]["baseDir"] = $di->lazyGetCall("directory_manager", "getCacheDirectory", "templates");
        $di->setters["Mustache_Engine"]["setCache"] = $di->lazyGet("template_cache");
        $di->setters["Mustache_Engine"]["setLoader"] = $di->lazyGet("template_loader");
        $di->setters["Mustache_Engine"]["setPartialsLoader"] = $di->lazyGet("template_partials_loader");
        $di->setters[$rootNs . "Util\\Vendor\\NeedsTemplateEngineTrait"]["setTemplateEngine"] = $di->lazyGet("template_engine");
        $di->set("template_cache", $di->lazyNew("Mustache_Cache_FilesystemCache"));
        $di->set("template_loader", $di->lazyNew("Mustache_Loader_FilesystemLoader", array(
            "baseDir" => $di->lazyGetCall("directory_manager", "getTemplatesDirectory"),
        )));
        $di->set("template_partials_loader", $di->lazyNew("Mustache_Loader_FilesystemLoader", array(
            "baseDir" => $di->lazyGetCall("directory_manager", "getTemplatePartialsDirectory"),
        )));
        $di->set("template_engine", $di->lazyNew("Mustache_Engine"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initSymfonyFilesystemConfig(Container $di, string $rootNs)
    {
        $di->types["Symfony\\Component\\Filesystem\\Filesystem"] = $di->lazyGet("filesystem");
        $di->setters["Symfony\\Component\\Filesystem\\NeedsFilesystemTrait"]["setFilesystem"] = $di->lazyGet("filesystem");
        $di->set("filesystem", $di->lazyNew("Symfony\\Component\\Filesystem\\Filesystem"));
    }
}
