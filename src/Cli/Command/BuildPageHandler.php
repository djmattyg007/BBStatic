<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command;

use MattyG\BBStatic\NeedsFileBuilderTrait;
use MattyG\BBStatic\Page\NeedsPageFactoryTrait;
use MattyG\BBStatic\Page\NeedsPageRendererTrait;
use MattyG\BBStatic\Signing\NeedsSignerTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildPageHandler
{
    use NeedsConfigTrait;
    use NeedsFileBuilderTrait;
    use NeedsPageFactoryTrait;
    use NeedsPageRendererTrait;
    use NeedsSignerTrait;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $pageName = $args->getArgument("page");
        $io->writeLine(sprintf("Page Name: %s", $pageName));

        $page = $this->pageFactory->create(array("name" => $pageName));

        $renderedPageFilename = $this->pageRenderer->renderPage($page);
        $this->signOutputFile($args, $renderedPageFilename);

        $io->writeLine(sprintf("Rendered Page Filename: %s", $renderedPageFilename));
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
                $this->signer->sign($filename);
            }
        } else {
            if ($args->isOptionSet("sign") === true) {
                $this->signer->sign($filename);
            }
        }
    }
}
