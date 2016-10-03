# AngryBytes Hash

[![Author](http://img.shields.io/badge/author-@angrybytes-blue.svg?style=flat-square)](https://twitter.com/angrybytes)
[![Software License](https://img.shields.io/badge/license-proprietary-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/AngryBytes/hash.svg?branch=master)](https://travis-ci.org/AngryBytes/hash)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AngryBytes/hash/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AngryBytes/hash/?branch=master)

A simple PHP library that simplifies cryptographical hashing. It provides an
object oriented interface to a variety of hashing methods.

## Requirements

* PHP `5.6.0` or `PHP 7.0` (recommended)

## Installation

Installation is done via Composer: `composer require angrybytes/hash`.

## Contents

### Hash

`AngryBytes\Hash\Hash` is the main hasher class. It uses one of the `Hashers` to do the work.

Available hashers are:

 * `AngryBytes\Hash\Hasher\BlowFish`
 * `AngryBytes\Hash\Hasher\MD5`
 * `AngryBytes\Hash\Hasher\Password`

In addition this class can compare strings/hashes using a time-safe method.

### HMAC

`AngryBytes\Hash\HMAC` can be used to generate
[HMAC's](http://en.wikipedia.org/wiki/Hash-based_message_authentication_code)
for string messages.

## Contributing

Before contributing to this project, please read the [contributing notes](CONTRIBUTING.md).

## License

Please refer to the [license file](LICENSE.md).
