<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Command;

use MattyG\BBStatic\Content\Page\NeedsPageBuilderTrait;
use MattyG\BBStatic\Content\Page\NeedsPageGathererTrait;
use MattyG\BBStatic\Content\Post\NeedsPostBuilderTrait;
use MattyG\BBStatic\Content\Post\NeedsPostGathererTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildHandler
{
    use NeedsConfigTrait;
    use NeedsPageBuilderTrait;
    use NeedsPageGathererTrait;
    use NeedsPostBuilderTrait;
    use NeedsPostGathererTrait;
    use ShouldSignOutputTrait;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $shouldSignOutput = $this->shouldSignOutput($args);
        $io->writeLine("Signing output: " . ($shouldSignOutput === true ? "true" : "false"));

        $pagesEnabled = $this->config->getValue("site/pages_url_path") !== null;
        $postsEnabled = $this->config->getValue("site/posts_url_path") !== null;
        if ($pagesEnabled === false && $postsEnabled === false) {
            $io->errorLine("Neither pages nor posts are configured");
            return 1;
        }

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

        $this->pageBuilder->buildPages($pageCollection, $shouldSignOutput);
    }

    /**
     * @param IO $io
     * @param bool $shouldSignOutput
     */
    private function buildPosts(IO $io, bool $shouldSignOutput)
    {
        $postCollection = $this->postGatherer->gatherPosts();
        $io->writeLine(sprintf("Found %d posts", count($postCollection)));

        $this->postBuilder->buildPosts($postCollection, $shouldSignOutput);
    }
}
