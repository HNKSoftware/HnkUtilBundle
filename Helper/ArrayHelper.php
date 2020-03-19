<?php


namespace Hnk\HnkUtilBundle\Helper;


class ArrayHelper
{

    /**
     * Returns array of values of a specified field from multi-dimensional array
     *
     *  [[id => 1], [id => 2], [id => 1]] ==> [1, 2]
     *
     * @param array | object $iterable
     * @param string $fieldName
     * @return array
     */
    public static function getUniqueFieldValuesFromArray($iterable, string $fieldName = 'id')
    {
        $values = [];

        foreach ($iterable as $item) {
            $value = ArrayHelper::extractFieldValue($item, $fieldName);

            if ($value !== null && !in_array($value, $values)) {
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * Returns original array with keys switched to the value of a specified field
     *
     * [0 => [id => 123], 1 => [id => 25]] ==> [123 => [id => 123], 25 => [id => 25]]
     *
     * @param array|object $iterable
     * @param string $fieldName
     * @return array
     */
    public static function indexByField($iterable, string $fieldName = 'id')
    {
        $values = [];

        foreach ($iterable as $item) {
            $value = ArrayHelper::extractFieldValue($item, $fieldName);

            if ($value !== null) {
                $values[$value] = $item;
            }
        }

        return $values;
    }

    /**
     * Removes first occurrence of $value in $array
     *
     * @param array $array
     * @param mixed $value
     */
    public static function removeItemByValue(&$array, $value)
    {
        if (!is_array($array)) {
            return;
        }

        if(($key = array_search($value, $array, true)) !== false) {
            unset($array[$key]);
        }
    }

    /**
     * Extracts value related to fieldName from array or object
     *
     * @param array|object $arrayOrObject
     * @param string $fieldName
     * @return mixed|null
     */
    public static function extractFieldValue($arrayOrObject, string $fieldName) {
        if (is_object($arrayOrObject)) {
            $getterMethod = sprintf('get%s', ucfirst($fieldName));

            if (method_exists($arrayOrObject, $getterMethod)) {
                return $arrayOrObject->$getterMethod();
            }

            if (property_exists($arrayOrObject, $fieldName)) {
                return $arrayOrObject->$fieldName;
            }
        }

        if (is_array($arrayOrObject) && array_key_exists($fieldName, $arrayOrObject)) {
            return $arrayOrObject[$fieldName];
        }

        return null;
    }

}