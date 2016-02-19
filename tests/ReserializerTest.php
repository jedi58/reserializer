<?php

namespace jedi58\Reserializer\Tests;

use jedi58\Reserializer\Reserializer;

/**
 * @group unit
 */
class ReserializerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function testParse_Int()
	{
		$this->assertSame(32, Reserializer::parse('i:32'));
	}
}
