<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template\Helper;

class CSSTag
{
    /**
     * @param Asset $assetUrlGenerator
     */
    public function __construct(Asset $assetUrlGenerator)
    {
        $this->assetUrlGenerator = $assetUrlGenerator;
    }

    /**
     * @param string $cssFilePath
     */
    public function __invoke(string $cssFilePath) : string
    {
        $cssFileUrl = call_user_func($this->assetUrlGenerator, "css/$cssFilePath");
        return '<link rel="stylesheet" type="text/css" href="' . $cssFileUrl . '" />';
    }
}
