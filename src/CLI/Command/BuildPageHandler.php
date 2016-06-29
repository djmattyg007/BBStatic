<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Command;

use MattyG\BBStatic\Content\Page\NeedsPageBuilderTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildPageHandler
{
    use NeedsConfigTrait;
    use NeedsPageBuilderTrait;
    use ShouldSignOutputTrait;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        if ($this->config->getValue("pages/enabled") === false) {
            $io->errorLine("Pages are not configured");
            return 1;
        }

        $pageName = $args->getArgument("page");

        $this->pageBuilder->createAndBuildPage($pageName, $this->shouldSignOutput($args));
    }
}
