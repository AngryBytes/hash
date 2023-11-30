# Upgrade Notes

This document lists important upgrade nodes and BC breaks per version.

## 2.0.0

### Update Hashing Values

If you are hashing multiple values, you will need to change the way they are passed
to the hasher. Instead of passing each variable separately, you will need to pass
them as an array. Like so:

```php

// Create a new Password Hasher
$hasher = new \AngryBytes\Hash\Hash(
  new \AngryBytes\Hash\Hasher\Password
);

// Old
$hash = $hasher->hash('foo', 'bar', ['foo', 'bar']);

// New
$hash = $hasher->hash([
  'foo', 'bar', ['foo', 'bar']
]);
```

The same principle applies to `Hash::shortHash()`.

### Update Hash Validation

In the previous version, hash validation would be done by creating a hash and comparing
it to the existing hash. Now, you can simply pass the value(s) to hash and the
existing hash to methods: `verify()` or `verfiyShortHash()`.

```php
// Create a new Password Hasher
$hasher = new \AngryBytes\Hash\Hash(
  new \AngryBytes\Hash\Hasher\Password
);

// The origin and hashed values
$valueToHash = '...'
$existingHash = '...';

// Old
$isValid = \AngryBytes\Hash\Hash::compare(
  $hasher->hash($valueToHash),
  $existingHash
);

// New
$isValid = $hasher->verify($valueToHash, $existingHash)
```

And for short hash comparison:

```php
// Create a new Password Hasher
$hasher = new \AngryBytes\Hash\Hash(
  new \AngryBytes\Hash\Hasher\Password
);

// Old
if (Hash::matchShortHash($hasher->shortHash($value), $existingShortHash)) {
    // Hash is valid
}

// New
if ($hasher->verifyShortHash($value, $existingShortHash)) {
    // Hash is valid
}
```
