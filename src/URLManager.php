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
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->baseUrl = rtrim($config->getValue("site/base_url"), "/") . "/";
        $this->pagesUrlPath = ltrim($config->getValue("pages/url_path"), "/");
        $this->blogUrlPath = ltrim($config->getValue("blog/url_path"), "/");
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
    public function getBlogUrlPath() : string
    {
        return $this->blogUrlPath;
    }
}
