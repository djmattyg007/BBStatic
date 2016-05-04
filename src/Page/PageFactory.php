<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use Aura\Di\Container as DiContainer;

final class PageFactory
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
    public function create(array $params) : Page
    {
        return $this->diContainer->newInstance(Page::class, $params);
    }
}
