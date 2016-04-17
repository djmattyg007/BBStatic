<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli;

use Symfony\Component\Console\Application as SymfonyApplication;

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
        $this->commandsDir = realpath($commandsDir);
    }

    /**
     * @param SymfonyApplication $application
     */
    public function addCommands(SymfonyApplication $application)
    {
        foreach (glob($this->commandsDir . "/*Command.php") as $commandFile) {
            $classname = pathinfo($commandFile, PATHINFO_FILENAME);
            $fqcn = $this->commandNamespace . "\\" . $classname;
            $application->add(new $fqcn());
        }
    }
}
