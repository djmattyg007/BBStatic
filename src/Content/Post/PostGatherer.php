<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Post;

use Icecave\Collections\NeedsVectorFactoryTrait;
use Icecave\Collections\Vector as PostCollection;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;

final class PostGatherer implements PostGathererInterface
{
    use NeedsFinderFactoryTrait;
    use NeedsPostFactoryTrait;
    use NeedsVectorFactoryTrait;

    /**
     * @param string $searchPath
     * @return PostCollection
     */
    public function gatherPosts(string $searchPath) : PostCollection
    {
        $postCollection = $this->vectorFactory->create();

        $this->loadPosts($postCollection, $searchPath);

        return $postCollection;
    }

    /**
     * @param PostCollection $postCollection
     * @param string $searchPath
     */
    private function loadPosts(PostCollection $postCollection, string $searchPath)
    {
        $finder = $this->finderFactory->create();
        $finder->files()
            ->name(Post::CONFIG_FILENAME)
            ->in($searchPath)
            ->depth("1")
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        foreach ($finder as $file) {
            $postName = $file->getRelativePath();
            $postCollection->pushBack($this->postFactory->create(array("name" => $postName)));
        }
    }
}
