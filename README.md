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
Parse the supplied text and convert all number-words found into a single number `"eight hundred fifteen"`.
This is an inclusive search, so provided text should be a single word-number only.
A string containing multiple word-numbers `"four eight fifteen sixteen twenty-three fortytwo"` will not match correctly. Words do not need to have any specific separator or case, or even any separator at all `ninetEENthousandeighTY-eight`
Returns the number in string form, or FALSE

###### setValidatorWhitelist( string/array )
Takes a single, or array of regex strings to test numbers against before parsing. Non-matching values will be excluded. This is run after setValidatorBlacklist. Passing a falsey value will clear the list.

###### setValidatorBlacklist( string/array )
Takes a single, or array of regex strings to test numbers against before parsing. Matching values will be excluded. This is run before setValidatorWhitelist. Passing a falsey value will clear the list.

###### _array_ listLanguages()
Lists the available languages

###### _bool_ setLanguage( (array) languageName )
Set the language that should be used to parse the text

###### updateLanguageData( string )
Name, (array) Data ) Creates or replaces a language's data. Takes an array that should be formatted as the default english one.
