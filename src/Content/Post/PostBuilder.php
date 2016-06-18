<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Post;

use Icecave\Collections\Vector as PostCollection;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;

final class PostBuilder
{
    use NeedsFinderFactoryTrait;
    use NeedsFilesystemTrait;
    use NeedsPostFactoryTrait;
    use NeedsPostRendererTrait;
    use NeedsSigningAdapterInterfaceTrait;

    /**
     * @param PostCollection $pages
     * @param bool $shouldSign
     * @param callable|null $progressUpdate
     */
    public function buildPosts(PostCollection $posts, bool $shouldSign, callable $progressUpdate = null)
    {
        $totalPostCount = count($posts);
        $counter = 0;
        foreach ($posts as $post) {
            $counter++;
            $this->buildPost($post, $shouldSign);
            if ($progressUpdate !== null) {
                $progressUpdate($counter, $totalCount);
            }
        }
    }

    /**
     * @param Post $post
     * @param bool $shouldSign
     */
    public function buildPost(Post $post, bool $shouldSign)
    {
        $this->cleanFolder($post->getOutputFolder());

        $renderedPostFilename = $this->postRenderer->render($post);
        if ($shouldSign === true) {
            $this->signingAdapter->sign($renderedPostFilename);
        }

        $postOutputFolder = $post->getOutputFolder() . DIRECTORY_SEPARATOR;
        foreach ($post->getAdditionalFiles() as $additionalFile) {
            $outputFile = $postOutputFolder . basename($additionalFile);
            $this->filesystem->copy($additionalFile, $outputFile, true);
            if ($shouldSign === true) {
                $this->signingAdapter->sign($outputFile);
            }
        }
    }

    /**
     * @param string $folderPath
     */
    private function cleanFolder(string $folderPath)
    {
        // Output folder doesn't exist yet
        if (!is_dir($folderPath)) {
            return;
        }

        $finder = $this->finderFactory->create();
        $finder->files()
            ->in($folderPath)
            ->depth(0)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true);

        foreach ($finder as $file) {
            $this->filesystem->remove($file->getPathname());
        }
    }

    /**
     * @param string $postName
     * @param bool $shouldSign
     * @return Post
     */
    public function createAndBuildPost(string $postName, bool $shouldSign) : Post
    {
        $post = $this->postFactory->create(array("name" => $postName));
        $this->buildPage($post, $shouldSign);
        return $post;
    }
}
