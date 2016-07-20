<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Asset;

use Assetic\Factory\AssetFactory;
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
     * @var bool
     */
    protected $loaded = false;

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
        // TODO: Provide lazy-loading of assets
        if ($this->loaded === true) {
            return;
        }

        foreach ($this->assetFileConfig as $name => $config) {
            $options = array("name" => $name);
            if (isset($config["output_pattern"])) {
                $options["output"] = $config["output_pattern"];
            }
            $asset = $this->assetFactory->createAsset($config["input_pattern"], $config["filters"], $options);
            $this->asseticAssetManager->set($name, $asset);
        }

        $this->loaded = true;
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
     * @throws \InvalidArgumentException
     */
    public function getAssetUrl(string $name) : string
    {
        $this->loadAssets();

        $asset = $this->asseticAssetManager->get($name);
        return $this->baseAssetUrl . ltrim($asset->getTargetPath(), "/");
    }
}
