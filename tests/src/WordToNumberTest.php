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

	public function testCustomValidatorWhitelistSingle() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorWhitelist( '/Five/' );

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( FALSE, $result );
		$result = $wordToNumber->parse( 'Five' );
		$this->assertEquals( 5, $result );

	}

	public function testCustomValidatorBlacklistSingle() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorBlacklist( '/Five/' );

		$result = $wordToNumber->parse( 'Five' );
		$this->assertEquals( FALSE, $result );
		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( 5, $result );

	}

	public function testCustomValidatorWhitelistArray() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorWhitelist([ '/Five/i', '/six/' ]);

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( 5, $result );
		$result = $wordToNumber->parse( 'six' );
		$this->assertEquals( 6, $result );
		$result = $wordToNumber->parse( 'seven' );
		$this->assertEquals( FALSE, $result );

	}

	public function testCustomValidatorBlacklistArray() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorBlacklist([ '/Five/i', '/six/' ]);

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( FALSE, $result );
		$result = $wordToNumber->parse( 'six' );
		$this->assertEquals( FALSE, $result );
		$result = $wordToNumber->parse( 'seven' );
		$this->assertEquals( 7, $result );

	}

	public function testClearCustomValidatorWhitelist() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorWhitelist( '/five/' );

		$result = $wordToNumber->parse( 'six' );
		$this->assertEquals( FALSE, $result );

		$wordToNumber->setValidatorWhitelist( FALSE );

		$result = $wordToNumber->parse( 'six' );
		$this->assertEquals( 6, $result );

	}

	public function testClearCustomValidatorBlacklist() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->setValidatorBlacklist( '/five/' );

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( FALSE, $result );

		$wordToNumber->setValidatorBlacklist( FALSE );

		$result = $wordToNumber->parse( 'five' );
		$this->assertEquals( 5, $result );
		
	}

}