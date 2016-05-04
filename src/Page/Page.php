<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use MattyG\BBStatic\DirectoryManager;
use MattyG\BBStatic\Util\ConfigFactory;

class Page
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $pagesDirectory;

    /**
     * @var \MattyG\BBStatic\Util\Config
     */
    protected $pageConfig = null;

    /**
     * @param string $name
     * @param DirectoryManager $directoryManager
     * @param ConfigFactory $configFactory
     */
    public function __construct(string $name, DirectoryManager $directoryManager, ConfigFactory $configFactory)
    {
        $this->name = $name;
        $this->pagesDirectory = $directoryManager->getPagesDirectory();

        $this->loadPageConfig($configFactory);
    }

    /**
     * @param ConfigFactory $configFactory
     */
    private function loadPageConfig(ConfigFactory $configFactory)
    {
        $filename = $this->pagesDirectory . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $this->name) . ".json";
        $this->pageConfig = $configFactory->create(array("filename" => $filename));
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
        return $this->pagesDirectory . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $this->name) . ".bb";
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
