<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template\Helper;

class URL
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, "/") . "/";
    }

    /**
     * @param string $urlPath
     */
    public function __invoke(string $urlPath) : string
    {
        $pathinfo = pathinfo($urlPath, PATHINFO_EXTENSION);
        $url = $this->baseUrl . trim($urlPath, "/");
        if (!$pathinfo) {
            $url .= "/";
        }
        return $url;
    }
}
