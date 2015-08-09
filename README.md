# WordToNumber

*Converts Words To Numbers*

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

`parse( string )`
Parse the supplied text and convert all number-words found into a single number `"eight hundred fifteen"`.
This is an inclusive search, so provided text should be a single word-number only.
A string containing multiple word-numbers `"four eight fifteen sixteen twenty-three fortytwo"` will not match correctly. Words do not need to have any specific separator or case, or even any separator at all `ninetEENthousandeighTY-eight`
Returns the number in string form, or FALSE

`listLanguages()`
Lists the available languages

`setLanguage( (array) languageName )`
Set the language that should be used to parse the text

`updateLanguageData( (string) Name, (array) Data )`
Creates or replaces a language's data. Takes an array that should be formatted as the default english one.