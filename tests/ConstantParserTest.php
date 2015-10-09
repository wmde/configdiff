<?php

use WMDE\ConfigDiff\ConstantParser;

class ConstantParserTest extends \PHPUnit_Framework_TestCase
{

	public function setUp() {
		$this->parser = new ConstantParser();
	}

	public function testGivenEmptySource_constantsAreEmpty() {
		$this->assertEquals( [], $this->parser->getConstants( '' ) );
	}

	public function testGivenOneDefine_itIsReturned() {
		$php = '<?php define("foo", "bar");';
		$expected = [ '"foo"' => '"bar"' ];
		$this->assertEquals( $expected, $this->parser->getConstants( $php ) );
	}

	public function testGivenMultipleDefines_theyAreReturned() {
		$php = '<?php define("foo", "bar"); define("quu", foo . "quux" ); define( "NULL", null);';
		$expected = [
			'"foo"' => '"bar"',
			'"quu"' => 'foo."quux"',
			'"NULL"' => 'null'
		];
		$this->assertEquals( $expected, $this->parser->getConstants( $php ) );
	}

}
