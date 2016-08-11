<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Command;

use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildHandler extends AbstractBuildHandler
{
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

        $this->build($args, $io);
    }
}
