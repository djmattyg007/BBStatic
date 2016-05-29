<?php
declare(strict_types=1);

namespace MattyG\BBStatic\BBCode;

trait NeedsBBCodeRendererTrait
{
    /**
     * @var Renderer
     */
    protected $bbcodeRenderer = null;

    /**
     * @param Renderer $renderer
     */
    public function setBBCodeRenderer(Renderer $bbcodeRenderer)
    {
        $this->bbcodeRenderer = $bbcodeRenderer;
    }
}
