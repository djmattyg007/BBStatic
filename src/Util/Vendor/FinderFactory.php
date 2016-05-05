<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util\Vendor;

use Symfony\Component\Finder\Finder;

final class FinderFactory
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
    public function create(array $params) : Finder
    {
        return $this->diContainer->newInstance(Finder::class, $params);
    }
}
