<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command;

use MattyG\BBStatic\NeedsFileBuilderTrait;
use MattyG\BBStatic\Signing\NeedsSignerTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class FolderHandler
{
    use NeedsConfigTrait;
    use NeedsFileBuilderTrait;
    use NeedsSignerTrait;

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
            if ($shouldSign === true) {
                $this->signer->sign($outFilename);
            }
            $io->writeLine("done.", IO::VERBOSE);
        }
    }

    /**
     * @param Args $args
     * @return bool
     */
    private function shouldSignOutput(Args $args) : bool
    {
        $configuredSigningOption = $this->config->getValue("signing/enabled", false);
        if ($configuredSigningOption === true) {
            if ($args->isOptionSet("no-sign") === false) {
                return true;
            }
        } else {
            if ($args->isOptionSet("sign") === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handleClean(Args $args, IO $io)
    {
        $folderPath = $args->getArgument("folder-path");
        $io->writeLine("Folder path: " . $folderPath);

        $finder = new SymfonyFinder();
        $finder->files()
            ->name("*.html")
            ->name($this->signer->getSignatureFileGlobPattern())
            ->in($folderPath)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        foreach ($finder as $file) {
            $filename = $file->getPathname();
            $io->write(sprintf("Deleting %s ... ", $filename), IO::VERBOSE);
            $check = @unlink($filename);
            if ($check !== true) {
                throw new \RuntimeException(sprintf("Error while deleting %s", $filename));
            }
            $io->writeLine("done.", IO::VERBOSE);
        }
    }
}
