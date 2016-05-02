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

        $di->params["Mustache_Cache_FilesystemCache"]["baseDir"] = $di->lazyGetCall("config", "getValue", "directories/cache");
        $di->setters["Mustache_Engine"]["setCache"] = $di->lazyGet("template_cache");
        $di->setters["Mustache_Engine"]["setLoader"] = $di->lazyGet("template_loader");
        $di->setters["Mustache_Engine"]["setPartialsLoader"] = $di->lazyGet("template_partials_loader");
        $di->set("template_cache", $di->lazyNew("Mustache_Cache_FilesystemCache"));
        $di->set("template_loader", $di->lazyNew("Mustache_Loader_FilesystemLoader", array(
            "baseDir" => $di->lazyValue("theme_directory"),
        )));
        $di->set("template_partials_loader", $di->lazyNew("Mustache_Loader_FilesystemLoader", array(
            "baseDir" => $di->lazyValue("theme_partials_directory"),
        )));
        $di->set("template_engine", $di->lazyNew("Mustache_Engine"));
        $di->values["theme_directory"] = $di->lazyGetCall("config", "getValue", "directories/theme");
        $di->values["theme_partials_directory"] = $di->lazy(function() use ($di) {
            return $di->get("config")->getValue("directories/theme") . DIRECTORY_SEPARATOR . "partials";
        });

        return $di;
    }
}
