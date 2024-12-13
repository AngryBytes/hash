# Changelog

## 6.0.0

### PHP support

- Dropped support for PHP `8.1` and lower.
- Added support for PHP `8.4`.

## 5.0.0

### PHP support

- Dropped support for PHP `8.0` and lower.
- Added support for PHP `8.3`.

## 4.0.1

## PHP Support

Support was added for PHP 8.2

## 4.0.0

## PHP Support

The minimum supported PHP version is now 7.4.

Support was added for PHP 8.1

## 3.0.0

## PHP Support

The minimum supported PHP version is now 7.3.

Support was added for PHP 8.0

## 2.0.0

### New Password Hasher

Version `2.0.0` comes with a new password hasher component: `AngryBytes\Hash\Hasher\Password`.
This hasher is intended to be used for hashing of passwords (and other secure tokens).

The hasher utilises PHP's native cryptographically strong password hashing functions:
`password_hash()` and `password_verify()`, see [Password Hashing](http://php.net/manual/en/book.password.php).

The password hasher has been setup to use PHP's default cost and algorithm.
The default cost can however be overwritten by providing a cost to the the constructor, like so:

```php
// Create a password hasher with n cost factor of 15 instead of the default (10).
$passwordHasher = new \AngryBytes\Hash\Hasher\Password(15);
```

#### Password Rehashing

The password hasher offers a method to check if an existing hash needs to be **rehashed**.
For example, this can be the case when the cost and/or algorithm of the password
hasher has changed. If this is the case, you **should** rehash the password
and update the stored hash with the rehashed value.

**Example**

In this example, we check whether an existing password is outdated and should be rehashed.

Note: in order to rehash the password, you will need access to its original plaintext value.
Therefore, it's probably a best practice to check for and update a stale hash
during login procedure, where the plaintext password is available after a login form
submit.

```php
// Create a password hasher
$hasher = new \AngryBytes\Hash\Hash(
  new \AngryBytes\Hash\Hasher\Password
);

// Plaintext password received via form submit.
$password = '...';

// Persisted password hash for the User
$userPasswordHash = '...';

// Verify the password against the hash
if ($hasher->verify($password, $userPasswordHash)) {

    // Check if the hash needs to be rehashed?
    if ($hasher->needsRehash($userPasswordHash)) {
        // Rehash password and update the user.
        $user->hash = $hasher->hash($password);
        $user->save();
    }
}
```

### Refactored "AngryBytes\Hash\HasherInterface"

Added new verification method `AngryBytes\Hash\HasherInterface::verify()` hash
verification. This method accepts the following three arguments:

- `$data` - The data that needs to be hashed.
- `$hash` - The hash that needs to match the hashed value of `$data`.
- `$options` (optional) - An array with addition hasher options. What these options are depends on the active hasher.

### Refactored AngryBytes\Hash\Hash

`AngryBytes\Hash\Hash::construct()` second argument (`$salt`) has become optional
to accommodate for hashers that handle their own salt, like `AngryBytes\Hash\Hasher\Password`.

`AngryBytes\Hash\Hash::hash()` and `AngryBytes\Hash\Hash::shortHash()` no longer accept any number of arguments but
only following two:

- `$data` - The data that needs to be hashed. This data can be of any type, all non-scalar types will be
  serialized before hashing.
- `$options` (optional) - An array with options that will be passed to the hasher. What these options are depends
  on the active hasher.

`AngryBytes\Hash\Hash::verify()` is a new method that's available to validate a hash in a time-safe manner.
The method accepts the following arguments:

- `$data` - The data that needs to be hashed. This data can be of any type, all non-scalar types will be
  serialized before hashing.
- `$hash` - The hash that needs to match the hashed value of `$data`.
- `$options` (optional) - An array with options that will be passed to the hasher. What these options are depends
  on the active hasher.

`AngryBytes\Hash\Hash::matchesShortHash()` is replaced by `AngryBytes\Hash\Hash::verifyShortHash()` this methods
accepts three arguments (`$data`, `$hash` and `$options`) just like `AngryBytes\Hash\Hash::verify()`.

### Minor Changes

- Scalar values passed to `hash()` and `shortHash()` are no longer serialized.
- `AngryBytes\Hash::compare()` now uses PHP native (timing attack safe) `hash_equals()` function.
- Fixed namespace issues for `AngryBytes\Hash\HMAC`.
- `AngryBytes\Hash\Hash` now implements a `__call()` method that dynamically passes
  methods to the active hasher. This allows you to perform calls such as `AngryBytes\Hash::hash($string, ['cost' => 15]);`
  without having to call `AngryBytes\Hash::getHasher()` first.

### Upgrading

Please refer to the [upgrade notes](UPGRADING.md).

### Deprecated & Removed Components

- `AngryBytes\Hash\RandomString` has been removed. Better open-source random string generation
  libraries are available to do this.

## 1.0.2

Valid Blowfish salts (22 char long composed of ./A-Za-z0-9 characters only) are now used as the salt as-is instead
of md5-ed and substring-ed.

## 1.0.1

Adding travis build status and scrutinizer code qual. img. to readme

## 1.0.0

Initial release
