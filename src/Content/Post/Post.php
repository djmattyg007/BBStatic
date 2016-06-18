<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Post;

use Icecave\Parity\Exception\NotComparableException;
use Icecave\Parity\ExtendedComparableInterface;
use Icecave\Parity\ExtendedComparableTrait;
use Icecave\Parity\SubClassComparableInterface;
use MattyG\BBStatic\Content\ContentEntity;
use MattyG\BBStatic\DirectoryManager;
use MattyG\BBStatic\Util\ConfigFactory;

class Post extends ContentEntity implements ExtendedComparableInterface, SubClassComparableInterface
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
        $this->contentFolder = $directoryManager->getPostContentDirectory() . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $name);
        $this->outputFolder = $directoryManager->getPostOutputDirectory() . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $name);

        $this->loadConfig($configFactory);
    }

    /**
     * @return string
     */
    public function getPageType() : string
    {
        return "post";
    }

    /**
     * @return string[]
     */
    public function getTags() : array
    {
        return $this->config->getValue("tags", array());
    }

    /**
     * @param Post $value
     * @return int The result of the comparison
     * @throws NotComparableException
     */
    public function compare($value)
    {
        if (is_object($value) === false) {
            throw new NotComparableException(sprintf("Cannot compare %s with Post", gettype($value)));
        }
        if (!$value instanceof Post) {
            throw new NotComparableException(sprintf("%s is not of type Post", get_class($value)));
        }

        return $this->getDatePosted() - $value->getDatePosted();
    }
}
