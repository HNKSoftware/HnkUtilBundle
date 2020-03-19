<?php


namespace Hnk\HnkUtilBundle\Tests\Helper;

use Hnk\HnkUtilBundle\Helper\ArrayHelper;
use PHPUnit\Framework\TestCase;

class Foo
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}

class Bar
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}

class ArrayHelperTest extends TestCase
{

    public function testGetUniqueFieldValuesFromArray()
    {
        $this->assertEquals(
            ArrayHelper::getUniqueFieldValuesFromArray([["id" => "id", "value" => "value"]]),
            ["id"],
            "by default it returns id field values"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult([], "id", [], "empty array");
        $this->validateGetUniqueFieldValuesFromArrayResult(
            [["id" => 1]],
            "id",
            [1],
            "one item"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult(
            [["id" => 1]],
            "value",
            [],
            "one item without expected key"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult(
            [["id" => 1], ["id" => 2], ["id" => 123]],
            "id",
            [1, 2, 123],
            "multiple items"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult(
            [["id" => 1], ["id" => 2], ["id" => 1]],
            "id",
            [1, 2],
            "multiple items (duplicating)"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult(
            [["id" => null], ["id" => 23]],
            "id",
            [23],
            "multiple items (with null)"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult(
            [new Foo(123), new Foo("text")],
            "value",
            [123, "text"],
            "objects with getter as items"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult(
            [new Bar(123), new Bar("text")],
            "value",
            [123, "text"],
            "objects with property as items"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult(
            [new Bar(123), new Bar("text")],
            "undefined",
            [],
            "objects without property or getter as items"
        );
        $this->validateGetUniqueFieldValuesFromArrayResult(
            ["invalid", 432],
            "id",
            [],
            "non-iterable items"
        );
    }

    public function testIndexByField()
    {
        $this->assertEquals(
            ArrayHelper::indexByField([["id" => "id value", "name" => "name value"]]),
            ["id value" => ["id" => "id value", "name" => "name value"]],
            "by default it indexes by id field values"
        );
        $this->validateIndexByField([], "id", [], "empty array");
        $this->validateIndexByField(
            [["id" => 1]],
            "id",
            [1 => ["id" => 1]],
            "one item"
        );
        $this->validateIndexByField(
            [["id" => 1]],
            "value",
            [],
            "one item without expected key"
        );
        $this->validateIndexByField(
            [["id" => 1], ["id" => 2], ["id" => 123]],
            "id",
            [1 => ["id" => 1], 2 => ["id" => 2], 123 => ["id" => 123]],
            "multiple items"
        );
        $this->validateIndexByField(
            [["id" => 1, "name" => "item 1"], ["id" => 2, "name" => "item 2"], ["id" => 1, "name" => "item 3"]],
            "id",
            [1 => ["id" => 1, "name" => "item 3"], 2 => ["id" => 2, "name" => "item 2"]],
            "multiple items (duplicating)"
        );
        $this->validateIndexByField(
            [["id" => null], ["id" => 23]],
            "id",
            [23 => ["id" => 23]],
            "multiple items (with null)"
        );

        $object1 = new Foo(123);
        $object2 = new Foo("text");
        $this->validateIndexByField(
            [$object1, $object2],
            "value",
            [123 => $object1, "text" => $object2],
            "objects with getter as items"
        );

        $object1 = new Bar(123);
        $object2 = new Bar("text");
        $this->validateIndexByField(
            [$object1, $object2],
            "value",
            [123 => $object1, "text" => $object2],
            "objects with property as items"
        );
        $this->validateIndexByField(
            [new Bar(123), new Bar("text")],
            "undefined",
            [],
            "objects without property or getter as items"
        );
        $this->validateIndexByField(
            ["invalid", 432],
            "id",
            [],
            "non-iterable items"
        );
    }

    public function testRemoveItemByValue()
    {
        $this->validateRemoveItemByValue("invalid", "invalid", "invalid", "string input");
        $this->validateRemoveItemByValue(1, 1, 1, "int input");
        $this->validateRemoveItemByValue(true, true, true, "bool input");
        $object = new Foo(1);
        $this->validateRemoveItemByValue($object, 1, $object, "object input");

        $this->validateRemoveItemByValue(
            ["id" => 1, "name" => "test"],
            "not found",
            ["id" => 1, "name" => "test"],
            "array without expected value"
        );
        $this->validateRemoveItemByValue(["id" => 1, "name" => "test"], 1, ["name" => "test"], "array with int value");
        $this->validateRemoveItemByValue(["id" => 10, "name" => "test"], "test", ["id" => 10], "array with string value");
        $this->validateRemoveItemByValue(["id" => 11, "isReady" => true], true, ["id" => 11], "array with bool value");
        $this->validateRemoveItemByValue(["id" => 12, "child" => $object], $object, ["id" => 12], "array with object value");
        $this->validateRemoveItemByValue(
            ["id" => 12, "child" => $object, "child2" => $object],
            $object,
            ["id" => 12, "child2" => $object],
            "multiple values"
        );
    }

    public function testExtractFieldValue()
    {
        $this->assertNull(ArrayHelper::extractFieldValue(null, "id"), "null input");
        $this->assertNull(ArrayHelper::extractFieldValue("invalid", "id"), "string input");
        $this->assertNull(ArrayHelper::extractFieldValue(1, "id"), "int input");
        $this->assertNull(ArrayHelper::extractFieldValue([], "id"), "empty array");
        $this->assertNull(ArrayHelper::extractFieldValue(["val" => 1], "id"), "array without a field");
        $this->assertNull(ArrayHelper::extractFieldValue(new Foo(1), "id"), "object without property or getter");

        $this->validateExtractFieldValueResult(["id" => 123], "id", 123, "array with field");
        $this->validateExtractFieldValueResult(new Bar(234), "value", 234, "object with property");
        $this->validateExtractFieldValueResult(new Foo(345), "value", 345, "object with getter");
    }

    private function validateGetUniqueFieldValuesFromArrayResult($iterable, $fieldName, $expectedResult, $message)
    {
        $this->assertEquals(
            ArrayHelper::getUniqueFieldValuesFromArray($iterable, $fieldName),
            $expectedResult,
            $message
        );
    }

    private function validateIndexByField($iterable, $fieldName, $expectedResult, $message)
    {
        $this->assertEquals(
            ArrayHelper::indexByField($iterable, $fieldName),
            $expectedResult,
            $message
        );
    }

    private function validateRemoveItemByValue($array, $value, $expectedResult, $message)
    {
        ArrayHelper::removeItemByValue($array, $value);

        $this->assertEquals($array, $expectedResult, $message);
    }

    private function validateExtractFieldValueResult($arrayOrObject, $fieldName, $expectedResult, $message)
    {
        $this->assertEquals(
            ArrayHelper::extractFieldValue($arrayOrObject, $fieldName),
            $expectedResult,
            $message
        );
    }
}