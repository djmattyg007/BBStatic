<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template\Helper;

class Asset
{
    /**
     * @var string
     */
    private $baseAssetUrl;

    /**
     * @param string $baseUrl
     * @param string $assetUrlPath
     */
    public function __construct(string $baseUrl, string $assetUrlPath)
    {
        $this->baseAssetUrl = $baseUrl . $assetUrlPath . "/";
    }

    /**
     * @param string $assetPath
     */
    public function __invoke(string $assetPath) : string
    {
        return $this->baseAssetUrl . ltrim($assetPath, "/");
    }
}
