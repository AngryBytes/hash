# AngryBytes Hash

[![Author](http://img.shields.io/badge/author-@angrybytes-blue.svg?style=flat-square)](https://twitter.com/angrybytes)
[![Software License](https://img.shields.io/badge/license-proprietary-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/AngryBytes/hash.svg?branch=master)](https://travis-ci.org/AngryBytes/hash)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AngryBytes/hash/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AngryBytes/hash/?branch=master)

A simple PHP library that simplifies cryptographical hashing. It provides an
object oriented interface to a variety of hashing methods.

## Requirements

* PHP `7.3`, `7.4` or PHP `8.0` (recommended)

## Installation

Installation is done via Composer: `composer require angrybytes/hash`.

## Components

### Hash

`AngryBytes\Hash\Hash` is the main hasher class and acts as a helper wrapper
around hashers (i.e. `AngryBytes\Hash\HasherInterface` implementations).

Some of the main features of this component:

* Hash strings and/or passwords.
* Create short hashes (e.g. used for identification).
* Compare strings/hashes using a time-safe method.
* Verify a string against a hash using the configured hasher.

### Hashers

This library comes with a set of hashers to be utilised by this hash component (or
to be used on their own):

 * `AngryBytes\Hash\Hasher\BlowFish`
 * `AngryBytes\Hash\Hasher\MD5`
 * `AngryBytes\Hash\Hasher\Password`

### HMAC

`AngryBytes\Hash\HMAC` can be used to generate
[HMAC's](http://en.wikipedia.org/wiki/Hash-based_message_authentication_code)
for string messages.

## Contributing

Before contributing to this project, please read the [contributing notes](CONTRIBUTING.md).

## License

Please refer to the [license file](LICENSE.md).
