<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Asset\Assetic;

use Assetic\AssetManager;
use Assetic\Factory\AssetFactory;
use Assetic\Factory\Worker\WorkerInterface;
use Aura\Di\Container as DiContainer;
use Symfony\Component\Finder\FinderFactory;

/**
 * @param DiContainer $di
 * @param string $filename
 * @return WorkerInterface
 */
function MattyGBBStaticAssetFactoryInitRequire(DiContainer $di, string $filename) : WorkerInterface
{
    return require($filename);
}

final class AssetFactoryInit
{
    /**
     * @var DiContainer
     */
    private $di;

    /**
     * @var string[]
     */
    private $workersDirs = array();

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    private $finderProto;

    /**
     * @param DiContainer $di
     * @param FinderFactory $finderFactory
     * @param string $workersDir
     */
    public function __construct(DiContainer $di, FinderFactory $finderFactory, string $workersDir = __DIR__ . "/workers")
    {
        $this->di = $di;
        $this->addWorkersDir($workersDir);

        $this->finderProto = $finderFactory->create();
        $this->finderProto->files()
            ->name("*.php")
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();
    }

    /**
     * @param string $workersDir
     */
    public function addWorkersDir(string $workersDir)
    {
        $this->workersDirs[] = $workersDir;
    }

    /**
     * @param string $outputFolder
     * @return AssetFactory
     */
    public function init(string $outputFolder, AssetManager $assetManager) : AssetFactory
    {
        $assetFactory = $this->di->newInstance(
            AssetFactory::class,
            array("root" => $outputFolder),
            array("setAssetManager" => $assetManager)
        );
        $this->initWorkers($assetFactory);

        return $assetFactory;
    }

    /**
     * TODO: No need to re-instantiate all workers for all asset factories
     * @param AssetFactory $assetFactory
     */
    private function initWorkers(AssetFactory $assetFactory)
    {
        foreach ($this->workersDirs as $workersDir) {
            $globber = clone $this->finderProto;
            $globber->in($workersDir);

            foreach ($globber as $workerFile) {
                $workerFilename = $workerFile->getRelativePathname();
                $worker = MattyGBBStaticAssetFactoryInitRequire($this->di, $workersDir . DIRECTORY_SEPARATOR . $workerFilename);
                $assetFactory->addWorker($worker);
            }
        }
    }
}
