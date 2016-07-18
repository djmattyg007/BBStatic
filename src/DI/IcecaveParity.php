<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class IcecaveParity implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "Icecave\\Parity\\";

    public function define(Container $di)
    {
        $di->params[self::ROOT_NS . "Comparator\\ParityComparator"]["fallbackComparator"] = $di->lazyGet("null_comparator");
        $di->setters[self::ROOT_NS . "Comparator\\NeedsParityComparatorTrait"]["setParityComparator"] = $di->lazyGet("parity_comparator");
        $di->set("parity_comparator", $di->lazyNew(self::ROOT_NS . "Comparator\\ParityComparator"));
        $di->set("null_comparator", $di->lazyNew("MattyG\\BBStatic\\Util\\Vendor\\NullComparator"));
    }

    public function modify(Container $di)
    {
    }
}
