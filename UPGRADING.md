# Upgrade Notes

This document lists important upgrade nodes and BC breaks per version.

## 2.0.0

### Update hashing

If you are hashing multiple values you will need to change the way their are passed to the hasher. Instead of passing
each variable separately you will need to pass them as an array. Like so:

```php

use AngryBytes\Hash\Hasher\Password;
use AngryBytes\Hash\Hash;

// Create a hasher
$hasher = new Hash(
    new Password()
);

/** Old way */

$hash = $hasher->hash($foo, $bar, $foobar);

/** New way */

$hash = $hasher->hash([
    $foo,
    $bar,
    $foobar
]);

/** Old way */

$hash = $hasher->shortHash($foo, $bar, $foobar);

/** New way */

$hash = $hasher->shortHash([
    $foo,
    $bar,
    $foobar
]);

```

### Update hash validation

In the previous version hash validation would be done by creating a hash and comparing it to the existing hash.
Now you can simple pass the value(s) to hash and the existing hash to a method, `verify()` or `verfiyShortHash()`.

```php

use AngryBytes\Hash\Hasher\Password;
use AngryBytes\Hash\Hash;

// Create a hasher
$hasher = new Hash(
    new Password()
);

// The origin and hashed values
$value = '...'
$existingHash = '...';

/** Old way */

// Create a hash
$hash = $hasher->hash($value);

// Validate hash
if (Hash::compare($hash, $existingHash)) {
    // Hash is valid
}

/** New way */

if ($hasher->verify($value, $existingHash)) {
    // Hash is valid
}


/** Old way */

$hash = $hasher->shortHash($value);

if (Hash::matchShortHash($hash, $existingHash)) {
    // Hash is valid
}

/** New way *

if ($hasher->verifyShortHash($value, $existingHash)) {
    // Hash is valid
}

```
