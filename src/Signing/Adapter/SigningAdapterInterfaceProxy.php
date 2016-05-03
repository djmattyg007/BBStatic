<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Signing\Adapter;

use Aura\Di\Container as DiContainer;
use InvalidArgumentException; // TODO: Change this to a more appropriate exception type

class SigningAdapterInterfaceProxy implements SigningAdapterInterface
{
    /**
     * @var DiContainer
     */
    private $diContainer;

    /**
     * @var SigningAdapterInterface
     */
    private $subject;

    /**
     * @param DiContainer $diContainer
     */
    public function __construct(DiContainer $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * @return SigningAdapterInterface
     */
    private function getSubject() : SigningAdapterInterface
    {
        if ($this->subject === null) {
            $config = $this->diContainer->get("config");
            $adapter = $config->getValue("signing/adapter", null);
            if ($adapter === null) {
                throw new InvalidArgumentException("No signing adapter configured.");
            }
            if ($adapter === "gnupg") {
                $this->subject = $this->diContainer->newInstance("MattyG\\BBStatic\\Signing\\Adapter\\GnuPG");
            } elseif (class_exists($adapter)) {
                $this->subject = $this->diContainer->newInstance($adapter);
            } else {
                throw new InvalidArgumentException(sprintf("Unrecognised signing adapter '%s' configured.", $adapter));
            }
            if (!$this->subject instanceof SigningAdapterInterface) {
                throw new \RuntimeException(sprintf("Invalid signing adapter '%s' configured.", $adapter));
            }
        }
        return $this->subject;
    }

    /**
     * @param string $filename
     * @throws \Exception If a signature cannot be generated.
     */
    public function signDetached(string $filename)
    {
        $this->getSubject()->signDetached($filename);
    }

    /**
     * @return string
     */
    public function getDetachedSignatureFileGlobPattern() : string
    {
        return $this->getSubject()->getDetachedSignatureFileGlobPattern();
    }
}

