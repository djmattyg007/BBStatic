<?php
declare(strict_types=1);

/** @var $config \MattyG\BBStatic\CLI\Config */
/** @var $di \Aura\Di\Container */

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

$config->beginCommand("watch")
    ->setDescription("Watch for changes and re-build automatically.")
    ->setHandler($di->lazyNew("MattyG\\BBStatic\\CLI\\Command\\WatchHandler"))
    ->addOption("sign", null, Option::NO_VALUE, "Create OpenPGP signatures (overrides configuration)")
    ->addOption("no-sign", null, Option::NO_VALUE, "Do not create OpenPGP signatures (overrides configuration)")
    ->end();
