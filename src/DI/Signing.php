<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class Signing implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Signing\\";

    public function define(Container $di)
    {
        $di->types[self::ROOT_NS . "SigningAdapterInterface"] = $di->lazyGet("signer");
        $di->params[self::ROOT_NS . "GnuPGAdapter"]["options"] = $di->lazyGetCall("config", "getValue", "signing/gnupg", array());
        $di->setters[self::ROOT_NS . "NeedsSigningAdapterInterfaceTrait"]["setSigningAdapter"] = $di->lazyGet("signer");
        $di->set("signer", $di->lazyNew(self::ROOT_NS . "SigningAdapterInterfaceSharedProxy"));
    }

    public function modify(Container $di)
    {
    }
}
