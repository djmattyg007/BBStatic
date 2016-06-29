<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content;

use Icecave\Collections\Vector as PostCollection;
use MattyG\BBStatic\Content\Post\NeedsPostGathererInterfaceTrait;

class Blog
{
    use NeedsPostGathererInterfaceTrait;

    /**
     * @var string
     */
    private $outputPath;

    /**
     * @var string
     */
    private $urlPath;

    /**
     * @var PostCollection
     */
    private $postCollection = null;

    /**
     * @param string $outputPath
     * @param string $urlPath
     */
    public function __construct(string $outputPath, string $urlPath)
    {
        $this->outputPath = $outputPath;
        $this->urlPath = $urlPath;
    }

    /**
     * @return string
     */
    public function getOutputPath() : string
    {
        return $this->outputPath;
    }

    /**
     * @return string
     */
    public function getUrlPath() : string
    {
        return $this->urlPath;
    }

    /**
     * @return PostCollection
     */
    public function getPostCollection() : PostCollection
    {
        if ($this->postCollection === null) {
            $this->postCollection = $this->postGatherer->gatherPosts();
        }
        return $this->postCollection;
    }
}
