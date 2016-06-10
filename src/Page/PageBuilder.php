<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use MattyG\BBStatic\Page\NeedsPageFactoryTrait;
use MattyG\BBStatic\Page\NeedsPageRendererTrait;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

final class PageBuilder
{
    /**
     * @param string $pageName
     * @param bool $shouldSign
     * @return Page
     */
    public function build(string $pageName, bool $shouldSign) : Page
    {
        $page = $this->pageFactory->create(array("name" => $pageName));

        $renderedPageFilename = $this->pageRenderer->render($page);
        if ($shouldSign === true) {
            $this->signingAdapter->sign($renderedPageFilename);
        }

        $pageOutputFolder = $page->getOutputFolder() . DIRECTORY_SEPARATOR;
        foreach ($page->getAdditionalFiles() as $additionalFile) {
            $outputFile = $pageOutputFolder . basename($additionalFile);
            $this->filesystem->copy($additionalFile, $outputFile, true);
            if ($shouldSign === true) {
                $this->signingAdapter->sign($outputFile);
            }
        }

        return $page;
    }
}
