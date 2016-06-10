<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use MattyG\BBStatic\DirectoryManager;
use MattyG\BBStatic\Util\ConfigFactory;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;

class Page
{
    use NeedsFinderFactoryTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $pageFolder;

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
        $this->pageFolder = $directoryManager->getPagesDirectory() . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $name);
        $this->outputFolder = $directoryManager->getHtmlDirectory() . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $name);

        $this->loadPageConfig($configFactory);
    }

    /**
     * @param ConfigFactory $configFactory
     */
    private function loadPageConfig(ConfigFactory $configFactory)
    {
        $filename = $this->pageFolder . DIRECTORY_SEPARATOR . "config.json";
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
        return $this->pageFolder . DIRECTORY_SEPARATOR . "content.bb";
    }

    /**
     * @return string
     */
    public function getOutputFolder() : string
    {
        return $this->outputFolder;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->pageConfig->getValue("title");
    }

    /**
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->pageConfig->getValue("author", null);
    }

    /**
     * @return int
     */
    public function getDatePosted() : int
    {
        return $this->pageConfig->getValue("date_posted");
    }

    /**
     * @return int
     */
    public function getDateUpdated() : int
    {
        return $this->pageConfig->getValue("date_updated", $this->pageConfig->getValue("date_posted"));
    }

    /**
     * @return array
     */
    public function getTemplateVariables() : array
    {
        return $this->pageConfig->getValue("template_vars", array());
    }

    /**
     * @return array
     */
    public function getAdditionalFiles() : array
    {
        $finder = $this->finderFactory->create();
        $finder->files()
            ->in($this->pageFolder)
            ->notName("content.bb")
            ->notName("config.json")
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks(true);

        $filenames = array();
        foreach ($finder as $file) {
            $filenames[] = $file->getPathname();
        }

        return $filenames;
    }
}
