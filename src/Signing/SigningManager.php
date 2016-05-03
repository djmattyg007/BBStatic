<?php

namespace MattyG\BBStatic\Signing;

use Aura\Di\Container as DiContainer;
use MattyG\BBStatic\Signing\Adapter\SigningAdapterInterface;
use MattyG\BBStatic\Util\Config;
use InvalidArgumentException; // TODO: Change this to a more appropriate exception type

class SigningManager
{
    /**
     * @var SigningAdapterInterface
     */
    private $signer;

    /**
     * @var Config
     */
    private $config;

    /**
     * We need access to the DI Container so that we can resolve the signing
     * adapter based on whether or not runtime configuration allows such a thing.
     *
     * @param SigningAdapterInterface $signer
     * @param Config $config
     */
    public function __construct(SigningAdapterInterface $signer, Config $config)
    {
        $this->signer = $signer;
        $this->config = $config;
    }

    /**
     * @param string $filename
     * @throws InvalidArgumentException
     */
    public function sign(string $filename)
    {
        $type = $this->config->getValue("signing/sigtype");
        if ($type === "detached") {
            $this->signer->signDetached($filename);
        } elseif ($type === "clearsign") {
            $this->signer->signClear($filename);
        } else {
            throw InvalidArgumentException(sprintf("Unrecognised signing type '%s'.", $type));
        }
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getSignatureFileGlobPattern() : string
    {
        $type = $this->config->getValue("signing/sigtype");
        if ($type === "detached") {
            return $this->signer->getDetachedSignatureFileGlobPattern();
        } elseif ($type === "clearsign") {
            return $this->signer->getClearsignFileGlobPattern();
        } else {
            throw InvalidArgumentException(sprintf("Unrecognised signing type '%s'.", $type));
        }
    }

}
