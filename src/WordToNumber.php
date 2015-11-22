<?php

namespace daraeman\WordToNumber;

/*
	wordToNumber Class
		converts human readable word-numbers into (string) digits
*/

class WordToNumber {

	/*
		(string) language
			current language
	*/
	private $language = "english";

	/*
		(array) validate_whitelist
		(array) validate_blacklist
			regexes to determine whether a string should be parsed
	*/
	private $validate_whitelist = [];
	private $validate_blacklist = [];

	/*
		(array) languages
			array of necessary number [ name => value ] pairs
				for single, tens, and place separators
				in the "large" category, the value for each key
				is the total number of digits, aka the nubmer of zeros plus one
	*/
	private $languages = [
		"english" => [
			"single" => [
				"zero" => '0',
				"one" => '1',
				"two" => '2',
				"three" => '3',
				"four" => '4',
				"five" => '5',
				"six" => '6',
				"seven" => '7',
				"eight" => '8',
				"nine" => '9'
			],
			"tens" => [
				"ten" => '10',
				"eleven" => '11',
				"twelve" => '12',
				"thirteen" => '13',
				"fourteen" => '14',
				"fifteen" => '15',
				"sixteen" => '16',
				"seventeen" => '17',
				"eighteen" => '18',
				"nineteen" => '19',
				"twenty" => '20',
				"thirty" => '30',
				"forty" => '40',
				"fourty" => '40',
				"fifty" => '50',
				"sixty" => '60',
				"seventy" => '70',
				"eighty" => '80',
				"ninety" => '90'
			],
			"large" => [
				"hundred" => 3,
				"thousand" => 4,
				"million" => 7,
				"billion" => 10,
				"trillion" => 13,
				"quadrillion" => 16,
				"quintillion" => 19,
				"sextillion" => 22,
				"septillion" => 23,
				"octillion" => 28,
				"nonillion" => 31,
				"decillion" => 34,
				"undecillion" => 37,
				"duodecillion" => 40,
				"tredecillion" => 41,
				"quattuordecillion" => 46,
				"quindecillion" => 49,
				"sexdecillion" => 52,
				"septendecillion" => 53,
				"octodecillion" => 58,
				"novemdecillion" => 61,
				"vigintillion" => 64,
				"unvigintillion" =>  67,
				"duovigintillion" => 70,
				"tresvigintillion" => 73,
				"quattuorvigintillion" => 76,
				"quinquavigintillion" => 79,
				"sesvigintillion" => 82,
				"septemvigintillion" => 85,
				"octovigintillion" => 88,
				"novemvigintillion" => 91,
				"trigintillion" => 94,
				"untrigintillion" => 97,
				"duotrigintillion" => 100,
				"googol" => 101,
				"trestrigintillion" => 103,
				"quattuortrigintillion" => 106,
				"quinquatrigintillion" => 110,
				"sestrigintillion" => 112,
				"septentrigintillion" => 115,
				"octotrigintillion" => 118,
				"noventrigintillion" => 121,
				"quadragintillion" => 124,
				"quinquagintillion" => 154,
				"sexagintillion" => 184,
				"septuagintillion" => 214,
				"octogintillion" => 244,
				"nonagintillion" => 274,
				"centillion" => 304,
				"uncentillion" => 307,
				"duocentillion" => 310,
				"trescentillion" => 313,
				"decicentillion" => 334,
				"undecicentillion" => 337,
				"viginticentillion" => 364,
				"unviginticentillion" => 367,
				"trigintacentillion" => 394,
				"quadragintacentillion" => 424,
				"quinquagintacentillion" => 454,
				"sexagintacentillion" => 484,
				"septuagintacentillion" => 514,
				"octogintacentillion" => 544,
				"nonagintacentillion" => 574,
				"ducentillion" => 604,
				"trecentillion" => 904,
				"quadringentillion" => 1204,
				"quingentillion" => 1504,
				"sescentillion" => 1804,
				"septingentillion" => 2104,
				"octingentillion" => 2404,
				"nongentillion" => 2704,
				"millinillion" => 3004
			]
		]
	];

	/*
		listLanguages
			return array of available languages
	*/
	public function listLanguages() {
		return array_keys( $this->languages );
	}

	/*
		setLanguage (string)
			changes the default language
			returns FALSE if it doesn't exist
	*/
	public function setLanguage( $language ) {
		if ( ! isset( $this->languages[ $language ] ) )
			return FALSE;

		$this->language = $language;
		return TRUE;
	}

	/*
		getLanguageData
			return a language's data
	*/
	public function getLanguageData( $language ) {
		return $this->languages[ $language ];
	}

	/*
		updateLanguageData
			create or replaces a language's data
	*/
	public function updateLanguageData( $language, $data ) {
		$this->languages[ $language ] = $data;
	}

	/*
		setValidatorWhitelist
			Takes a single, or array of regex strings
			to test numbers against before parsing.
			Only matching values will be included.
			This is run before setValidatorWhitelist.
			Passing a falsey value will clear thie list.
	*/
	public function setValidatorWhitelist( $list ) {

		if ( empty( $list ) )
			$this->validate_whitelist = [];
		elseif ( is_array( $list ) )
			$this->validate_whitelist = $list;
		else
			$this->validate_whitelist = [ $list ];
	}

