<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Command;

use MattyG\BBStatic\CLI\Vendor\NeedsProgressBarFactoryTrait;
use MattyG\BBStatic\Content\NeedsBlogBuilderTrait;
use MattyG\BBStatic\Content\NeedsBlogFactoryTrait;
use MattyG\BBStatic\Content\Page\NeedsPageBuilderTrait;
use MattyG\BBStatic\Content\Page\NeedsPageGathererTrait;
use MattyG\BBStatic\Content\Post\NeedsPostBuilderTrait;
use MattyG\BBStatic\Content\Post\NeedsPostGathererTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildHandler
{
    use NeedsBlogBuilderTrait;
    use NeedsBlogFactoryTrait;
    use NeedsConfigTrait;
    use NeedsPageBuilderTrait;
    use NeedsPageGathererTrait;
    use NeedsPostBuilderTrait;
    use NeedsPostGathererTrait;
    use NeedsProgressBarFactoryTrait;
    use ShouldSignOutputTrait;

    /**
     * @var ProgressBar
     */
    protected $progressBar;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $pagesEnabled = $this->config->getValue("pages/enabled");
        $postsEnabled = $this->config->getValue("posts/enabled");
        if ($pagesEnabled === false && $postsEnabled === false) {
            $io->errorLine("Neither pages nor posts are configured");
            return 1;
        }

        $shouldSignOutput = $this->shouldSignOutput($args);
        $io->writeLine("Signing output: " . ($shouldSignOutput === true ? "true" : "false"));

        if ($pagesEnabled === true) {
            $this->buildPages($io, $shouldSignOutput);
        }

        if ($postsEnabled === true) {
            $this->buildPosts($io, $shouldSignOutput);
        }
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
    private function buildPosts(IO $io, bool $shouldSignOutput)
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
     * @param int $totalUnits
     */
    protected function startProgressBar(IO $io, int $totalUnits)
    {
        $this->progressBar = $this->progressBarFactory->create($io, $totalUnits);
        $this->progressBar->start();
    }

    /**
     * TODO: Change to protected, use Closure::fromCallable() in PHP 7.1
     */
    public function advanceProgressBar()
    {
        $this->progressBar->advance();
    }

    protected function finishProgressBar()
    {
        $this->progressBar->finish();
        $this->progressBar = null;
    }
}
