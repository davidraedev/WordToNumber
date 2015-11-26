# WordToNumber

*Converts Words To Numbers*

[![Build Status](https://travis-ci.org/daraeman/WordToNumber.png?branch=master)](https://travis-ci.org/daraeman/WordToNumber)

## Installation

Add to composer.json's `repositories` array

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/daraeman/WordToNumber.git"
        }
    ]

Run `composer update` to install.

## Usage

###### _string/FALSE_ parse( string )
Parse the supplied text and convert all number-words found into a single number `"eight hundred fifteen"`.<br>
The String need not only be word-numbers, but should contain only one word-number, as having multiple will cause undefined behavior<br>
Words do not need to have any specific separator or case, or even any separator at all `ninetEENthousandeighTY-eight`<br>
Returns the number in string form, or FALSE

###### setValidatorWhitelist( string/array )
Takes a single, or array of regex strings to test numbers against before parsing.<br>
Non-matching values will be excluded.<br>
This is run after setValidatorBlacklist.<br>
Passing a falsey value will clear the list.

###### setValidatorBlacklist( string/array )
Takes a single, or array of regex strings to test numbers against before parsing.<br>
Matching values will be excluded.<br>
This is run before setValidatorWhitelist.<br>
Passing a falsey value will clear the list.

###### _array_ listLanguages()
Lists the available languages

###### _bool_ setLanguage( (array) languageName )
Set the language that should be used to parse the text

###### updateLanguageData( string )
Name, (array) Data ) Creates or replaces a language's data.<br>Takes an array that should be formatted as the default english one.

### Example
	$wordtoNumber = new wordToNumber();
	$wordtoNumber->setValidatorBlacklist( '/hundred/i' );
	$wordtoNumber->setValidatorWhitelist([
		'/one|two|three|four/i',
		'/five|six|seven|eight/i'
	]);
	$number = $wordtoNumber->parse( 'eight' );
	var_dump( $number );
	// string(1) "8"
	$number = $wordtoNumber->parse( 'eight hundred' );
	var_dump( $number );
	// bool(false)
