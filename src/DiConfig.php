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

        $di->set("config", $di->lazyNew($rootNs . "Util\\Config"));
        $di->params[$rootNs . "Util\\Config"]["filename"] = BBStatic::CONFIG_FILENAME;

        $di->types["Nbbc\\BBCode"] = $di->lazy(array($di->lazyNew($rootNs . "BBCode\\Init"), "init"));
        $di->set("file_builder", $di->lazyNew($rootNs . "FileBuilder"));

        $di->setters[$rootNs . "Util\\NeedsConfigTrait"]["setConfig"] = $di->lazyGet("config");

        $di->setters[$rootNs . "NeedsFileBuilderTrait"]["setFileBuilder"] = $di->lazyGet("file_builder");

        $di->types[$rootNs . "Signing\\Adapter\\SigningAdapterInterface"] = $di->lazyGet("signer");
        $di->params[$rootNs . "Signing\\Adapter\\GnuPG"]["options"] = $di->lazyGetCall("config", "getValue", "signing/gnupg", array());
        $di->set("signer", $di->lazy(function() use ($di, $rootNs) {
            $config = $di->get("config");
            $enabled = $config->getValue("signing/enabled", false);
            if ($enabled === false) {
                return $di->newInstance($rootNs . "Signing\\Adapter\\NullAdapter");
            }
            $adapter = $config->getValue("signing/adapter", null);
            if ($adapter === null) {
                return $di->newInstance($rootNs . "Signing\\Adapter\\NullAdapter");
            } elseif ($adapter === "gnupg") {
                return $di->newInstance($rootNs . "Signing\\Adapter\\GnuPG");
            } else {
                return $di->newInstance($adapter);
            }
        }));

        return $di;
    }
}
