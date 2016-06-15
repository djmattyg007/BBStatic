<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command;

use Icecave\Collections\NeedsVectorFactoryTrait;
use Icecave\Parity\Comparator\NeedsParityComparatorTrait;
use MattyG\BBStatic\Page\NeedsPageBuilderTrait;
use MattyG\BBStatic\Page\NeedsPageFactoryTrait;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class FolderHandler
{
    use NeedsConfigTrait;
    use NeedsFilesystemTrait;
    use NeedsFinderFactoryTrait;
    use NeedsPageBuilderTrait;
    use NeedsPageFactoryTrait;
    use NeedsParityComparatorTrait;
    use NeedsSigningAdapterInterfaceTrait;
    use NeedsVectorFactoryTrait;
    use ShouldSignOutputTrait;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handleBuild(Args $args, IO $io)
    {
        $folderPath = $args->getArgument("folder-path");
        $shouldSign = $this->shouldSignOutput($args);

        $io->writeLine("Folder path: " . $folderPath);
        $io->writeLine("Signing output: " . ($shouldSign === true ? "yes" : "no"));

        $finder = $this->finderFactory->create();
        $finder->files()
            ->name("config.json")
            ->in($folderPath)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        $pageCollection = $this->vectorFactory->create();
        foreach ($finder as $file) {
            $inFilename = $file->getRelativePath();
            $page = $this->pageFactory->create(array("name" => $inFilename));
            $pageCollection->pushBack($page);
        }

        $sortedPageCollection = $pageCollection->sort($this->parityComparator);
        foreach ($sortedPageCollection as $page) {
            $io->writeLine("Found " . $page->getName());
        }
    }

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handleClean(Args $args, IO $io)
    {
        $folderPath = $args->getArgument("folder-path");
        $io->writeLine("Folder path: " . $folderPath);

        $finder = $this->finderFactory->create();
        $finder->files()
            ->name("*.html")
            // TODO: This line must be performed conditionally
            ->name($this->signingAdapter->getSignatureFileGlobPattern())
            ->in($folderPath)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        foreach ($finder as $file) {
            $filename = $file->getPathname();
            $io->write(sprintf("Deleting %s ... ", $filename), IO::VERBOSE);
            $this->filesystem->remove($filename);
            $io->writeLine("done.", IO::VERBOSE);
        }
    }
}