	/*
		setValidatorBlacklist
			Takes a single, or array of regex strings
			to test numbers against before parsing.
			Matching values will be exluded.
			This is run before setValidatorWhitelist.
			Passing a falsey value will clear thie list.
	*/
	public function setValidatorBlacklist( $list ) {

		if ( empty( $list ) )
			$this->validate_blacklist = [];
		elseif ( is_array( $list ) )
			$this->validate_blacklist = $list;
		else
			$this->validate_blacklist = [ $list ];
	}

	/*
		validate
			Validates a string against the setValidatorBlacklist
			and then the setValidatorWhitelist to test numberstrings
			against before parsing
	*/
	public function validate( $string ) {

		if ( ! empty( $this->validate_blacklist ) ) {
			foreach( $this->validate_blacklist as $regex ) {
				if ( ! preg_match( $regex, $string ) )
					return FALSE;
			}
		}

		if ( empty( $this->validate_whitelist ) )
			return TRUE;

		foreach( $this->validate_whitelist as $regex ) {
			if ( preg_match( $regex, $string ) )
				return TRUE;
		}

		return FALSE;

	}

	/*
		createNumber
			Creates a number with $len zeros in it
			and prepends the specified number to the in place of ther first zero
			ex. createNumber( 23, 5 )
				= 230000
	*/
	private function createNumber( $pre_number, $len ) {
		$number = ($len>0) ? str_repeat( "0", $len ) : '';
		if ( $pre_number )
			$number = $pre_number . substr( $number, 1 );
		return $number;
	}

	/*
		appendNumber
			Creates a number with $len zeros in it
				and right-aligned overwrites $number with it
			ex. appendNumber( 111, 5, 230000 )
				= 230111
	*/
	private function appendNumber( $pre_number, $len, $number ) {
		$insert = $this->createNumber( $pre_number, $len );
		$result = substr( $number, 0, (strlen( $number ) - strlen( $insert )) ) . $insert;
		return $result;
	}

	/*
		parseGroup
			Attempts to parse a string and return the hundreds number for it
			This is used to generate the amount for each number separator ( thousand, million... )
			Will match hundreds, tens, and singles
			Returns number or FALSE
	*/
	private function parseGroup( $text ) {

		$number = FALSE;
		$pre_number = FALSE;
		$post_number = $text;
		if ( strpos( $text, "hundred" ) !== FALSE ) {

			$matches = $this->trimArray( explode( "hundred", $text ) );

			if ( strlen( $matches[0] ) )
				$pre_number = $this->parseSingle( $matches[0] );
			
			$number = $this->createNumber( $pre_number, 3 );
			
			$post_number = trim( $matches[1] );

		}
		if ( strlen( $post_number ) ) {
			$tens = $this->parseTens( $post_number );
			if ( strlen( $tens ) ) {
				if ( $number )
					$number = $this->appendNumber( $tens, 1, ( $number ) ? $number : '' );
				else
					$number = $tens;
				$post_number = FALSE;
			}

		}
		if ( ! $number ) {
			$number = $this->parseSingle( $post_number );
		}
		elseif ( $post_number ) {
			$single = $this->parseSingle( $post_number );
			$number = $this->appendNumber( $single, 1, ( $number ) ? $number : '' );
		}

		return $number;

	}

	/*
		parseSingle
			Attempts to parse a string as a single digit
	*/
	private function parseSingle( $text ) {

		foreach ( $this->languages[ $this->language ]["single"] as $word => $val ) {

			if ( strpos( $text, $word ) !== FALSE )
				return $val;
		}

		return FALSE;

	}

	/*
		parseTens
			Attempts to parse a string for the tens place
			Will match for single digit only if a tens is found
			otherwise returns FALSE;
	*/
	private function parseTens( $text ) {

		$number = FALSE;
		foreach ( $this->languages[ $this->language ]["tens"] as $word => $val ) {

			$match = explode( $word, $text );

			if ( $match && isset( $match[1] ) ) {
				$number = $val;
				$single = ( ! empty( $match[1] ) ) ? $this->parseSingle( str_replace( $word, "", $text ) ) : FALSE ;
				return ( $single ) ? substr( $number, 0, 1 ) . $single : $number;
			}

		}

		return false;

	}

	/*
		parse
			The main function of this class.
			Takes a string as input,
			attempts to parse it to numbers,
			then returns it or FALSE
	*/
	public function parse( $text ){

		if ( ! $this->validate( $text ) )
			return FALSE;

		$text = strtolower( $text );

		$number = FALSE;
		foreach ( $this->languages[ $this->language ]["large"] as $word => $val ) {

			if ( strpos( $text, $word ) !== FALSE ) {

				$match = $this->trimArray( explode( $word, $text ) );
				$pre_number = ( $match && ! empty( $match[0] ) ) ? $match[0] : FALSE ;
				$text = $match[1];

				$pre_number_parsed = ( $this->parseGroup( $pre_number ) ) ?: '';

				if ( $number == FALSE )
					$number = $this->createNumber( $pre_number_parsed, $val );
				else
					$number = $this->appendNumber( $pre_number_parsed, $val, $number );
			}
		}

		$pre_number_parsed = ( $this->parseGroup( $text ) ) ?: '';
		if ( $pre_number_parsed !== FALSE ) {
			if ( $number )
				$number = $this->appendNumber( $pre_number_parsed, ( strlen( $pre_number_parsed ) - 1 ), $number );
			else
				$number = $pre_number_parsed;
		}

		return $number;
	}

	private function trimArray( $array ) {
		return array_map( "trim", $array );
	}

}