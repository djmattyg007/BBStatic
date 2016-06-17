<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use Icecave\Collections\NeedsVectorFactoryTrait;
use Icecave\Collections\Vector as PageCollection;
use MattyG\BBStatic\NeedsDirectoryManagerTrait;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;

final class PageGatherer
{
    use NeedsDirectoryManagerTrait;
    use NeedsFinderFactoryTrait;
    use NeedsIndexPageFactoryTrait;
    use NeedsPageFactoryTrait;
    use NeedsVectorFactoryTrait;

    /**
     * @return PageCollection
     */
    public function gatherPages() : PageCollection
    {
        $pageCollection = $this->vectorFactory->create();

        $this->loadPages($pageCollection);
        $this->checkForIndex($pageCollection);

        return $pageCollection;
    }

    /**
     * @param PageCollection $pageCollection
     */
    private function loadPages(PageCollection $pageCollection)
    {
        $finder = $this->finderFactory->create();
        $finder->files()
            ->name(Page::CONFIG_FILENAME)
            ->in($this->directoryManager->getPagesDirectory())
            ->depth("> 0")
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        foreach ($finder as $file) {
            $pageName = $file->getRelativePath();
            $pageCollection->pushBack($this->pageFactory->create(array("name" => $pageName)));
        }
    }

    /**
     * @param PageCollection $pageCollection
     */
    private function checkForIndex(PageCollection $pageCollection)
    {
        $indexConfigFilename = $this->directoryManager->getPagesDirectory() . DIRECTORY_SEPARATOR . Page::CONFIG_FILENAME;
        if (file_exists($indexConfigFilename) === true) {
            $pageCollection->pushBack($this->indexPageFactory->create());
        }
    }
}
