<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util;

/**
 * Provides the central BBStatic configuration.
 */
trait NeedsConfigTrait
{
    /**
     * @var Config
     */
    protected $config = null;

    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }
}
