<?php

namespace Hnk\HnkUtilBundle\Tests\Helper;


use DateTime;
use Hnk\HnkUtilBundle\Helper\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{

    public function testGetDateAsString()
    {
        $this->assertEquals("2020-01-01", DateHelper::getDateAsString("2020-01-01 10:00:00"), "no format");

        // invalid input
        $this->validateGetDateAsStringResult(null, "Y", null, "null input");
        $this->validateGetDateAsStringResult(1, "Y", null, "int invalid input");
        $this->validateGetDateAsStringResult(1584639537, "Y", null, "epoch int input");
        $this->validateGetDateAsStringResult("1584639537", "Y", null, "epoch string input");

        // string input
        $this->validateGetDateAsStringResult("10.11.2020", "Y-m-d", "2020-11-10", "string format 1");
        $this->validateGetDateAsStringResult("2020-12-01", "Y-m-d", "2020-12-01", "string format 2");

        // DateTime input
        $this->validateGetDateAsStringResult(DateTime::createFromFormat("Y-m-d", "2020-04-21"), "Y-m-d", "2020-04-21", "date time");

        // formatting
        $this->validateGetDateAsStringResult("08.02.2020", "Y-m-d H:i:s", "2020-02-08 00:00:00", "full format");
    }

    public function testFormatDatePL()
    {
        $this->assertEquals("01.02.2019", DateHelper::formatDatePL("2019-02-01"));
        $this->assertEquals("03.04.2019", DateHelper::formatDatePL(
            DateTime::createFromFormat("Y-m-d H:i", "2019-04-03 10:20")));
    }

    public function testFormatDateDB()
    {
        $this->assertEquals("2019-02-01", DateHelper::formatDateDB("2019-02-01"));
        $this->assertEquals("2019-04-03", DateHelper::formatDateDB(
            DateTime::createFromFormat("Y-m-d H:i", "2019-04-03 10:20")));
    }

    public function testFormatTimestampDB()
    {
        $this->assertEquals("2019-02-01 00:00:00", DateHelper::formatTimestampDB("2019-02-01"));
        $this->assertEquals("2019-04-03 10:20:05", DateHelper::formatTimestampDB(
            DateTime::createFromFormat("Y-m-d H:i:s", "2019-04-03 10:20:05")));
    }

    public function testCreateDateTime()
    {
        $this->validateCreateDateTimeResult("invalid", "Y-m-d", false, "invalid input");
        $this->validateCreateDateTimeResult("2020-01-02", "invalid", false, "invalid format");
        $this->validateCreateDateTimeResult("2020-01-02", "Y-m-d", DateTime::createFromFormat("Y-m-d", "2020-01-02"), "string input");
        $this->validateCreateDateTimeResult("05.09.1999", "d.m.Y", DateTime::createFromFormat("Y-m-d", "1999-09-05"), "string input");

        $dateTime = DateTime::createFromFormat("Y-m-d H:i:s", "2000-04-11 13:12:11");
        $result = DateHelper::createDateTime($dateTime);
        $this->assertTrue($dateTime === $result, "date time input");
    }

    public function testCreateDateTimeFromDatePL()
    {
        $this->assertEquals(
            DateTime::createFromFormat("Y-m-d H:i:s", "2019-04-03 00:00:00"),
            DateHelper::createDateTimeFromDatePL("03.04.2019")
        );
    }

    public function testCreateDateTimeFromTimestampPL()
    {
        $this->assertEquals(
            DateTime::createFromFormat("Y-m-d H:i:s", "2019-04-03 11:12:13"),
            DateHelper::createDateTimeFromTimestampPL("03.04.2019 11:12:13")
        );
    }

    public function testCreateDateTimeFromTwoObjects()
    {
        $date1 = DateTime::createFromFormat("Y-m-d H:i:s", "2019-04-03 11:12:13");
        $date2 = DateTime::createFromFormat("Y-m-d H:i:s", "2020-10-13 18:23:01");

        $result = DateHelper::createDateTimeFromTwoObjects($date1, $date1);
        $this->assertFalse($date1 === $result, "should create new date");
        $this->assertTrue($date1 == $result, "should create object with same properties");

        $result = DateHelper::createDateTimeFromTwoObjects($date1, $date2);
        $this->assertEquals(
            DateTime::createFromFormat("Y-m-d H:i:s", "2019-04-03 18:23:01"),
            $result,
            "should combine two dates"
        );
    }

    public function testCreateDateTimeAndTrimTime()
    {
        $dateTime = DateTime::createFromFormat("Y-m-d H:i:s", "2019-04-03 00:00:00");

        $result = DateHelper::createDateTimeAndTrimTime($dateTime);
        $this->assertFalse($dateTime === $result, "should create new date");
        $this->assertTrue($dateTime == $result, "should create object with same properties");

        $this->assertEquals(
            DateTime::createFromFormat("Y-m-d H:i:s", "2019-04-03 00:00:00"),
            DateHelper::createDateTimeAndTrimTime($dateTime),
            "should reset time"
        );
    }

    private function validateGetDateAsStringResult($dateTimeInput, $format, $expectedResult, $message)
    {
        $this->assertEquals($expectedResult, DateHelper::getDateAsString($dateTimeInput, $format), $message);
    }

    private function validateCreateDateTimeResult($dateTimeInput, $format, $expectedResult, $message)
    {
        $this->assertEquals($expectedResult, DateHelper::createDateTime($dateTimeInput, $format), $message);
    }
}
