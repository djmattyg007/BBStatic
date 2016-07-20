<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use MattyG\BBStatic\Util\Config;

final class URLManager
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $pagesUrlPath;

    /**
     * @var string
     */
    private $blogUrlPath;

    /**
     * @var string
     */
    private $assetUrlPath;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->baseUrl = rtrim($config->getValue("site/base_url"), "/") . "/";
        $this->pagesUrlPath = trim($config->getValue("pages/url_path"), "/");
        $this->blogUrlPath = trim($config->getValue("blog/url_path"), "/");
        $this->assetUrlPath = trim($config->getValue("assets/url_path"), "/");
    }

    /**
     * @return string
     */
    public function getBaseUrl() : string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getPagesUrlPath() : string
    {
        return $this->pagesUrlPath;
    }

    /**
     * @return string
     */
    public function getPagesUrl() : string
    {
        return $this->baseUrl . $this->pagesUrlPath . "/";
    }

    /**
     * @return string
     */
    public function getBlogUrlPath() : string
    {
        return $this->blogUrlPath;
    }

    /**
     * @return string
     */
    public function getBlogUrl() : string
    {
        return $this->baseUrl . $this->blogUrlPath . "/";
    }

    /**
     * @return string
     */
    public function getAssetUrlPath() : string
    {
        return $this->assetUrlPath;
    }

    /**
     * @return string
     */
    public function getAssetUrl() : string
    {
        return $this->baseUrl . $this->assetUrlPath . "/";
    }
}
