<?php
declare(strict_types=1);

namespace MattyG\BBStatic\BBCode;

use Thunder\Shortcode\HandlerContainer\HandlerContainer;
use Thunder\Shortcode\Processor\Processor;
use Thunder\Shortcode\Parser\RegularParser;

/**
 * @param string $filename
 * @return callable
 */
function MattyGBBStaticInitHandler(string $filename): callable
{
    return require($filename);
}

class Init
{
    /**
     * @var string[]
     */
    protected $handlerDirs = array();

    /**
     * @param string $handlersDir
     */
    public function __construct(string $handlersDir = __DIR__ . "/handlers")
    {
        $this->addHandlersDir($handlersDir);
    }

    /**
     * @param string $handlersDir
     */
    public function addHandlersDir(string $handlersDir)
    {
        $this->handlersDirs[] = realpath($handlersDir);
    }

    public function init(): Processor
    {
        $handlerContainer = new HandlerContainer();
        $this->initHandlers($handlerContainer);
        return new Processor(new RegularParser(), $handlerContainer);
    }

    /**
     * @param HandlerContainer $handlerContainer
     */
    protected function initHandlers(HandlerContainer $handlerContainer)
    {
        foreach ($this->handlersDirs as $handlersDir) {
            foreach (glob($handlersDir . "/*.php") as $handlerFile) {
                $handlerName = pathinfo($handlerFile, PATHINFO_FILENAME);
                $handlerContainer->add($handlerName, MattyGBBStaticInitHandler($handlerFile));
            }
        }
    }
}
