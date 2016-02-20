<?php

namespace jedi58\Reserializer;

/**
 * Class for repairing serialised data
 */
class Reserializer
{
  /**
   * RegExp for detecting serialised arrays
   */
  const TYPE_ARRAY = '/^a:([0-9]+):{(.*?)}/';
  /**
   * RegExp for detecting serialised integers
   */
  const TYPE_INTEGER = '/^i:([0-9]+)/';
  /**
   * RegExp for detecting serialised strings
   */
  const TYPE_STRING = '/^s:([0-9]+):"(.*?)"/';
  /**
   * RegExp for detecting serialised boolean values
   */
  const TYPE_BOOL = '/^b:([01])/';
  /**
   * RegExp for detecting serialised objects
   */
  const TYPE_OBJECT = '/^O:([0-9]+):"(.*?)":([0-9]+):{(.*?)}/';
  /**
   * Parses the given string into the detected type
   * @param string $value The serialised string to parse
   * @return mixed The output of parsing the string
   */
  public static function parse($value)
  {
    $contents = '';
    if (preg_match(self::TYPE_ARRAY, $value, $output)) {
      $contents = array();
      if (!empty($output[2])) {
        self::processArray($contents, $output[2]);
      }
      return $contents;
    } elseif (preg_match(self::TYPE_OBJECT, $value, $output)) {
      $className = class_exists($output[2]) ? $output[2] : '\stdClass';
      $contents = new $className();
      if (!empty($output[4])) {
        self::processArray($contents, $output[4]);
      }
      return $contents;
    } elseif (preg_match(self::TYPE_INTEGER, $value, $output)) {
      return (int) (!empty($output[1]) ? $output[1] : null);
    } elseif (preg_match(self::TYPE_BOOL, $value, $output)) {
      return (bool) (!empty($output[1]) ? $output[1] : null);
    } elseif (preg_match(self::TYPE_STRING, $value, $output)) {
      return !empty($output[2]) ? $output[2] : null;
    }
    return null;
  }
  /**
   * Takes serialised string separated by semi-colons and
   * process the result into the provided array or object
   * @param mixed $output The array or Object to add data to (passed by reference)
   * @param string $values The values to add to the array or object
   */
  private static function processArray(&$output, $values)
  {
    $values = mb_split(';', $values);
    for ($i = 0; $i < sizeof($values); $i += 2) {
      $key = self::parse($values[$i]);
      $value = !empty($values[$i + 1]) ? self::parse($values[$i + 1]) : null;
      if (is_int($key)) {
        $output[] = $value;
      } elseif (!empty($key)) {
        if (gettype($output) == 'array') {
          $output[$key] = $value;
        } else {
          $output->$key = $value;
        }
      }
    }
  }
  /**
   * Processes the provided input and reserialises for output
   * @param string $value The serialized data to repair
   * @return string The fixed serialized data
   */
  public static function reserialize($value) 
  {
    return serialize(self::parse($value));
  }
}
