<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use MattyG\BBStatic\BBStatic;
use MattyG\BBStatic\Util\Config;

class DiConfig
{
    /**
     * @return Container
     */
    public function createContainer() : Container
    {
        $rootNs = "MattyG\\BBStatic\\";
        $di = (new ContainerBuilder())->newInstance(true);

        // Necessary evil, we need to be able to resolve objects based on runtime configuration
        $di->types["Aura\\Di\\Container"] = $di;

        $di->setters[$rootNs . "Util\\NeedsConfigTrait"]["setConfig"] = $di->lazyGet("config");
        $di->setters[$rootNs . "Util\\NeedsConfigFactoryTrait"]["setConfigFactory"] = $di->lazyGet("config_factory");
        $di->set("config", $di->lazyNew($rootNs . "Util\\Config", array("filename" => BBStatic::CONFIG_FILENAME)));
        $di->set("config_factory", $di->lazyNew($rootNs . "Util\\ConfigFactory"));

        $di->types[$rootNs . "DirectoryManager"] = $di->lazyGet("directory_manager");
        $di->params[$rootNs . "DirectoryManager"]["config"] = $di->lazyGet("config");
        $di->setters[$rootNs . "NeedsDirectoryManagerTrait"]["setDirectoryManager"] = $di->lazyGet("directory_manager");
        $di->set("directory_manager", $di->lazyNew($rootNs . "DirectoryManager"));

        $di->types["Nbbc\\BBCode"] = $di->lazy(array($di->lazyNew($rootNs . "BBCode\\Init"), "init"));
        $di->setters[$rootNs . "NeedsFileBuilderTrait"]["setFileBuilder"] = $di->lazyGet("file_builder");
        $di->set("file_builder", $di->lazyNew($rootNs . "FileBuilder"));

        $di->types[$rootNs . "Signing\\Adapter\\SigningAdapterInterface"] = $di->lazyNew($rootNs . "Signing\\Adapter\\SigningAdapterInterfaceProxy");
        $di->params[$rootNs . "Signing\\SigningManager"]["config"] = $di->lazyGet("config");
        $di->params[$rootNs . "Signing\\Adapter\\GnuPG"]["options"] = $di->lazyGetCall("config", "getValue", "signing/gnupg", array());
        $di->setters[$rootNs . "Signing\\NeedsSignerTrait"]["setSigner"] = $di->lazyGet("signer");
        $di->set("signer", $di->lazyNew($rootNs . "Signing\\SigningManager"));

        $di->params[$rootNs . "Page\\Page"]["config"] = $di->lazyGet("config");
        $di->params[$rootNs . "Page\\Page"]["configFactory"] = $di->lazyGet("config_factory");
        $di->setters[$rootNs . "Page\\NeedsPageFactoryTrait"]["setPageFactory"] = $di->lazyGet("page_factory");
        $di->setters[$rootNs . "Page\\NeedsPageRendererTrait"]["setPageRenderer"] = $di->lazyGet("page_renderer");
        $di->set("page_factory", $di->lazyNew($rootNs . "Page\\PageFactory"));
        $di->set("page_renderer", $di->lazyNew($rootNs . "Page\\Renderer"));

        // TODO: Update to use directory manager
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

        $di->types["Symfony\\Component\\Filesystem\\Filesystem"] = $di->lazyGet("filesystem");
        $di->setters[$rootNs . "Util\\Vendor\\NeedsFilesystemTrait"]["setFilesystem"] = $di->lazyGet("filesystem");
        $di->set("filesystem", $di->lazyNew("Symfony\\Component\\Filesystem\\Filesystem"));

        return $di;
    }
}
