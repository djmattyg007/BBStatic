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
    private $postsUrlPath;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->baseUrl = rtrim($config->getValue("site/base_url"), "/") . "/";
        $this->pagesUrlPath = ltrim($config->getValue("pages/url_path"), "/");
        $this->postsUrlPath = ltrim($config->getValue("posts/url_path"), "/");
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
    public function getPostsUrlPath() : string
    {
        return $this->postsUrlPath;
    }
}
