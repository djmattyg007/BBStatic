<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use Icecave\Collections\Vector as PageCollection;
use MattyG\BBStatic\Page\NeedsPageFactoryTrait;
use MattyG\BBStatic\Page\NeedsPageRendererTrait;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

final class PageBuilder
{
    use NeedsPageFactoryTrait;
    use NeedsPageRendererTrait;
    use NeedsSigningAdapterInterfaceTrait;
    use NeedsFilesystemTrait;

    public function buildPages(PageCollection $pages, bool $shouldSign)
    {
        foreach ($pages as $page) {
            $this->buildPage($page, $shouldSign);
        }
    }

    /**
     * @param Page $page
     * @param bool $shouldSign
     * @return Page
     */
    public function buildPage(Page $page, bool $shouldSign) : Page
    {
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
    }

    /**
     * @param string $pageName
     * @param bool $shouldSign
     */
    public function createAndBuildPage(string $pageName, bool $shouldSign) : Page
    {
        $page = $this->pageFactory->create(array("name" => $pageName));
        $this->buildPage($page, $shouldSign);
        return $page;
    }
}
