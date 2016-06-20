<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template\Helper;

use DateTime;
use DateTimeZone;

class Date
{
    /**
     * @var DateTimeZone
     */
    private $timezone;

    /**
     * @var array
     */
    private $dateFormats = array(
        "iso8601_full" => "Y-m-d H:i:s",
        "iso8601_date" => "Y-m-d",
    );

    /**
     * @param string $timezone
     * @param string[] $dateFormats
     */
    public function __construct(string $timezone, array $dateFormats)
    {
        $this->timezone = new DateTimeZone($timezone);
        $this->dateFormats = array_merge($this->dateFormats, $dateFormats);
    }

    /**
     * @param int $date
     * @param string $dateFormat
     */
    public function __invoke(int $date, string $dateFormat) : string
    {
        // If you pass in a timezone to the constructor, it assumes that that timezone is for the input date.
        // In order to format the date in the correct timezone, we need to set the timezone after construction.
        $date = new DateTime("@" . $date);
        $date->setTimezone($this->timezone);
        return $date->format($this->dateFormats[$dateFormat] ?? $dateFormat);
    }
}
