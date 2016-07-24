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
    private $contentPath;

    /**
     * @var string
     */
    private $outputPath;

    /**
     * @var string
     */
    private $urlPath;

    /**
     * @var int
     */
    private $postsPerPage;

    /**
     * @var PostCollection
     */
    private $postCollection = null;

    /**
     * @param string $contentPath
     * @param string $outputPath
     * @param string $urlPath
     * @param int $postsPerPage
     */
    public function __construct(string $contentPath, string $outputPath, string $urlPath, int $postsPerPage)
    {
        $this->contentPath = $contentPath;
        $this->outputPath = $outputPath;
        $this->urlPath = $urlPath;
        $this->postsPerPage = $postsPerPage;
    }

    /**
     * @return string
     */
    public function getContentPath() : string
    {
        return $this->contentPath;
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
     * @return int
     */
    public function getPostsPerPage() : int
    {
        return $this->postsPerPage;
    }

    /**
     * @return PostCollection
     */
    public function getPostCollection() : PostCollection
    {
        if ($this->postCollection === null) {
            $this->postCollection = $this->postGatherer->gatherPosts($this->contentPath);
        }
        return $this->postCollection;
    }
}
