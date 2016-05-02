<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use MattyG\BBStatic\BBStatic;
use MattyG\BBStatic\Util\Config;

class DiConfig
{
    /**
     * @return Container
     */
    public function createContainer() : Container
    {
        $rootNs = "MattyG\\BBStatic\\";
        $di = (new ContainerBuilder())->newInstance(true);

        // Necessary evil, we need to be able to resolve objects based on runtime configuration
        $di->types["Aura\\Di\\Container"] = $di;

        $di->params[$rootNs . "Util\\Config"]["filename"] = BBStatic::CONFIG_FILENAME;
        $di->setters[$rootNs . "Util\\NeedsConfigTrait"]["setConfig"] = $di->lazyGet("config");
        $di->set("config", $di->lazyNew($rootNs . "Util\\Config"));

        $di->types["Nbbc\\BBCode"] = $di->lazy(array($di->lazyNew($rootNs . "BBCode\\Init"), "init"));
        $di->setters[$rootNs . "NeedsFileBuilderTrait"]["setFileBuilder"] = $di->lazyGet("file_builder");
        $di->set("file_builder", $di->lazyNew($rootNs . "FileBuilder"));

        $di->params[$rootNs . "Signing\\Adapter\\GnuPG"]["options"] = $di->lazyGetCall("config", "getValue", "signing/gnupg", array());
        $di->setters[$rootNs . "Signing\\NeedsSignerTrait"]["setSigner"] = $di->lazyGet("signer");
        $di->set("signer", $di->lazyNew($rootNs . "Signing\\SigningManager"));

        return $di;
    }
}
