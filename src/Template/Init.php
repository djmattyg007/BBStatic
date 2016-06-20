<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template;

use Aura\Di\Container as DiContainer;
use Symfony\Component\Finder\FinderFactory;

/**
 * @param DiContainer $di
 * @param string $filename
 * @return callable
 */
function MattyGBBStaticInitRequire(DiContainer $di, string $filename)
{
    return require($filename);
}

final class Init
{
    /**
     * @var DiContainer
     */
    private $di;

    /**
     * @var string
     */
    private $partialsDir;

    /**
     * @var string[]
     */
    private $helpersDirs = array();

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    private $finderProto;

    /**
     * @param DiContainer $di
     * @param FinderFactory $finderFactory
     * @param string $partialsDir
     * @param string $helpersDir
     */
    public function __construct(DiContainer $di, FinderFactory $finderFactory, string $partialsDir, string $helpersDir = __DIR__ . "/helpers")
    {
        $this->di = $di;
        $this->setPartialsDir($partialsDir);
        $this->addHelpersDir($helpersDir);

        $this->finderProto = $finderFactory->create();
        $this->finderProto->files()
            ->name("*.php")
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();
    }

    /**
     * @param string $partialsDir
     */
    public function setPartialsDir(string $partialsDir)
    {
        $this->partialsDir = $partialsDir;
    }

    /**
     * @param string $helpersDir
     */
    public function addHelpersDir(string $helpersDir)
    {
        $this->helpersDirs[] = $helpersDir;
    }

    /**
     * @return TemplateEngineInterface
     */
    public function init() : TemplateEngineInterface
    {
        $engine = $this->di->newInstance(EdenHandlebarsAdapter::class);
        $this->initPartials($engine);
        $this->initHelpers($engine);

        return $engine;
    }

    /**
     * @param TemplateEngineInterface $engine
     */
    private function initPartials(TemplateEngineInterface $engine)
    {
        $globber = clone $this->finderProto;
        $globber->in($this->partialsDir);

        foreach ($globber as $partialFile) {
            $partialFilename = $partialFile->getRelativePathname();
            $partialName = pathinfo($partialFilename, PATHINFO_FILENAME);
            $partialString = file_get_contents($this->partialsDir . DIRECTORY_SEPARATOR . $partialFilename);
            $engine->registerPartial($partialName, $partialString);
        }
    }

    /**
     * @param TemplateEngineInterface $engine
     */
    private function initHelpers(TemplateEngineInterface $engine)
    {
        foreach ($this->helpersDirs as $helperDir) {
            $globber = clone $this->finderProto;
            $globber->in($helperDir);

            foreach ($globber as $helperFile) {
                $helperFilename = $helperFile->getRelativePathname();
                $helperName = pathinfo($helperFilename, PATHINFO_FILENAME);
                $helper = MattyGBBStaticInitRequire($this->di, $helperDir . DIRECTORY_SEPARATOR . $helperFilename);
                $engine->registerHelper($helperName, $helper);
            }
        }
    }
}
