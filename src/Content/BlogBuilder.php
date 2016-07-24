<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content;

use Icecave\Collections\Vector as PostCollection;
use Icecave\Parity\Comparator\NeedsParityComparatorTrait;
use MattyG\BBStatic\BBCode\NeedsBBCodeRendererTrait;
use MattyG\BBStatic\Content\Post\Post;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use MattyG\BBStatic\Template\NeedsTemplateEngineInterfaceTrait;
use MattyG\BBStatic\Util\Vendor\NeedsIcecaveCollectionPagerFactoryTrait;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

class BlogBuilder
{
    use NeedsBBCodeRendererTrait;
    use NeedsFilesystemTrait;
    use NeedsIcecaveCollectionPagerFactoryTrait;
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
        $pager = $this->icecaveCollectionPagerFactory->create($sortedPosts);
        $pager->setMaxPerPage($blog->getPostsPerPage());

        $blogContext = $this->prepareBlogContext($blog);
        $pageCount = 0;
        do {
            $pager->setCurrentPage(++$pageCount);
            $context = array(
                "blog" => $blogContext,
                "pager" => $this->preparePagerContext($pager),
                "posts" => $this->preparePostsContext($pager->getCurrentPageResults()),
            );
            $renderedPage = $this->templateEngine->render("blogindex", $context);

            $outFilename = $blog->getOutputPath() . DIRECTORY_SEPARATOR . sprintf("page%d.html", $pageCount);
            $this->filesystem->dumpFile($outFilename, $renderedPage);
            if ($shouldSign === true) {
                $this->signingAdapter->sign($outFilename);
            }
        } while ($pager->hasNextPage() === true);

        $this->filesystem->copy($blog->getOutputPath() . DIRECTORY_SEPARATOR . "page1.html", $blog->getOutputPath() . DIRECTORY_SEPARATOR . "index.html");
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
     * @param Pagerfanta $pager
     * @return array
     */
    private function preparePagerContext(Pagerfanta $pager) : array
    {
        return array(
            "total_results" => $pager->getNbResults(),
            "total_pages" => $pager->getNbPages(),
            "current_page" => $pager->getCurrentPage(),
            "can_paginate" => $pager->haveToPaginate(),
            "prev_page" => $pager->hasPreviousPage() ? $pager->getPreviousPage() : null,
            "next_page" => $pager->hasNextPage() ? $pager->getNextPage() : null,
        );
    }

    /**
     * @param iterable $posts
     * @return array
     */
    private function preparePostsContext($posts) : array
    {
        $postContext = array();

        foreach ($posts as $post) {
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

        return $postContext;
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
