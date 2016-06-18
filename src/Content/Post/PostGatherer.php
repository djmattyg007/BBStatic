<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Post;

use Icecave\Collections\NeedsVectorFactoryTrait;
use Icecave\Collections\Vector as PostCollection;
use MattyG\BBStatic\NeedsDirectoryManagerTrait;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;

final class PostGatherer
{
    use NeedsDirectoryManagerTrait;
    use NeedsFinderFactoryTrait;
    use NeedsPostFactoryTrait;
    use NeedsVectorFactoryTrait;

    /**
     * @return PostCollection
     */
    public function gatherPosts() : PostCollection
    {
        $postCollection = $this->vectorFactory->create();

        $this->loadPosts($postCollection);

        return $postCollection;
    }

    /**
     * @param PostCollection $postCollection
     */
    private function loadPosts(PostCollection $postCollection)
    {
        $finder = $this->finderFactory->create();
        $finder->files()
            ->name(Post::CONFIG_FILENAME)
            ->in($this->directoryManager->getPostContentDirectory())
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
