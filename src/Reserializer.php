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
  public function parse($value)
  {
    $contents = '';
    if (preg_match(self::TYPE_ARRAY, $value, $output)) {
      $contents = array();
      if (!empty($output[2])) {
        $output = mb_split(';', $output[2]);
        for ($i = 0; $i < sizeof($output); $i += 2) {
          $key = self::parse($output[$i]);
          $value = self::parse($output[$i + 1]);
          if (!empty($key) && !empty($value)) {
            $contents[$key] = $value;
          }
        }
      }
      return $contents;
    } elseif (preg_match(self::TYPE_INTEGER, $value, $output)) {
      return (int) (!empty($output[1]) ? $output[1] : null);
    } elseif (preg_match(self::TYPE_STRING, $value, $output)) {
      return !empty($output[2]) ? $output[2] : null;
    }
    return null;
  }
  /**
   *
   */
  public function reserialize($value) 
  {
    return serialize(self::parse($value));
  }
}
