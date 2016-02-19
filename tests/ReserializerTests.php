<?php

namespace jedi58\Reserializer;

/**
 * @group unit
 */
class Reserializer extends \PHPUnit_Framework_TestCase
{
  protected $reserializer;
  
  public function setUp()
  {
    $this->reserializer = new Reserializer();
  }
}
