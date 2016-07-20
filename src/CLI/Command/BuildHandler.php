<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Command;

use MattyG\BBStatic\Asset\NeedsAssetManagerTrait;
use MattyG\BBStatic\CLI\Vendor\NeedsProgressBarFactoryTrait;
use MattyG\BBStatic\Content\NeedsBlogBuilderTrait;
use MattyG\BBStatic\Content\NeedsBlogFactoryTrait;
use MattyG\BBStatic\Content\Page\NeedsPageBuilderTrait;
use MattyG\BBStatic\Content\Page\NeedsPageGathererTrait;
use MattyG\BBStatic\Content\Post\NeedsPostBuilderTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildHandler
{
    use NeedsAssetManagerTrait;
    use NeedsBlogBuilderTrait;
    use NeedsBlogFactoryTrait;
    use NeedsConfigTrait;
    use NeedsPageBuilderTrait;
    use NeedsPageGathererTrait;
    use NeedsPostBuilderTrait;
    use NeedsProgressBarFactoryTrait;
    use ShouldSignOutputTrait;

    /**
     * @var ProgressBar
     */
    protected $progressBar = null;

    /**
     * @var bool
     */
    protected $progressBarStarted = false;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $pagesEnabled = $this->config->getValue("pages/enabled");
        $blogEnabled = $this->config->getValue("blog/enabled");
        if ($pagesEnabled === false && $blogEnabled === false) {
            $io->errorLine("Neither pages nor posts are configured");
            return 1;
        }

        $shouldSignOutput = $this->shouldSignOutput($args);
        $io->writeLine("Signing output: " . ($shouldSignOutput === true ? "true" : "false"));

        if ($pagesEnabled === true) {
            $this->buildPages($io, $shouldSignOutput);
        }

        if ($blogEnabled === true) {
            $this->buildBlog($io, $shouldSignOutput);
        }

        $this->prepareAssets($io, $shouldSignOutput);
    }

    /**
     * @param IO $io
     * @param bool $shouldSignOutput
     */
    private function buildPages(IO $io, bool $shouldSignOutput)
    {
        $pageCollection = $this->pageGatherer->gatherPages();
        $io->writeLine(sprintf("Found %d pages", count($pageCollection)));

        $this->startProgressBar($io, count($pageCollection));
        try {
            $this->pageBuilder->buildPages($pageCollection, $shouldSignOutput, array($this, "advanceProgressBar"));
        } finally {
            $this->finishProgressBar();
        }
        $io->writeLine("");
    }

    /**
     * @param IO $io
     * @param bool $shouldSignOutput
     */
    private function buildBlog(IO $io, bool $shouldSignOutput)
    {
        $blog = $this->blogFactory->create();
        $postCollection = $blog->getPostCollection();
        $io->writeLine(sprintf("Found %d posts", count($postCollection)));

        $this->startProgressBar($io, count($postCollection));
        try {
            $this->postBuilder->buildPosts($postCollection, $shouldSignOutput, array($this, "advanceProgressBar"));
        } finally {
            $this->finishProgressBar();
        }
        $io->writeLine("");

        $io->writeLine("Building blog index");
        $this->blogBuilder->buildBlogIndex($blog, $shouldSignOutput);
    }

    /**
     * @param IO $io
     * @param bool $shouldSignOutput
     */
    private function prepareAssets(IO $io, bool $shouldSignOutput)
    {
        $io->writeLine("Preparing assets");

        $this->startProgressBar($io);
        try {
            $this->assetManager->writeAssets($shouldSignOutput, array($this, "advanceProgressBar"));
        } finally {
            $this->finishProgressBar();
        }
        $io->writeLine("");
    }

    /**
     * TODO: Use nullable types for $totalUnits
     * @param IO $io
     * @param int|null $totalUnits
     */
    protected function startProgressBar(IO $io, $totalUnits = null)
    {
        $this->progressBar = $this->progressBarFactory->create($io, $totalUnits === null ? 0 : $totalUnits);
        if ($totalUnits !== null) {
            $this->progressBar->start($totalUnits);
            $this->progressBarStarted = true;
        }
    }

    /**
     * TODO: Change to protected, use Closure::fromCallable() in PHP 7.1
     *
     * @param int $counter
     * @param int $totalCount
     */
    public function advanceProgressBar(int $counter, int $totalCount)
    {
        if ($this->progressBarStarted === false) {
            $this->progressBar->start($totalCount);
            $this->progressBarStarted = true;
        }
        $this->progressBar->advance();
    }

    protected function finishProgressBar()
    {
        if ($this->progressBarStarted === true) {
            $this->progressBar->finish();
        }
        $this->progressBar = null;
        $this->progressBarStarted = false;
    }
}
