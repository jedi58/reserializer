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
		$this->assertSame(32, Reserializer::parse('i:32;'));
	}

	public function testParseString()
	{
		$this->assertSame('something', Reserializer::parse('s:9:"something";'));
	}

	public function testParseArray()
	{
		$this->assertSame(
			array('a', 'b'),
			Reserializer::parse(
			'a:2:{i:0;s:1:"a";i:1;s:1:"b";}'
		));
	}

	public function testParseAssociativeArray()
	{
		$this->assertSame(
			array(
				'a' => 'abc',
				'b' => 'def'
			),
			Reserializer::parse(
			'a:2:{s:1:"a";s:3:"abc";s:1:"b";s:3:"def";}'
		));	
	}
}
