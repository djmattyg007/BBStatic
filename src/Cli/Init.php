<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli;

use Symfony\Component\Console\Application as SymfonyCliApplication;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class Init
{
    /**
     * @var string
     */
    protected $commandNamespace;

    /**
     * @var string
     */
    protected $commandsDir;

    /**
     * @param string $commandsDir
     */
    public function __construct(string $commandNamespace = __NAMESPACE__ . "\\Command", string $commandsDir = __DIR__ . "/Command")
    {
        $this->commandNamespace = $commandNamespace;
        $this->commandsDir = $commandsDir;
    }

    /**
     * @param SymfonyCliApplication
     */
    public function addCommands(SymfonyCliApplication $application)
    {
        $globber = new SymfonyFinder();
        $globber->files()
            ->name("*Command.php")
            ->in($this->commandsDir)
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();
        $extLen = strlen(".php");
        foreach ($globber as $file) {
            $classname = str_replace("/", "\\", substr($file->getRelativePathname(), 0, -$extLen));
            $fqcn = $this->commandNamespace . "\\" . $classname;
            $application->add(new $fqcn);
        }
    }
}
