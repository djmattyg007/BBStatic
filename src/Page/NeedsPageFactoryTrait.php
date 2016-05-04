<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

trait NeedsPageFactoryTrait
{
    /**
     * @var PageFactory
     */
    protected $pageFactory = null;

    /**
     * @param PageFactory $pageFactory
     */
    public function setPageFactory(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }
}
