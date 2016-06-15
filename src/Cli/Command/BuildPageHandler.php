<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command;

use MattyG\BBStatic\Page\NeedsPageBuilderTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildPageHandler
{
    use NeedsConfigTrait;
    use ShouldSignOutputTrait;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $pageName = $args->getArgument("page");

        $this->pageBuilder->createAndBuildPage($pageName, $this->shouldSignOutput($args));
    }
}
