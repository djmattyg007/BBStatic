<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use MattyG\BBStatic\Util\Config;

class DiConfig
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Container
     */
    public function createContainer() : Container
    {
        $rootNs = "MattyG\\BBStatic\\";
        $di = (new ContainerBuilder())->newInstance(true);

        $di->set("config", $this->config);

        $di->types["Nbbc\\BBCode"] = $di->lazy(array($di->lazyNew($rootNs . "BBCode\\Init"), "init"));
        $di->set("file_builder", $di->lazyNew($rootNs . "FileBuilder"));

        $di->setters[$rootNs . "Util\\NeedsConfigTrait"]["setConfig"] = $di->lazyGet("config");

        $di->setters[$rootNs . "NeedsFileBuilderTrait"]["setFileBuilder"] = $di->lazyGet("file_builder");

        return $di;
    }
}
