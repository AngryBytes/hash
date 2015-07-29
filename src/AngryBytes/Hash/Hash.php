<?php
/**
 * Security
 *
 * @category    AngryBytes
 * @package     Hash
 * @copyright   Copyright (c) 2007-2012 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash;

use AngryBytes\Hash\HasherInterface;

use \InvalidArgumentException;

/**
 * Hash
 *
 * Hash generation and comparison methods.
 *
 * This hasher accepts a "salt" string that it uses for salting the hash
 * function.
 *
 * @category    AngryBytes
 * @package     Hash
 */
class Hash
{
    /**
     * Salt for hashing
     *
     * @var string
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
     * @param  HasherInterface $hasher
     * @param  string          $salt
     **/
    public function __construct(HasherInterface $hasher, $salt)
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
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set the salt
     *
     * @param  string $salt
     * @return Hash
     */
    public function setSalt($salt)
    {
        // Make sure it's of sufficient length
        if (strlen($salt) < 20) {
            throw new InvalidArgumentException(sprintf(
                'Provided salt "%s" is not long enough. A minimum length of 20 characters is required',
                $salt
            ));
        }

        $this->salt = $salt;

        return $this;
    }

    /**
     * Generate a hash
     *
     * Will accept any number of (serializable) variables
     *
     * @param mixed $what1
     * @param mixed $what2
     * ...
     * @param  mixed  $whatN
     * @return string
     **/
    public function hash()
    {
        return $this->getHasher()->hash(
            call_user_func_array(
                array($this, 'getDataString'),
                func_get_args()
            ),
            $this->getSalt()
        );
    }

    /**
     * Hash something into a short hash
     *
     * This is simply a shortened version of hash(), returning a 7 character
     * hash, which should be good enough for identification purposes. It is
     * however *not* strong enough for cryptographical uses or password
     * storage. Use only to create a fast, short string to compare or identify.
     *
     * @see Hash::hash()
     *
     * @param mixed $what1
     * @param mixed $what2
     * ...
     * @param  mixed  $whatN
     * @return string
     **/
    public function shortHash()
    {
        $fullHash = call_user_func_array(
            array($this, 'hash'),
            func_get_args()
        );

        return substr($fullHash, 0, 7);
    }

    /**
     * Does something match a short hash?
     *
     * First argument to this function is the short hash as it *should* be, all other arguments will
     * be passed to shortHash()
     *
     * @see Hash::shortHash()
     *
     * @param  mixed  $what
     * @param  string $hash
     * @return bool
     **/
    public function matchesShortHash()
    {
        // Full args to method
        $args = func_get_args();

        // Remove compareTo from the beginning
        $compareTo = array_shift($args);

        // Create short hash to compare to
        $shortHash = call_user_func_array(
            array($this, 'shortHash'),
            $args
        );

        // Compare and return
        return self::compare($compareTo, $shortHash);
    }

    /**
     * Compare two hashes
     *
     * This method is time-safe, i.e. it will take the same amount of time to
     * execute for all inputs. This is critical to avoid timing attacks.
     *
     * NOTE:
     *
     * **This method only works on ASCII strings.**
     *
     * @param  string $hashA
     * @param  string $hashB
     * @return bool
     **/
    public static function compare($hashA, $hashB)
    {
        // Compare length
        if (strlen($hashA) !== strlen($hashB)) {
            return false;
        }

        // bitwise OR total for all characters
        $result = 0;

        for ($charIndex = 0; $charIndex < strlen($hashA); $charIndex++) {
            // XOR the value of the ASCII characters at $charIndex
            // This value is then OR-ed into the total
            $result |= ord($hashA[$charIndex]) ^ ord($hashB[$charIndex]);
        }

        // If the result is anything but 0 there was a differing character at
        // one or more of the positions
        return $result === 0;
    }

    /**
     * Get the passed arguments as a string
     *
     * Accepts anything serializable
     *
     * @param mixed $what1
     * @param mixed $what2
     * ...
     * @param  mixed  $whatN
     * @return string
     **/
    private function getDataString()
    {
        return serialize(func_get_args());
    }
}
