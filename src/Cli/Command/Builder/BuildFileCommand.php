<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command\Builder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildFileCommand extends Command
{
    use BuilderTrait;

    protected function configure()
    {
        $this->setName("build:file")
            ->setDescription("Convert a single file from BBCode into HTML.")
            ->addArgument(
                "in_filename",
                InputArgument::REQUIRED,
                "File to convert"
            )
            ->addArgument(
                "out_filename",
                InputArgument::OPTIONAL,
                "File to create with converted content"
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inFilename = $input->getArgument("in_filename");
        $outFilename = $input->getArgument("out_filename");
        if (!$outFilename) {
            $inFilenameParts = pathinfo($inFilename);
            $outFilename = $inFilenameParts["dirname"] . "/" . $inFilenameParts["filename"] . ".html";
        }

        $this->getFileBuilder()->buildAndOutput($inFilename, $outFilename);

        $output->writeln("In Filename: " . $inFilename);
        $output->writeln("Out Filename: " . $outFilename);
    }
}
