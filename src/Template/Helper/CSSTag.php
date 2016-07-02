<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template\Helper;

use MattyG\BBStatic\Template\SafeString;

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
     * @return SafeString
     */
    public function __invoke(string $cssFilePath) : SafeString
    {
        $cssFileUrl = call_user_func($this->assetUrlGenerator, "css/$cssFilePath");
        return new SafeString('<link rel="stylesheet" type="text/css" href="' . $cssFileUrl . '" />');
    }
}
