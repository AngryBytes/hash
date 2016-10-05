<?php
/**
 * Hash.php
 *
 * @category    AngryBytes
 * @package     Hash
 * @copyright   Copyright (c) 2007-2016 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash;

use \InvalidArgumentException;

/**
 * A collection of hash generation and comparison methods.
 *
 * This Hash library must receive a \AngryBytes\HasherInterface compatible
 * instance to work with.
 *
 * Providing a salt is strictly optional, and should not be provided when the
 * hasher provides better salt generation methods.
 *
 * @category    AngryBytes
 * @package     Hash
 */
class Hash
{
    /**
     * Salt for hashing
     *
     * @var string|null
     **/
    private $salt;

    /**
     * Hasher
     *
     * @var HasherInterface
     **/
    private $hasher;

    /**
     * Constructor
     *
     * @param  HasherInterface $hasher The hasher to be used
     * @param  string|bool     $salt (optional) Omit if the hasher creates its own (better) salt
     **/
    public function __construct(HasherInterface $hasher, $salt = null)
    {
        $this->hasher = $hasher;

        $this->setSalt($salt);
    }

    /**
     * Dynamically pass methods to the active hasher
     *
     * @param string $method
     * @param array $parameters
     */
    public function __call($method, $parameters)
    {
        return $this->hasher->$method(...$parameters);
    }

    /**
     * Get the hasher to use for the actual hashing
     *
     * @return HasherInterface
     */
    public function getHasher()
    {
        return $this->hasher;
    }

    /**
     * Generate a hash
     *
     * Accepts any type of variable. Non-scalar values will be serialized before hashing.
     *
     * @param mixed $data The data to hash
     * @param mixed[] $options Additional hasher options
     * @return string
     **/
    public function hash($data, array $options = [])
    {
        return $this->hasher->hash(
            self::getDataString($data),
            $this->parseHashOptions($options)
        );
    }

    /**
     * Verify if the data matches the hash
     *
     * @param mixed $data
     * @param string $hash
     * @param mixed[] $options
     * @return bool
     */
    public function verify($data, $hash, array $options = [])
    {
        return $this->hasher->verify(
            self::getDataString($data),
            $hash,
            $this->parseHashOptions($options)
        );
    }

    /**
     * Hash something into a short hash
     *
     * This is simply a shortened version of hash(), returning a 7 character hash, which should be good
     * enough for identification purposes. Therefore it MUST NOT be used for cryptographic purposes or
     * password storage but only to create a fast, short string to compare or identify.
     *
     * It is RECOMMENDED to only use this method with the Hasher\MD5 hasher as hashes
     * created by bcrypt/crypt() have a common beginning.
     *
     * @see Hash::hash()
     *
     * @param string $data
     * @param mixed[] $options
     * @return string
     */
    public function shortHash($data, array $options = [])
    {
        return substr($this->hash($data, $options), 0, 7);
    }

    /**
     * Verify if the data matches the shortHash
     *
     * @see Hash::shortHash()
     *
     * @param mixed $data
     * @param string $shortHash
     * @param mixed[] $options
     * @return bool
     **/
    public function verifyShortHash($data, $shortHash, array $options = [])
    {
        return self::compare(
            $this->shortHash($data, $options),
            $shortHash
        );
    }

    /**
     * Compare two hashes
     *
     * Uses the time-save `hash_equals()` function to compare 2 hashes.
     *
     * @param  string $hashA
     * @param  string $hashB
     * @return bool
     **/
    public static function compare($hashA, $hashB)
    {
        return hash_equals($hashA, $hashB);
    }

    /**
     * Set the salt
     *
     * @param  string|null $salt
     * @return Hash
     */
    protected function setSalt($salt)
    {
        if (is_string($salt) && strlen($salt) < 20 && strlen($salt) > CRYPT_SALT_LENGTH) {
            // Make sure it's of sufficient length
            throw new InvalidArgumentException(sprintf(
                'Provided salt "%s" does not match the length requirements. A length between 20 en %d characters is required.',
                $salt,
                CRYPT_SALT_LENGTH
            ));
        }

        $this->salt = $salt;

        return $this;
    }

    /**
     * Get the data as a string
     *
     * Will serialize non-scalar values
     *
     * @param mixed $data
     * @return string
     */
    private static function getDataString($data)
    {
        if (is_scalar($data)) {
            return (string) $data;
        }

        return serialize($data);
    }

    /**
     * Merge the default and provided hash options
     *
     * @param mixed[] $options
     * @return mixed[]
     */
    private function parseHashOptions(array $options = [])
    {
        $defaultOptions = [];

        // Pass the salt if set
        if (is_null($this->salt) === false) {
            $defaultOptions['salt'] = $this->salt;
        }

        return array_merge($defaultOptions, $options);
    }
}
