<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util;

final class Config
{
    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        if (is_readable($filename)) {
            $this->loadConfig($filename);
        } else {
            throw new \RuntimeException(sprintf("Cannot read configuration file '%s'.", $filename));
        }
    }

    /**
     * @param string $filename
     */
    private function loadConfig(string $filename)
    {
        $file = file_get_contents($filename);
        $config = json_decode($file, true);
        if (!$config) {
            throw \RuntimeException(sprintf("Invalid configuration in '%s'.", $filename));
        }

        $this->config = $config;
    }

    /**
     * @param string $settingName
     * @param mixed $default
     * @return mixed|null
     */
    public function getValue(string $settingName, $default = null)
    {
        if (strpos($settingName, "/")) {
            $settingPath = explode("/", $settingName);
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
        if (isset($this->config[$settingName])) {
            return $this->config[$settingName];
        } else {
            return $default;
        }
    }
}
