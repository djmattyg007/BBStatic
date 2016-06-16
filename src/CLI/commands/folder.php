<?php
declare(strict_types=1);

/** @var $config \MattyG\BBStatic\CLI\Config */
/** @var $di \Aura\Di\Container */

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

$config
    ->beginCommand("folder")
        ->setDescription("Convert a folder full of BBCode files into HTML.")
        ->setHandler($di->lazyNew("MattyG\\BBStatic\\CLI\\Command\\FolderHandler"))

        ->beginSubCommand("build")
            ->setHandlerMethod("handleBuild")
            ->addArgument("folder-path", Argument::REQUIRED, "Folder full of files to convert")
            ->addOption("sign", null, Option::NO_VALUE, "Create OpenPGP signatures (overrides configuration)")
            ->addOption("no-sign", null, Option::NO_VALUE, "Do not create OpenPGP signatures (overrides configuration)")
        ->end()

        ->beginSubCommand("clean")
            ->setHandlerMethod("handleClean")
            ->addArgument("folder-path", Argument::REQUIRED, "Folder full of generated files to clean up")
        ->end()

    ->end();
