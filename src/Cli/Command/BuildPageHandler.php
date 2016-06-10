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

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $pageName = $args->getArgument("page");

        $page = $this->pageBuilder->build($pageName, $this->shouldSignOutput($args));
    }

    /**
     * @param Args $args
     * @return bool
     */
    private function shouldSignOutput(Args $args) : bool
    {
        $configuredSigningOption = $this->config->getValue("signing/enabled", false);
        if ($configuredSigningOption === true) {
            return !$args->isOptionSet("no-sign");
        } else {
            return $args->isOptionSet("sign");
        }
    }
}
