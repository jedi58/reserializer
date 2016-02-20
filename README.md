# Reserializer
[![Build Status](https://travis-ci.org/jedi58/Reserializer.svg)](https://travis-ci.org/jedi58/Reserializer)
[![Code Climate](https://codeclimate.com/github/jedi58/Reserializer/badges/gpa.svg)](https://codeclimate.com/github/jedi58/Reserializer)

PHP class that will take a serialised object and parse it, ignoring the sizes specified. This is useful when serialized objects are corrupted and need fixing

## Usage

```php
Reserializer::parse('s:21:"https://www.google.com";');
```
The above example contains an invalid serialized object as the string is one character longer than it should be. The `parse` function will take this, ignore the suggested length and will return the string ready to be reserialised. Alternatively, the class can do the whole process itself:

```php
Reserializer::reserialize('s:21:"https://www.google.com";')
```
The expected output for this is `s:22:"https://www.google.com";`. It is possible to use this for more complicated examples of type bool, int, string, array, and objects. In the case of objects they will however be converted into `stdClass`.
