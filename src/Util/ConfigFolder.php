<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util;

use RuntimeException;

final class ConfigFolder
{
    /**
     * @var string
     */
    private $folder;

    /**
     * @var array
     */
    private $config = array();

    /**
     * @param string $folder
     */
    public function __construct(string $folder)
    {
        if (is_dir($folder) === false || is_readable($folder) === false) {
            throw new RuntimeException(sprintf("Cannot read configuration from folder '%s'.", $folder));
        }
        $this->folder = $folder;
    }

    /**
     * @param string $key
     */
    private function loadConfig(string $key)
    {
        if (isset($this->config[$key])) {
            return;
        }

        $filename = $this->folder . DIRECTORY_SEPARATOR . $key . ".json";
        if (is_file($filename) === false || is_readable($filename) === false) {
            throw new RuntimeException(sprintf("Cannot read configuration file '%s.json'.", $key));
        }
        $file = file_get_contents($filename);
        $config = json_decode($file, true);
        if (!$config) {
            throw new RuntimeException(sprintf("Invalid configuration in '%s.json'.", $key));
        }

        $this->config[$key] = $config;
    }

    /**
     * @param string $settingName
     * @param mixed $default
     * @return mixed|null
     */
    public function getValue(string $settingName, $default = null)
    {
        $settingPath = explode("/", $settingName);
        $this->loadConfig($settingPath[0]);

        if (count($settingPath) === 1) {
            return $this->config[$settingPath[0]];
        }

        $config = $this->config;
        foreach ($settingPath as $segment) {
            if ($segment === "") {
                return $default;
            }
            if (is_array($config)) {
                if (isset($config[$segment])) {
                    $config = $config[$segment];
                } else {
                    return $default;
                }
            } else {
                return $default;
            }
        }
        return $config;
    }
}
