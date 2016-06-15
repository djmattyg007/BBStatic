<?php
declare(strict_types=1);

/** @var $config \MattyG\BBStatic\Cli\Config */
/** @var $di \Aura\Di\Container */

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

$config->beginCommand("build")
    ->setDescription("Build your static site.")
    ->setHandler($di->lazyNew("MattyG\\BBStatic\\Cli\\Command\\BuildHandler"))
    ->addOption("sign", null, Option::NO_VALUE, "Create OpenPGP signatures (overrides configuration)")
    ->addOption("no-sign", null, Option::NO_VALUE, "Do not create OpenPGP signatures (overrides configuration)")
    ->end();
