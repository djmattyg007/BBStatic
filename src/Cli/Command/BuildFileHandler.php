<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command;

use MattyG\BBStatic\BBCode\NeedsBBCodeRendererTrait;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildFileHandler
{
    use NeedsConfigTrait;
    use NeedsBBCodeRendererTrait;
    use NeedsSigningAdapterInterfaceTrait;

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

        $this->bbcodeRenderer->buildAndOutput($inFilename, $outFilename);
        $this->signOutputFile($args, $outFilename);

        $io->writeLine(sprintf("In Filename: %s", $inFilename));
        $io->writeLine(sprintf("Out Filename: %s", $outFilename));
    }

    /**
     * @param Args $args
     * @param string $filename
     */
    private function signOutputFile(Args $args, string $filename)
    {
        $configuredSigningOption = $this->config->getValue("signing/enabled", false);
        if ($configuredSigningOption === true) {
            if ($args->isOptionSet("no-sign") === false) {
                $this->signingAdapter->sign($filename);
            }
        } else {
            if ($args->isOptionSet("sign") === true) {
                $this->signingAdapter->sign($filename);
            }
        }
    }
}
