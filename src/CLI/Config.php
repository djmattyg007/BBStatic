<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI;

use Aura\Di\Container as DiContainer;
use MattyG\BBStatic\BBStatic;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Webmozart\Console\Config\DefaultApplicationConfig;

/**
 * @param string $filename
 * @param Config $config
 * @param DiContainer $di
 */
function MattyGBBStaticRequireCommandConfig(string $filename, Config $config, DiContainer $di)
{
    require($filename);
}

class Config extends DefaultApplicationConfig
{
    /**
     * @var string[]
     */
    protected $commandsDirs = array(
        __DIR__ . "/commands",
    );

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

    /**
     * @param DiContainer $di
     */
    public function addCommands(DiContainer $di)
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
                MattyGBBStaticRequireCommandConfig($file->getPathname(), $this, $di);
            }
        }
    }
}
