<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util;

trait NeedsConfigFactoryTrait
{
    /**
     * @var ConfigFactory
     */
    protected $configFactory = null;

    /**
     * @param ConfigFactory $configFactory
     */
    public function setConfigFactory(ConfigFactory $config)
    {
        $this->configFactory = $configFactory;
    }
}
