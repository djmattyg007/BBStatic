<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Asset;

use Assetic\Asset\AssetInterface;
use Assetic\Factory\AssetFactory;
use InvalidArgumentException;
use MattyG\BBStatic\Asset\Assetic\AssetWriter;
use Symfony\Component\Asset\UrlPackage;

class AssetManager
{
    /**
     * @var AssetFactory
     */
    private $assetFactory;

    /**
     * @var \Assetic\AssetManager
     */
    private $asseticAssetManager;

    /**
     * @var AssetWriter
     */
    private $assetWriter;

    /**
     * @var array
     */
    private $assetFileConfig;

    /**
     * @var string
     */
    protected $baseAssetUrl;

    /**
     * @param AssetFactory $assetFactory
     * @param AssetWriter $assetWriter
     * @param array $assetFileConfig
     * @param string $baseAssetUrl
     */
    public function __construct(AssetFactory $assetFactory, AssetWriter $assetWriter, array $assetFileConfig, string $baseAssetUrl)
    {
        $this->assetFactory = $assetFactory;
        $this->asseticAssetManager = $assetFactory->getAssetManager();
        $this->assetWriter = $assetWriter;
        $this->assetFileConfig = $assetFileConfig;
        $this->baseAssetUrl = $baseAssetUrl;
    }

    public function loadAssets()
    {
        $remainingAssets = array_diff_key($this->assetFileConfig, array_flip($this->asseticAssetManager->getNames()));
        foreach ($remainingAssets as $name => $config) {
            $options = array("name" => $name);
            if (isset($config["output_pattern"])) {
                $options["output"] = $config["output_pattern"];
            }
            $asset = $this->assetFactory->createAsset($config["input_pattern"], $config["filters"], $options);
            $this->asseticAssetManager->set($name, $asset);
        }
    }

    /**
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function loadAsset(string $name)
    {
        if (isset($this->assetFileConfig[$name]) === false) {
            throw new InvalidArgumentException();
        }
        if (in_array($name, $this->asseticAssetManager->getNames(), true) === true) {
            // Already loaded
            return;
        }
        $config = $this->assetFileConfig[$name];

        $factoryOptions = array("name" => $name);
        if (isset($config["output_pattern"])) {
            $options["output"] = $config["output_pattern"];
        }
        $asset = $this->assetFactory->createAsset($config["input_pattern"], $config["filters"] ?? array(), $options);
        $this->asseticAssetManager->set($name, $asset);
    }

    /**
     * @param string $name
     * @return AssetInterface
     * @throws InvalidArgumentException
     */
    public function getAsset(string $name) : AssetInterface
    {
        $this->loadAsset($name);
        return $this->asseticAssetManager->get($name);
    }

    /**
     * @param bool $shouldSignOutput
     * @param callable|null $progressUpdate
     */
    public function writeAssets(bool $shouldSignOutput, callable $progressUpdate = null)
    {
        $this->loadAssets();

        $this->assetWriter->writeManagerAssets($this->asseticAssetManager, $shouldSignOutput, $progressUpdate);
    }

    /**
     * @param string $name
     * @return string
     * @throws InvalidArgumentException
     */
    public function getAssetUrl(string $name) : string
    {
        $asset = $this->getAsset($name);
        return $this->baseAssetUrl . ltrim($asset->getTargetPath(), "/");
    }
}
