# Changelog

## 2.0.0

### New Password Hasher

Version `2.0.0` comes with the new `AngryBytes\Hash\Hasher\Password` hasher. This hasher is intended to be used for
hashing passwords and other secure tokens. The hasher utilises PHP's native password hashing functions like
`password_hash()` and `password_verify()`, see [Password Hashing](http://php.net/manual/en/book.password.php).

The password hasher has been setup to use PHP's default cost and algorithm. The default cost can however be overwritten
via the `setCost()` method, like so:

```php
$hasher = new \AngryBytes\Hash\Hasher\Password;
$hasher->setCost(15);
```

The password hasher offers a method to check if an existing hash needs to be rehashed. This can be the case if
the cost and/or algorithm of the hasher has changed. If this is the case you'll want to rehash and store your existing hashes.

#### Example

In this example we will check if an existing password hash needs to be rehashed, if so we'll hash the password value.
Note, in order to rehash the password you will need access to the plaintext value. It's advised to to this after
a user has successfully entered their password.

```php

use AngryBytes\Hash\Hasher\Password;
use AngryBytes\Hash\Hash;

// Create a password hasher
$hasher = new Hash(
    new Password()
);

$password = 'plaintext password'
$passwordHash = 'password hash';

// Verify the password against the hash
if ($hasher->verify($password, $passwordHash)) {
    // Check if the hash needs to be rehashed
    if ($hasher->getHasher()->needsRehash($passwordHash)) {
        // Rehash the password
        $passwordHash = $hasher->hash($password);
    }
}

```

### Refactored AngryBytes\Hash\HasherInterface

`AngryBytes\Hash\HasherInterface::verify()` is a new method for verify an existing hash. The method accepts the
following three arguments:

* `$data` - The data that needs to be hashed.
* `$hash` - The hash that needs to match the hashed value of `$data`.
* `$salt` - The salt used for hashing.

### Refactored AngryBytes\Hash\Hash

`AngryBytes\Hash\Hash::construct()` second argument (`$salt`) has become optional to accommodate for hashers that
handle their own salt, like `AngryBytes\Hash\Hasher\Password`.

`AngryBytes\Hash\Hash::hash()` and `AngryBytes\Hash\Hash::shortHash()` no longer accept any number of arguments but
only one. This single argument can however be of any type. All non-scalar types will be serialized before hashing. 

`AngryBytes\Hash\Hash::verify()` is a new method that's available to validate a hash in a time-safe manner.
The method accepts the following two arguments:

* `$data` - The data that needs to be hashed. This data can be of any type, all non-scalar types will be
 serialized before hashing.
* `$hash` - The hash that needs to match the hashed value of `$data`.

`AngryBytes\Hash\Hash::matchesShortHash()` is replaced by `AngryBytes\Hash\Hash::verifyShortHash()` this methods
accepts two arguments (`$data` and `$hash`) just like `AngryBytes\Hash\Hash::verify()`.

### Minor Changes

* Scalar values passed to `hash()` and `shortHash()` are no longer serialized.
* `compare()` Now uses PHP native `hash_equals()` function.
* Fixed namespace issues for `AngryBytes\Hash\HMAC`.

### Upgrading

Please refer to the [upgrade notes](UPGRADING.md).

### Deprecated & Removed Components

`AngryBytes\Hash\RandomString` has been removed.

## 1.0.2
Valid Blowfish salts (22 char long composed of ./A-Za-z0-9 characters only) are now used as the salt as-is instead
of md5-ed and substring-ed.

## 1.0.1
Adding travis build status and scrutinizer code qual. img. to readme

## 1.0.0
Initial release
