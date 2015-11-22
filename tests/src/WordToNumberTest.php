<?php

use daraeman\WordToNumber\WordToNumber;

class WordToNumberTest extends \PHPUnit_Framework_TestCase {
	
	public function testParseNumberNormal() {
		$input = "eight hundred fifteen";
		$expectedResult = "815";

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->parse( $input );

		$this->assertEquals( $expectedResult, $result );
	}

	public function testParseNumberOddlyFormatted() {
		$input = "ninetEENthousandeighTY-eight";
		$expectedResult = "19088";

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->parse( $input );

		$this->assertEquals( $expectedResult, $result );
	}

	public function testListLanguages() {
		$expectedResult = [ "english" ];

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->listLanguages();

		$this->assertEquals( $expectedResult, $result );
	}

	public function testSetLanguageExists() {
		$expectedResult = TRUE;

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->setLanguage( "english" );

		$this->assertEquals( $expectedResult, $result );
	}

	public function testSetLanguageDoesNotExist() {
		$expectedResult = FALSE;

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->setLanguage( "Pirahã" );

		$this->assertEquals( $expectedResult, $result );
	}

	public function testUpdateLanguageData() {
		$expectedResult = TRUE;

		$wordToNumber = new WordToNumber();
		$wordToNumber->updateLanguageData( "Pirahã", [ "all the datas" => TRUE ] );
		$result = $wordToNumber->getLanguageData( "Pirahã" );
		$result = ( ! empty( $result ) );

		$this->assertEquals( $expectedResult, $result );
	}

	public function testCustomValidatorSingle() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidator( '/Five/' );

		$result = $wordToNumber->parse( 'I AM NUMBER ONE!' );
		$this->assertEquals( FALSE, $result );

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( FALSE, $result );

		$result = $wordToNumber->parse( 'Five hundred' );
		$this->assertEquals( 500, $result );

		$result = $wordToNumber->parse( 'Five' );
		$this->assertEquals( 5, $result );

	}

	public function testCustomValidatorArray() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidator([
			'/Five/i',
			'/sixteen/'
		]);

		$result = $wordToNumber->parse( 'I AM NUMBER ONE!' );
		$this->assertEquals( FALSE, $result );

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( 5, $result );

		$result = $wordToNumber->parse( 'Five hundred' );
		$this->assertEquals( 500, $result );

		$result = $wordToNumber->parse( 'Five' );
		$this->assertEquals( 5, $result );

		$result = $wordToNumber->parse( 'sixteen' );
		$this->assertEquals( 16, $result );

		$wordToNumber->setValidator([
			'/Five/i',
			'/sixteen/'
		], FALSE );

		$result = $wordToNumber->parse( 'sixteen' );
		$this->assertEquals( FALSE, $result );
	}

	public function testClearCustomValidator() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorWhitelist( '/Five/' );

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( FALSE, $result );
		$result = $wordToNumber->parse( 'Five' );
		$this->assertEquals( 5, $result );

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorWhitelist([ '/Five/i', '/six/' ]);

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( 5, $result );
		$result = $wordToNumber->parse( 'six' );
		$this->assertEquals( 6, $result );
		$result = $wordToNumber->parse( 'seven' );
		$this->assertEquals( FALSE, $result );

		$wordToNumber->setValidatorWhitelist( 0 );
		$wordToNumber->setValidatorBlacklist( '/Five/' );

		$result = $wordToNumber->parse( 'Five' );
		$this->assertEquals( FALSE, $result );
		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( 5, $result );

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorBlacklist([ '/Five/i', '/six/' ]);

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( FALSE, $result );
		$result = $wordToNumber->parse( 'six' );
		$this->assertEquals( FALSE, $result );
		$result = $wordToNumber->parse( 'seven' );
		$this->assertEquals( 7, $result );
	}

}