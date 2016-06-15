<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use Icecave\Collections\NeedsVectorFactoryTrait;
use Icecave\Collections\Vector as PageCollection;
use MattyG\BBStatic\NeedsDirectoryManagerTrait;
use MattyG\BBStatic\Page\NeedsPageFactoryTrait;
use Symfony\Component\Finder\NeedsFinderFactoryTrait;

final class PageGatherer
{
    use NeedsDirectoryManagerTrait;
    use NeedsFinderFactoryTrait;
    use NeedsPageFactoryTrait;
    use NeedsVectorFactoryTrait;

    /**
     * @return PageCollection
     */
    public function gatherPages() : PageCollection
    {
        $finder = $this->finderFactory->create();
        $finder->files()
            ->name(Page::CONFIG_FILENAME)
            ->in($this->directoryManager->getPagesDirectory())
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        $pageCollection = $this->vectorFactory->create();
        foreach ($finder as $file) {
            $pageName = $file->getRelativePath();
            $pageCollection->pushBack($this->pageFactory->create(array("name" => $pageName)));
        }

        return $pageCollection;
    }
}
