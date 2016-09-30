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
 * Hash
 *
 * A collection of hash generation and comparison methods.
 *
 * @category    AngryBytes
 * @package     Hash
 */
class Hash
{
    /**
     * Salt for hashing
     *
     * @var string|bool
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
     * @param  string|bool     $salt (optional) Omit if the hasher creates its own salt
     **/
    public function __construct(HasherInterface $hasher, $salt = false)
    {
        $this
            ->setHasher($hasher)
            ->setSalt($salt);
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
     * Set the hasher to use for the actual hashing
     *
     * @param  HasherInterface $hasher
     * @return Hash
     */
    public function setHasher(HasherInterface $hasher)
    {
        $this->hasher = $hasher;

        return $this;
    }

    /**
     * Get the salt
     *
     * @return string|bool
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set the salt
     *
     * @param  string|bool $salt
     * @return Hash
     */
    public function setSalt($salt)
    {
        if ($salt) {
            // Make sure it's of sufficient length
            if (strlen($salt) < 20) {
                throw new InvalidArgumentException(sprintf(
                    'Provided salt "%s" is not long enough. A minimum length of 20 characters is required',
                    $salt
                ));
            }
        }

        $this->salt = $salt;

        return $this;
    }

    /**
     * Generate a hash
     *
     * Accepts any type of variable. Non-scalar values will be serialized before hashing.
     *
     * @param mixed $data The data to hash
     * @return string
     **/
    public function hash($data)
    {
        return $this->getHasher()->hash(
            $this->getDataString($data),
            $this->getSalt()
        );
    }

    /**
     * Verify if the data matches the hash
     *
     * @param mixed $data
     * @param string $hash
     * @return bool
     */
    public function verify($data, $hash)
    {
        return $this->getHasher()->verify(
            $this->getDataString($data),
            $hash,
            $this->getSalt()
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
     * @return string
     **/
    public function shortHash($data)
    {
        return substr($this->hash($data), 0, 7);
    }

    /**
     * Verify if the data matches the shortHash
     *
     * @see Hash::shortHash()
     *
     * @param mixed $data
     * @param string $shortHash
     * @return bool
     **/
    public function verifyShortHash($data, $shortHash)
    {
        return self::compare(
            $this->shortHash($data),
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
     * Get the data as a string
     *
     * Will serialize non-scalar values
     *
     * @return string
     **/
    private function getDataString($data)
    {
        if (is_scalar($data)) {
            return $data;
        }

        return serialize($data);
    }
}
