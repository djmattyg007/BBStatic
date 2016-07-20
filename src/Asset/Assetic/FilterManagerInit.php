<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Asset\Assetic;

use Assetic\FilterManager;
use Assetic\Filter\FilterInterface;
use Aura\Di\Container as DiContainer;
use Symfony\Component\Finder\FinderFactory;

/**
 * @param DiContainer $di
 * @param string $filename
 * @return FilterInterface
 */
function MattyGBBStaticFilterManagerInitRequire(DiContainer $di, string $filename) : FilterInterface
{
    return require($filename);
}

final class FilterManagerInit
{
    /**
     * @var DiContainer
     */
    private $di;

    /**
     * @var string[]
     */
    private $filtersDirs = array();

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    private $finderProto;

    /**
     * @param DiContainer $di
     * @param FinderFactory $finderFactory
     * @param string $filtersDir
     */
    public function __construct(DiContainer $di, FinderFactory $finderFactory, string $filtersDir = __DIR__ . "/filters")
    {
        $this->di = $di;
        $this->addFiltersDir($filtersDir);

        $this->finderProto = $finderFactory->create();
        $this->finderProto->files()
            ->name("*.php")
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();
    }

    /**
     * @param string $filtersDir
     */
    public function addFiltersDir(string $filtersDir)
    {
        $this->filtersDirs[] = $filtersDir;
    }

    /**
     * @return FilterManager
     */
    public function init() : FilterManager
    {
        $filterManager = $this->di->newInstance(FilterManager::class);
        $this->initFilters($filterManager);

        return $filterManager;
    }

    /**
     * @param FilterManager $filterManager
     */
    private function initFilters(FilterManager $filterManager)
    {
        foreach ($this->filtersDirs as $filtersDir) {
            $globber = clone $this->finderProto;
            $globber->in($filtersDir);

            foreach ($globber as $filterFile) {
                $filterFilename = $filterFile->getRelativePathname();
                $filterName = pathinfo($filterFilename, PATHINFO_FILENAME);
                $filter = MattyGBBStaticFilterManagerInitRequire($this->di, $filtersDir . DIRECTORY_SEPARATOR . $filterFilename);
                $filterManager->set($filterName, $filter);
            }
        }
    }
}
