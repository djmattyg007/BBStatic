<?php
declare(strict_types=1);

/** @var $di \Aura\Di\Container */

return new \MattyG\BBStatic\Util\Vendor\LazyCallable($di->lazyNew("MattyG\\BBStatic\\Template\\Helper\\Date"));
