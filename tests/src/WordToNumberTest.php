﻿<?php

use daraeman\WordToNumber;

class WordToNumberTest extends \PHPUnit_Framework_TestCase {
	
	public function testParseNumberNormal() {

		$wordToNumber = new WordToNumber();

		$checks = [
			"one" => "1",
			"seventy three" => "73",
			"one hundred" => "100",
			"one hundred seven" => "107",
			"one hundred seventy" => "170",
			"one hundred seventy three" => "173",
			"one hundred seventy-three" => "173",
			"one hundred and seventy-three" => "173",
			"one hundred and seventy-three thousand" => "173000",
			"one hundred and seventy-three million" => "173000000",
			"one hundred and seventy-three million two thousand" => "173002000",
			"one hundred,,,,,and,,,,,seventy-three million two thousand and two" => "173002002",
			"one hundred and - --seventy-three billion" => "173000000000",
			"one hundred.- .,and seventy-three trillion five million sixty seven thousand one hundred and eighty two" => "173000005067182",
			"just some extra text here one hundred and seventy-three some more text at the end" => "173",
		];

		foreach ( $checks as $check => $expected ) {
			$result = $wordToNumber->parse( $check );
			$this->assertEquals( $expected, $result );
		}
	}

	public function testParseNumberOddlyFormatted() {

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->parse( "ninetEENthousandeighTY-eight" );

		$this->assertEquals( "19088", $result );
	}

	public function testListLanguages() {

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->listLanguages();

		$this->assertEquals( [ "english" ], $result );
	}

	public function testSetLanguageExists() {

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->setLanguage( "english" );

		$this->assertEquals( TRUE, $result );
	}

	public function testSetLanguageDoesNotExist() {

		$wordToNumber = new WordToNumber();
		$result = $wordToNumber->setLanguage( "Pirahã" );

		$this->assertEquals( FALSE, $result );
	}

	public function testUpdateLanguageData() {

		$wordToNumber = new WordToNumber();
		$wordToNumber->updateLanguageData( "Pirahã", [ "all the datas" => TRUE ] );
		$result = $wordToNumber->getLanguageData( "Pirahã" );
		$result = ( ! empty( $result ) );

		$this->assertEquals( TRUE, $result );
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