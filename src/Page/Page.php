<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use MattyG\BBStatic\Util\Config;
use MattyG\BBStatic\Util\ConfigFactory;

class Page
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ConfigFactory
     */
    protected $configFactory;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $pageConfig = null;

    /**
     * TODO: Inject DirectoryManager instead
     * @param Config $config
     * @param ConfigFactory $configFactory
     * @param string $name
     */
    public function __construct(Config $config, ConfigFactory $configFactory, string $name)
    {
        $this->config = $config;
        $this->configFactory = $configFactory;
        $this->name = $name;
        $this->loadPageConfig();
    }

    private function loadPageConfig()
    {
        $pagesDirectory = $this->config->getValue("directories/pages");
        $filename = $pagesDirectory . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $this->name) . ".json";
        $this->pageConfig = $this->configFactory->create(array("filename" => $filename));
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPageType() : string
    {
        return $this->pageConfig->getValue("page_type") ?: "default";
    }

    /**
     * @return string
     */
    public function getContentFilename() : string
    {
        $pagesDirectory = $this->config->getValue("directories/pages");
        return $pagesDirectory . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $this->name) . ".bb";
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->pageConfig->getValue("title");
    }

    /**
     * @return array
     */
    public function getTemplateVariables() : array
    {
        return $this->pageConfig->getValue("template_vars", array());
    }
}
