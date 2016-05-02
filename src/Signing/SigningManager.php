<?php

namespace MattyG\BBStatic\Signing;

use Aura\Di\Container as DiContainer;
use MattyG\BBStatic\Signing\Adapter\SigningAdapterInterface;
use MattyG\BBStatic\Util\Config;

class SigningManager
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var DiContainer
     */
    private $diContainer;

    /**
     * @var SigningAdapterInterface
     */
    private $signer = null;

    /**
     * We need access to the DI Container so that we can resolve the signing
     * adapter based on whether or not runtime configuration allows such a thing.
     * TODO: Look at splitting this up into a proxy.
     *
     * @param DiContainer $diContainer
     * @param Config $config
     */
    public function __construct(DiContainer $diContainer, Config $config)
    {
        $this->diContainer = $diContainer;
        $this->config = $config;
    }

    private function resolveSigner()
    {
        if ($this->signer !== null) {
            return;
        }
        $adapter = $this->config->getValue("signing/adapter", null);
        if ($adapter === null) {
            throw new \InvalidArgumentException("No signing adapter configured.");
        }
        if ($adapter === "gnupg") {
            $this->signer = $this->diContainer->newInstance("MattyG\\BBStatic\\Signing\\Adapter\\GnuPG");
        } elseif (class_exists($adapter)) {
            $this->signer = $this->diContainer->newInstance($adapter);
        } else {
            throw new \InvalidArgumentException(sprintf("Unrecognised signing adapter '%s' configured.", $adapter));
        }
        if (!$this->signer instanceof SigningAdapterInterface) {
            throw new \RuntimeException(sprintf("Invalid signing adapter '%s' configured.", $adapter));
        }
    }

    /**
     * @param string $filename
     */
    public function sign(string $filename)
    {
        $this->resolveSigner();
        $type = $this->config->getValue("signing/sigtype");
        if ($type === "detached") {
            $this->signer->signDetached($filename);
        } elseif ($type === "clearsign") {
            $this->signer->signClear($filename);
        } else {
            throw \InvalidArgumentException(sprintf("Unrecognised signing type '%s'.", $type));
        }
    }
}
