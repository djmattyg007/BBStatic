<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util;

use Aura\Di\Container as DiContainer;

final class ConfigFactory
{
    /**
     * @var DiContainer
     */
    private $diContainer;

    /**
     * @param DiContainer $diContainer
     */
    public function __construct(DiContainer $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * @param array $params
     */
    public function create(array $params) : Config
    {
        return $this->diContainer->newInstance(Config::class, $params);
    }
}
