<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content;

use Icecave\Collections\Vector as PostCollection;
use Icecave\Parity\Comparator\NeedsParityComparatorTrait;
use MattyG\BBStatic\BBCode\NeedsBBCodeRendererTrait;
use MattyG\BBStatic\Content\Post\Post;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use MattyG\BBStatic\Template\NeedsTemplateEngineInterfaceTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

class BlogBuilder
{
    use NeedsBBCodeRendererTrait;
    use NeedsFilesystemTrait;
    use NeedsParityComparatorTrait;
    use NeedsSigningAdapterInterfaceTrait;
    use NeedsTemplateEngineInterfaceTrait;

    /**
     * @param Blog $blog
     * @param bool $shouldSign
     * @param callable|null $progressUpdate
     */
    final public function buildBlogIndex(Blog $blog, bool $shouldSign, callable $progressUpdate = null)
    {
        // TODO: Clean folder before building a la posts and pages
        $sortedPosts = $blog->getPostCollection()->sort($this->parityComparator);

        $postContext = array();
        foreach ($sortedPosts as $post) {
            $renderedContent = $this->bbcodeRenderer->build($post->getContentFilename());
            $renderedSummary = $this->bbcodeRenderer->build($post->getSummaryFilename());
            $postContext[] = array_merge(
                $this->preparePostContext($post),
                array(
                    "content" => $renderedContent,
                    "summary" => $renderedSummary,
                )
            );
        }

        $context = array("blog" => $this->prepareBlogContext($blog), "posts" => $postContext);
        $renderedPage = $this->templateEngine->render("blogindex", $context);
        $outFilename = $blog->getOutputPath() . DIRECTORY_SEPARATOR . "index.html";
        $this->filesystem->dumpFile($outFilename, $renderedPage);
        if ($shouldSign === true) {
            $this->signingAdapter->sign($outFilename);
        }
    }

    /**
     * @param Blog $blog
     * @return array
     */
    protected function prepareBlogContext(Blog $blog) : array
    {
        return array(
            "url_path" => $blog->getUrlPath(),
        );
    }

    /**
     * @param Post $post
     * @return array
     */
    protected function preparePostContext(Post $post) : array
    {
        return array(
            "title" => $post->getTitle(),
            "author" => $post->getAuthor(),
            "date_posted" => $post->getDatePosted(),
            "date_updated" => $post->getDateUpdated(),
            "tags" => $post->getTags(),
            "vars" => $post->getTemplateVariables(),
            "url_path" => $post->getUrlPath(),
        );
    }
}
