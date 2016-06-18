<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Page;

use Icecave\Parity\Exception\NotComparableException;
use Icecave\Parity\ExtendedComparableInterface;
use Icecave\Parity\ExtendedComparableTrait;
use Icecave\Parity\SubClassComparableInterface;
use MattyG\BBStatic\Content\ContentEntity;
use MattyG\BBStatic\DirectoryManager;
use MattyG\BBStatic\Util\ConfigFactory;

class Page extends ContentEntity implements ExtendedComparableInterface, SubClassComparableInterface
{
    use ExtendedComparableTrait;

    /**
     * @param string $name
     * @param DirectoryManager $directoryManager
     * @param ConfigFactory $configFactory
     */
    public function __construct(string $name, DirectoryManager $directoryManager, ConfigFactory $configFactory)
    {
        $this->name = $name;
        $this->contentFolder = $directoryManager->getPagesDirectory() . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $name);
        $this->outputFolder = $directoryManager->getHtmlDirectory() . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $name);

        $this->loadConfig($configFactory);
    }

    /**
     * @return string
     */
    public function getPageType() : string
    {
        return $this->config->getValue("page_type") ?: "default";
    }

    /**
     * @param Page $value
     * @return int The result of the comparison
     * @throws NotComparableException
     */
    public function compare($value)
    {
        if (is_object($value) === false) {
            throw new NotComparableException(sprintf("Cannot compare %s with Page", gettype($value)));
        }
        if (!value instanceof Page) {
            throw new NotComparableException(sprintf("%s is not of type Page", get_class($value)));
        }

        return $this->getDatePosted() - $value->getDatePosted();
    }
}
