<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template\Helper;

use MattyG\BBStatic\Asset\AssetManager;

class Asset
{
    /**
     * @var AssetManager
     */
    private $assetManager;

    /**
     * @param AssetManager $assetManager
     */
    public function __construct(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
    }

    /**
     * @param string $assetName
     * @return string
     */
    public function __invoke(string $assetName) : string
    {
        return $this->assetManager->getAssetUrl($assetName);
        return $this->baseAssetUrl . ltrim($assetPath, "/");
    }
}
