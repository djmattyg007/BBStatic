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
        $this->initSigningConfig($di, "{$rootNs}Signing\\");
        $this->initPageConfig($di, "{$rootNs}Content\\Page\\");
        $this->initPostConfig($di, "{$rootNs}Content\\Post\\");
        $this->initTemplateEngineConfig($di, "{$rootNs}Template\\");
        $this->initTemplateHelperConfig($di, "{$rootNs}Template\\Helper\\");
        $this->initIcecaveCollectionsConfig($di, "Icecave\\Collections\\");
        $this->initIcecaveParityConfig($di, "Icecave\\Parity\\");
        $this->initSymfonyFilesystemConfig($di, "Symfony\\Component\\Filesystem\\");
        $this->initSymfonyFinderConfig($di, "Symfony\\Component\\Finder\\");
        $this->initCLIConfig($di, $rootNs);

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
        $di->types["Nbbc\\BBCode"] = $di->lazyGet("bbcode_parser");
        $di->setters[$rootNs . "BBCode\\NeedsBBCodeRendererTrait"]["setBBCodeRenderer"] = $di->lazyGet("bbcode_renderer");
        $di->set("bbcode_renderer", $di->lazyNew($rootNs . "BBCode\\BBCodeRenderer"));
        $di->set("bbcode_parser", $di->lazy(array($di->lazyNew($rootNs . "BBCode\\Init"), "init")));

        $di->params[$rootNs . "BBCode\\Rule\\URLMap"]["urlMap"] = $di->lazyGetCall("config_folder", "getValue", "url_map");
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initSigningConfig(Container $di, string $rootNs)
    {
        $di->types[$rootNs . "SigningAdapterInterface"] = $di->lazyGet("signer");
        $di->params[$rootNs . "GnuPGAdapter"]["options"] = $di->lazyGetCall("config", "getValue", "signing/gnupg", array());
        $di->setters[$rootNs . "NeedsSigningAdapterInterfaceTrait"]["setSigningAdapter"] = $di->lazyGet("signer");
        $di->set("signer", $di->lazyNew($rootNs . "SigningAdapterInterfaceSharedProxy"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initPageConfig(Container $di, string $rootNs)
    {
        $di->setters[$rootNs . "NeedsIndexPageFactoryTrait"]["setIndexPageFactory"] = $di->lazyGet("index_page_factory");
        $di->setters[$rootNs . "NeedsPageBuilderTrait"]["setPageBuilder"] = $di->lazyGet("page_builder");
        $di->setters[$rootNs . "NeedsPageFactoryTrait"]["setPageFactory"] = $di->lazyGet("page_factory");
        $di->setters[$rootNs . "NeedsPageGathererTrait"]["setPageGatherer"] = $di->lazyGet("page_gatherer");
        $di->setters[$rootNs . "NeedsPageRendererTrait"]["setPageRenderer"] = $di->lazyGet("page_renderer");
        $di->set("index_page_factory", $di->lazyNew($rootNs . "IndexPageFactory"));
        $di->set("page_builder", $di->lazyNew($rootNs . "PageBuilder"));
        $di->set("page_factory", $di->lazyNew($rootNs . "PageFactory"));
        $di->set("page_gatherer", $di->lazyNew($rootNs . "PageGatherer"));
        $di->set("page_renderer", $di->lazyNew($rootNs . "PageRenderer"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initPostConfig(Container $di, string $rootNs)
    {
        $di->setters[$rootNs . "NeedsPostBuilderTrait"]["setPostBuilder"] = $di->lazyGet("post_builder");
        $di->setters[$rootNs . "NeedsPostFactoryTrait"]["setPostFactory"] = $di->lazyGet("post_factory");
        $di->setters[$rootNs . "NeedsPostGathererTrait"]["setPostGatherer"] = $di->lazyGet("post_gatherer");
        $di->setters[$rootNs . "NeedsPostRendererTrait"]["setPostRenderer"] = $di->lazyGet("post_renderer");
        $di->set("post_builder", $di->lazyNew($rootNs . "PostBuilder"));
        $di->set("post_factory", $di->lazyNew($rootNs . "PostFactory"));
        $di->set("post_gatherer", $di->lazyNew($rootNs . "PostGatherer"));
        $di->set("post_renderer", $di->lazyNew($rootNs . "PostRenderer"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initTemplateEngineConfig(Container $di, string $rootNs)
    {
        $di->params[$rootNs . "EdenHandlebarsAdapter"]["engine"] = $di->lazyGet("eden_handlebars");
        $di->params[$rootNs . "EdenHandlebarsAdapter"]["templateLoader"] = $di->lazyGet("template_loader");
        $di->setters[$rootNs . "NeedsTemplateEngineInterfaceTrait"]["setTemplateEngine"] = $di->lazyGet("template_engine");
        $di->setters[$rootNs . "TemplateLoader"]["setFileExtension"] = $di->lazyGetCall("config", "getValue", "templates/fileext", "hbs");
        $templateEngineInit = $di->lazyNew($rootNs . "Init", array(
            "partialsDir" => $di->lazyGetCall("directory_manager", "getTemplatePartialsDirectory"),
        ));
        $di->set("template_engine", $di->lazy(array($templateEngineInit, "init")));
        $di->set("eden_handlebars", $di->lazy(function() use ($di) {
            $handlebars = \Eden\Handlebars\Index::i();
            $handlebars->setCache($di->get("directory_manager")->getCacheDirectory("templates"));
            return $handlebars;
        }));
        $di->set("template_loader", $di->lazyNew($rootNs . "TemplateLoader", array(
            "templatesFolder" => $di->lazyGetCall("directory_manager", "getTemplatesDirectory"),
        )));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initTemplateHelperConfig(Container $di, string $rootNs)
    {
        $di->params[$rootNs . "Date"]["timezone"] = $di->lazyGetCall("config", "getValue", "site/timezone", "UTC");
        $di->params[$rootNs . "Date"]["dateFormats"] = $di->lazyGetCall("config", "getValue", "theme/date_formats", array());
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initIcecaveCollectionsConfig(Container $di, string $rootNs)
    {
        $di->setters[$rootNs . "NeedsVectorFactoryTrait"]["setVectorFactory"] = $di->lazyGet("vector_collection_factory");
        $di->set("vector_collection_factory", $di->lazyNew($rootNs . "VectorFactory"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initIcecaveParityConfig(Container $di, string $rootNs)
    {
        $di->params[$rootNs . "Comparator\\ParityComparator"]["fallbackComparator"] = $di->lazyGet("null_comparator");
        $di->setters[$rootNs . "NeedsParityComparatorTrait"]["setParityComparator"] = $di->lazyGet("parity_comparator");
        $di->set("parity_comparator", $di->lazyNew($rootNs . "Comparator\\ParityComparator"));
        $di->set("null_comparator", $di->lazyNew("MattyG\\BBStatic\\Util\\Vendor\\NullComparator"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initSymfonyFilesystemConfig(Container $di, string $rootNs)
    {
        $di->types[$rootNs . "Filesystem"] = $di->lazyGet("filesystem");
        $di->setters[$rootNs . "NeedsFilesystemTrait"]["setFilesystem"] = $di->lazyGet("filesystem");
        $di->set("filesystem", $di->lazyNew($rootNs . "Filesystem"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initSymfonyFinderConfig(Container $di, string $rootNs)
    {
        $di->setters[$rootNs . "NeedsFinderFactoryTrait"]["setFinderFactory"] = $di->lazyGet("finder_factory");
        $di->set("finder_factory", $di->lazyNew($rootNs . "FinderFactory"));
    }

    /**
     * @param Container $di
     * @param string $rootNs
     */
    private function initCLIConfig(Container $di, string $rootNs)
    {
        $di->setters[$rootNs . "CLI\\Vendor\\NeedsProgressBarFactoryTrait"]["setProgressBarFactory"] = $di->lazyGet("progress_bar_factory");
        $di->set("progress_bar_factory", $di->lazyNew($rootNs . "CLI\\Vendor\\ProgressBarFactory"));
    }
}
