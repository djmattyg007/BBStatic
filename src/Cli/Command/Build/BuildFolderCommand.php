<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command\Build;

use MattyG\BBStatic\NeedsFileBuilderTrait;
use MattyG\BBStatic\Signing\NeedsSignerTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildFolderCommand
{
    use NeedsConfigTrait;
    use NeedsFileBuilderTrait;
    use NeedsSignerTrait;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $folderPath = $args->getArgument("folder-path");
        $io->writeLine("Folder path: " . $folderPath);

        $finder = new SymfonyFinder();
        $finder->files()
            ->name("*.bb")
            ->in($folderPath)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        foreach ($finder as $file) {
            $inFilename = $file->getPathname();
            $io->write(sprintf("Converting %s ... ", $inFilename), IO::VERBOSE);
            $inFilenameParts = pathinfo($inFilename);
            $outFilename = $inFilenameParts["dirname"] . "/" . $inFilenameParts["filename"] . ".html";
            $this->fileBuilder->buildAndOutput($inFilename, $outFilename);
            $io->writeLine("done.", IO::VERBOSE);
        }
    }
}
