<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Signing;

use Symfony\Component\Process\Exception as SymfonyException;
use Symfony\Component\Process\ProcessBuilderFactory;

class GnuPGAdapter implements SigningAdapterInterface
{
    const SIG_FILE_EXT_DETACHED = "sig";

    /**
     * @var ProcessBuilderFactory
     */
    protected $processBuilderFactory;

    /**
     * @var string
     */
    protected $gpgPath;

    /**
     * @var string
     */
    protected $localUser = null;

    /**
     * @param ProcessBuilderFactory $processBuilderFactory
     * @param array $options
     * @throws \RuntimeException
     */
    public function __construct(ProcessBuilderFactory $processBuilderFactory, array $options = array())
    {
        if (isset($options["gpg_path"]) && is_executable($options["gpg_path"])) {
            $this->gpgPath = $options["gpg_path"];
        } else {
            $this->gpgPath = $this->findPathToGpg();
        }
        if (!$this->gpgPath) {
            throw \RuntimeException("Cannot locate GnuPG binary.");
        }
        if (!empty($options["local_user"])) {
            $this->localUser = $options["local_user"];
        }
        $this->processBuilderFactory = $processBuilderFactory;
    }

    /**
     * @return string
     */
    private function findPathToGpg() : string
    {
        $paths = explode(PATH_SEPARATOR, getenv("PATH"));
        $gpgBinaries = array("gpg2", "gpg");
        foreach ($paths as $path) {
            foreach ($gpgBinaries as $gpgBinary) {
                if (is_executable($path . DIRECTORY_SEPARATOR . $gpgBinary) === true) {
                    return $path . DIRECTORY_SEPARATOR . $gpgBinary;
                }
            }
        }
        return null;
    }

    /**
     * @param string $filename
     * @throws \Exception If a signature cannot be generated.
     */
    public function sign(string $filename)
    {
        $sigFilename = $this->makeSigFilename($filename, self::SIG_FILE_EXT_DETACHED);
        if (file_exists($sigFilename) === true) {
            unlink($sigFilename);
        }

        $processBuilder = $this->processBuilderFactory->create(array("arguments" => array(
            $this->gpgPath,
            "--output",
            $sigFilename,
            "--detach-sign",
        )));
        if (is_string($this->localUser)) {
            $processBuilder->add("--local-user")->add($this->localUser);
        }
        $processBuilder->add($filename);

        $process = $processBuilder->getProcess();
        try {
            $process->mustRun();
        } catch (SymfonyException\RuntimeException $e) {
            throw new SigningException("Failed to run GnuPG.", 1, $e);
        } catch (SymfonyException\ProcessFailedException $e) {
            throw new SigningException(sprintf("GnuPG failed to create a detached signature for %s.", $filename), $process->getExitCode(), $e);
        }
    }

    /**
     * @return string
     */
    public function getSignatureFileGlobPattern() : string
    {
        return "*." . self::SIG_FILE_EXT_DETACHED;
    }

    /**
     * @param string $filename
     * @param string $sigExtension
     * @return string
     */
    private function makeSigFilename(string $filename, string $sigExtension) : string
    {
        $pathInfo = pathinfo($filename);
        return $pathInfo["dirname"] . DIRECTORY_SEPARATOR . $pathInfo["filename"] . "." . $sigExtension;
    }
}
