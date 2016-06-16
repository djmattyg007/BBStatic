<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use Icecave\Collections\Vector as PageCollection;
use MattyG\BBStatic\Page\NeedsPageFactoryTrait;
use MattyG\BBStatic\Page\NeedsPageRendererTrait;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;

final class PageBuilder
{
    use NeedsFinderFactoryTrait;
    use NeedsFilesystemTrait;
    use NeedsPageFactoryTrait;
    use NeedsPageRendererTrait;
    use NeedsSigningAdapterInterfaceTrait;

    /**
     * @param PageCollection $pages
     * @param bool $shouldSign
     * @param callable|null $progressUpdate
     */
    public function buildPages(PageCollection $pages, bool $shouldSign, callable $progressUpdate = null)
    {
        $totalPageCount = count($pages);
        $counter = 0;
        foreach ($pages as $page) {
            $counter++;
            $this->buildPage($page, $shouldSign);
            if ($progressUpdate !== null) {
                $progressUpdate($counter, $totalCount);
            }
        }
    }

    /**
     * @param Page $page
     * @param bool $shouldSign
     */
    public function buildPage(Page $page, bool $shouldSign)
    {
        $this->cleanFolder($page->getOutputFolder());

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
     * @param string $folderPath
     */
    private function cleanFolder(string $folderPath)
    {
        $finder = $this->finderFactory->create();
        $finder->files()
            ->in($folderPath)
            ->depth(0)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true);

        foreach ($finder as $file) {
            $this->filesystem->remove($file->getPathname());
        }
    }

    /**
     * @param string $pageName
     * @param bool $shouldSign
     * @return Page
     */
    public function createAndBuildPage(string $pageName, bool $shouldSign) : Page
    {
        $page = $this->pageFactory->create(array("name" => $pageName));
        $this->buildPage($page, $shouldSign);
        return $page;
    }
}
