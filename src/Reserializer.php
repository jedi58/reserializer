<?php

namespace jedi58\Reserializer;

/**
 *
 */
class Reserializer
{
  /**
   *
   */
  const TYPE_ARRAY = '/^a:([0-9]+):{(.*?)}/';
  /**
   *
   */
  const TYPE_INTEGER = '/^i:([0-9]+)/';
  /**
   *
   */
  const TYPE_STRING = '/^s:([0-9]+):"(.*?)"/';
  /**
   *
   */
  const TYPE_BOOL = '/^b:([01])/';
  /**
   *
   */
  const TYPE_OBJECT = '/^O:([0-9]+):"(.*?)":([0-9]+):{(.*?)}/';
  /**
   *
   */
  public static function parse($value)
  {
    $contents = '';
    if (preg_match(self::TYPE_ARRAY, $value, $output)) {
      $contents = array();
      if (!empty($output[2])) {
        $output = mb_split(';', $output[2]);
        for ($i = 0; $i < sizeof($output); $i += 2) {
          $key = self::parse($output[$i]);
          $value = self::parse($output[$i + 1]);
          if (is_int($key)) {
            $contents[] = $value;
          } elseif (!empty($key)) {
            $contents[$key] = $value;
          }
        }
      }
      return $contents;
    } elseif (preg_match(self::TYPE_OBJECT, $value, $output)) {
      $contents = new \stdClass(); // @todo replace this with $output[2]
      if (!empty($output[4])) {
        $output = mb_split(';', $output[4]);
        for ($i = 0; $i < sizeof($output); $i += 2) {
          $key = self::parse($output[$i]);
          $value = self::parse($output[$i + 1]);
          if (!empty($key)) {
            $contents->$key = $value;
          }
        }
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
   *
   */
  public static function reserialize($value) 
  {
    return serialize(self::parse($value));
  }
}
