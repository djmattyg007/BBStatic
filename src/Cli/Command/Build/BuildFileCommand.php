<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command\Build;

use MattyG\BBStatic\NeedsFileBuilderTrait;
use MattyG\BBStatic\Signing\NeedsSignerTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildFileCommand
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
        $inFilename = $args->getArgument("in-filename");
        $outFilename = $args->getArgument("out-filename");
        if (!$outFilename) {
            $inFilenameParts = pathinfo($inFilename);
            $outFilename = $inFilenameParts["dirname"] . DIRECTORY_SEPARATOR . $inFilenameParts["filename"] . ".html";
        }

        $this->fileBuilder->buildAndOutput($inFilename, $outFilename);

        $io->writeLine(sprintf("In Filename: %s", $inFilename));
        $io->writeLine(sprintf("Out Filename: %s", $outFilename));
    }
}
