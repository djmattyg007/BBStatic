<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content;

use MattyG\BBStatic\Util\ConfigFactory;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;

abstract class ContentEntity
{
    use NeedsFinderFactoryTrait;

    const CONFIG_FILENAME = "config.json";
    const CONTENT_FILENAME = "content.bb";

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $contentFolder;

    /**
     * @var string
     */
    protected $outputFolder;

    /**
     * @var \MattyG\BBStatic\Util\Config
     */
    protected $config = null;

    /**
     * @param ConfigFactory $configFactory
     */
    protected function loadConfig(ConfigFactory $configFactory)
    {
        $filename = $this->contentFolder . DIRECTORY_SEPARATOR . static::CONFIG_FILENAME;
        $this->config = $configFactory->create(array("filename" => $filename));
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
    abstract public function getPageType() : string;

    /**
     * @return string
     */
    public function getContentFilename() : string
    {
        return $this->contentFolder . DIRECTORY_SEPARATOR . static::CONTENT_FILENAME;
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
        return $this->config->getValue("title");
    }

    /**
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->config->getValue("author", null);
    }

    /**
     * @return int
     */
    public function getDatePosted() : int
    {
        return $this->config->getValue("date_posted");
    }

    /**
     * @return int
     */
    public function getDateUpdated() : int
    {
        return $this->config->getValue("date_updated", $this->getDatePosted());
    }

    /**
     * @return array
     */
    public function getTemplateVariables() : array
    {
        return $this->config->getValue("template_vars", array());
    }

    /**
     * @return array
     */
    public function getAdditionalFiles() : array
    {
        $finder = $this->finderFactory->create();
        $finder->files()
            ->in($this->contentFolder)
            ->depth(0)
            ->notName(static::CONFIG_FILENAME)
            ->notName(static::CONTENT_FILENAME)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        $filenames = array();
        foreach ($finder as $file) {
            $filenames[] = $file->getPathname();
        }

        return $filenames;
    }
}
