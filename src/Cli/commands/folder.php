<?php
declare(strict_types=1);

/** @var $config \MattyG\BBStatic\Cli\Config */
/** @var $di \Aura\Di\Container */

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

$config
    ->beginCommand("folder")
        ->setDescription("Convert a folder full of BBCode files into HTML.")
        ->setHandler($di->lazyNew("MattyG\\BBStatic\\Cli\\Command\\FolderHandler"))

        ->beginSubCommand("build")
            ->setHandlerMethod("handleBuild")
            ->addArgument("folder-path", Argument::REQUIRED, "Folder full of files to convert")
        ->end()

        ->beginSubCommand("clean")
            ->setHandlerMethod("handleClean")
            ->addArgument("folder-path", Argument::REQUIRED, "Folder full of generated files to clean up")
        ->end()

    ->end();
