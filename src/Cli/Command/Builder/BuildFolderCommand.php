<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command\Builder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class BuildFolderCommand extends Command
{
    use BuilderTrait;

    protected function configure()
    {
        $this->setName("build:folder")
            ->setDescription("Convert a folder full of BBCode files into HTML.")
            ->addArgument(
                "folder_path",
                InputArgument::REQUIRED,
                "Folder full of files to convert"
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $folderPath = $input->getArgument("folder_path");
        $output->writeln("Folder path: " . $folderPath);

        $finder = new SymfonyFinder();
        $finder->files()
            ->name("*.bb")
            ->in($folderPath)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        $fileBuilder = $this->getFileBuilder();
        foreach ($finder as $file) {
            $inFilename = $file->getPathname();
            $output->write(sprintf("Converting %s ... ", $inFilename), false, OutputInterface::OUTPUT_NORMAL | OutputInterface::VERBOSITY_VERBOSE);
            $inFilenameParts = pathinfo($inFilename);
            $outFilename = $inFilenameParts["dirname"] . "/" . $inFilenameParts["filename"] . ".html";
            $fileBuilder->buildAndOutput($inFilename, $outFilename);
            $output->write("done.", true, OutputInterface::OUTPUT_NORMAL | OutputInterface::VERBOSITY_VERBOSE);
        }
    }
}
