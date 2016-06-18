<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Page;

use MattyG\BBStatic\DirectoryManager;
use MattyG\BBStatic\Util\ConfigFactory;

class IndexPage extends Page
{
    /**
     * @param string $name
     * @param DirectoryManager $directoryManager
     * @param ConfigFactory $configFactory
     */
    public function __construct(DirectoryManager $directoryManager, ConfigFactory $configFactory)
    {
        $this->name = "index";
        $this->contentFolder = $directoryManager->getPageContentDirectory();
        $this->outputFolder = $directoryManager->getPageOutputDirectory();

        $this->loadConfig($configFactory);
    }

    /**
     * @return string
     */
    public function getPageType() : string
    {
        return "homepage";
    }
}
