<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

trait NeedsPageRendererTrait
{
    /**
     * @var Renderer
     */
    protected $pageRenderer = null;

    /**
     * @param Renderer $pageRenderer
     */
    public function setPageRenderer(Renderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }
}
