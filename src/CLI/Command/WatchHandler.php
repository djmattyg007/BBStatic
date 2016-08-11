<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Command;

use MattyG\BBStatic\BBStatic;
use MattyG\BBStatic\NeedsDirectoryManagerTrait;
use Lurker\NeedsResourceWatcherTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class WatchHandler extends AbstractBuildHandler
{
    use NeedsDirectoryManagerTrait;
    use NeedsResourceWatcherTrait;

    /**
     * @var Args
     */
    private $args;

    /**
     * @var IO
     */
    private $io;

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

        $this->args = $args;
        $this->io = $io;
        $this->initResourceWatcher();
        $this->resourceWatcher->start();
    }

    private function initResourceWatcher()
    {
        $paths = array(
            BBStatic::CONFIG_FILENAME,
            $this->directoryManager->getConfigFolderDirectory(),
            $this->directoryManager->getThemeDirectory(),
            $this->directoryManager->getPageContentDirectory(),
            $this->directoryManager->getBlogContentDirectory(),
        );
        // TODO: PHP 7.1 use Closure::fromCallable() instead
        foreach ($paths as $path) {
            $this->resourceWatcher->trackByListener($path, array($this, "resourceWatcherCallback"));
        }
    }

    public function resourceWatcherCallback()
    {
        $this->build($this->args, $this->io);
    }
}
