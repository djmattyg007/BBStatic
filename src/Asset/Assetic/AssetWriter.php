<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Asset\Assetic;

use Assetic\AssetManager as AsseticAssetManager;
use Assetic\Asset\AssetInterface;
use Assetic\Util\VarUtils;
use InvalidArgumentException;
use MattyG\BBStatic\Signing\NeedsSigningAdapterInterfaceTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

final class AssetWriter
{
    use NeedsFilesystemTrait;
    use NeedsSigningAdapterInterfaceTrait;

    /**
     * @var string
     */
    private $outputDirectory;

    /**
     * @var string[]
     */
    private $values;

    /**
     * @param string $outputDirectory The base directory for assets
     * @param array $values Variable values
     * @throws InvalidArgumentException if a variable value is not a string
     */
    public function __construct(string $outputDirectory, array $values = array())
    {
        foreach ($values as $var => $vals) {
            foreach ($vals as $value) {
                if (!is_string($value)) {
                    throw new InvalidArgumentException(sprintf('All variable values must be strings, but got %1$s for variable "%2$s".', json_encode($value), $var));
                }
            }
        }

        $this->outputDirectory = rtrim($outputDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->values = $values;
    }

    /**
     * @param AsseticAssetManager $asseticAssetManager
     * @param bool $shouldSign
     * @param callable|null $progressUpdate
     */
    public function writeManagerAssets(AsseticAssetManager $asseticAssetManager, bool $shouldSign = true, callable $progressUpdate = null)
    {
        $assetNames = $asseticAssetManager->getNames();
        $totalAssetCount = count($assetNames);
        $counter = 0;
        foreach ($assetNames as $assetName) {
            $counter++;
            $this->writeAsset($asseticAssetManager->get($assetName), $shouldSign);
            if ($progressUpdate !== null) {
                $progressUpdate($counter, $totalAssetCount);
            }
        }
    }

    /**
     * @param AssetInterface $asset
     * @param bool $shouldSign
     */
    public function writeAsset(AssetInterface $asset, bool $shouldSign = true)
    {
        $clonedAsset = clone $asset;
        foreach (VarUtils::getCombinations($asset->getVars(), $this->values) as $combination) {
            $clonedAsset->setValues($combination);

            $outputPath = $this->outputDirectory . VarUtils::resolve(
                $clonedAsset->getTargetPath(),
                $clonedAsset->getVars(),
                $clonedAsset->getValues()
            );
            $this->filesystem->dumpFile($outputPath, $clonedAsset->dump());
            if ($shouldSign === true) {
                $this->signingAdapter->sign($outputPath);
            }
        }
    }
}
