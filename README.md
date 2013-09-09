# AngryBytes Hash

A simple PHP library that simplifies cryptographical hashing. It provides an
object oriented interface to a variety of hashing methods.

## Contents

### Hash

`AngryBytes\Hash\Hash` is the main hasher class. It uses one of the `Hashers` to do the work.

Available hashers are:

 * `AngryBytes\Hash\Hasher\BlowFish`
 * `AngryBytes\Hash\Hasher\MD5`

In addition this class can compare strings/hashes using a time-safe method.

### HMAC

`AngryBytes\Hash\HMAC` can be used to generate
[HMAC's](http://en.wikipedia.org/wiki/Hash-based_message_authentication_code)
for string messages.

### Random Strings

Also included is a basic random string generator in
`AngryBytes\Hash\RandomString`. It targets Unix systems with `/dev/urandom`
available for now.
