<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command;

use MattyG\BBStatic\Page\NeedsPageBuilderTrait;
use MattyG\BBStatic\Page\NeedsPageGathererTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildHandler
{
    use NeedsConfigTrait;
    use NeedsPageBuilderTrait;
    use NeedsPageGathererTrait;
    use ShouldSignOutputTrait;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $shouldSignOutput = $this->shouldSignOutput($args);
        $io->writeLine("Signing output: " . ($shouldSignOutput === true ? "true" : "false"));

        $pageCollection = $this->pageGatherer->gatherPages();
        $io->writeLine(sprintf("Found %d pages", count($pageCollection)));

        $this->pageBuilder->buildPages($pageCollection, $shouldSignOutput);
    }
}
