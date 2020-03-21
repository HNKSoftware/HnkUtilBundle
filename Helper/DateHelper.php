<?php


namespace Hnk\HnkUtilBundle\Helper;


use DateTime;
use DateTimeInterface;

class DateHelper
{
    /**
     * Returns formatted date created from \DateTime instance or any string accepted by strtotime
     *
     * @param string|DateTimeInterface $dateTimeInput
     * @param string $format
     *
     * @return null|string
     */
    public static function getDateAsString($dateTimeInput, string $format = 'Y-m-d'): ?string
    {
        if ($dateTimeInput instanceof DateTime) {
            return $dateTimeInput->format($format);
        }

        $time = strtotime($dateTimeInput);
        if (false !== $time) {
            return date($format, $time);
        }

        return null;
    }

    /**
     * Returns date formatted for Polish standard
     *
     * @param string|DateTime $dateTimeInput
     * @return string|null
     */
    public static function formatDatePL($dateTimeInput): ?string
    {
        return self::getDateAsString($dateTimeInput, "d.m.Y");
    }

    /**
     * Returns date formatted for database standard
     *
     * @param string|DateTime $dateTimeInput
     * @return string|null
     */
    public static function formatDateDB($dateTimeInput): ?string
    {
        return self::getDateAsString($dateTimeInput, "Y-m-d");
    }

    /**
     * Returns timestamp formatted for database standard
     *
     * @param string|DateTime $dateTimeInput
     * @return string|null
     */
    public static function formatTimestampDB($dateTimeInput): ?string
    {
        return self::getDateAsString($dateTimeInput, "Y-m-d H:i:s");
    }

    /**
     * Creates DateTime instance from string or DateTime instance
     *
     * @param string|DateTime $dateTimeInput
     * @param string $format
     * @return DateTime|false
     */
    public static function createDateTime($dateTimeInput, string $format = "Y-m-d")
    {
        if ($dateTimeInput instanceof DateTime) {
            return $dateTimeInput;
        }

        return DateTime::createFromFormat($format, $dateTimeInput);
    }

    /**
     * Creates DateTime instance from Polish standard date string
     *
     * @param string|DateTime $datePlInput
     * @return DateTime|false
     */
    public static function createDateTimeFromDatePL($datePlInput)
    {
        $newDate = DateHelper::createDateTime($datePlInput, "d.m.Y");
        $newDate->setTime(0, 0, 0);
        return $newDate;
    }
    /**
     * Creates DateTime instance from Polish standard timestamp string
     *
     * @param string|DateTime $timestampPL
     * @return DateTime|false
     */
    public static function createDateTimeFromTimestampPL($timestampPL)
    {
        return DateHelper::createDateTime($timestampPL, "d.m.Y H:i:s");
    }

    /**
     * Creates new DateTime object combining date from one object and time from the other
     *
     * @param DateTime $date
     * @param DateTime $time
     * @return DateTime
     */
    public static function createDateTimeFromTwoObjects(DateTime $date, DateTime $time): DateTime
    {
        $newDate = clone $date;
        $newDate->setTime($time->format("H"), $time->format("i"), $time->format("s"));
        return $newDate;
    }

    /**
     * Creates new DateTime object with time set to 00:00:00
     *
     * @param DateTime $dateTime
     * @return DateTime
     */
    public static function createDateTimeAndTrimTime(DateTime $dateTime): DateTime
    {
        $newDate = clone $dateTime;
        $newDate->setTime(0, 0, 0);
        return $newDate;
    }
}