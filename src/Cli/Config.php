<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli;

use Aura\Di\Container;
use MattyG\BBStatic\BBStatic;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Webmozart\Console\Config\DefaultApplicationConfig;

/**
 * @param string $filename
 * @param Init $config
 * @param Container $di
 */
function MattyGBBStaticRequireCommandConfig(string $filename, Config $config, Container $di)
{
    require($filename);
}

class Config extends DefaultApplicationConfig
{
    /**
     * @var Container
     */
    protected $di;

    /**
     * @var string[]
     */
    protected $commandsDirs = array(
        __DIR__ . "/commands",
    );

    /**
     * @param Container $di
     */
    public function setDiContainer(Container $di)
    {
        $this->di = $di;
    }

    /**
     * @param string $commandsDir
     * @return Init
     */
    public function addCommandsDir(string $commandsDir) : Init
    {
        $this->commandsDir[] = $commandsDir;
        return $this;
    }

    protected function configure()
    {
        parent::configure();

        $this->setName("bbstatic")
            ->setDisplayName("BBStatic")
            ->setVersion(BBStatic::VERSION);
    }

    public function addCommands()
    {
        $globberProto = new SymfonyFinder();
        $globberProto->files()
            ->name("*.php")
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();

        foreach ($this->commandsDirs as $commandsDir) {
            $globber = clone $globberProto;
            $globber->in($commandsDir);
            foreach ($globber as $file) {
                MattyGBBStaticRequireCommandConfig($file->getPathname(), $this, $this->di);
            }
        }
    }
}
