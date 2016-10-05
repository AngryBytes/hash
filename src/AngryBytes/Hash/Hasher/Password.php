<?php
/**
 * Password.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 * @copyright       Copyright (c) 2007-2016 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash\Hasher;

use AngryBytes\Hash\HasherInterface;

use \InvalidArgumentException;
use \RuntimeException;

/**
 * Password Hasher Using Native PHP Hash Methods
 *
 * Generate and verify hashes using the `password_*` functions.
 * The hashing algorithm and salting is handled by these functions.
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 */
class Password implements HasherInterface
{
    /**
     * The hashing algorithm
     *
     * @var int
     */
    const ALGORITHM = PASSWORD_DEFAULT;

    /**
     * Cost factor for the algorithm
     *
     * @var int
     */
    private $cost;

    /**
     * Password constructor.
     *
     * @param null|int $cost
     */
    public function __construct($cost = null)
    {
        $this->setCost($cost);
    }

    /**
     * {@inheritDoc}
     *
     * @throws RuntimeException If the hashing fails
     * @param string|bool $salt (optional) When omitted `password_hash()` will generate it's own salt
     */
    public function hash($data, $salt = false)
    {
        // Set hash options
        $options = [];

        if (is_int($this->cost)) {
            $options['cost'] = $this->cost;
        }
        if ($salt) {
            $options['salt'] = $salt;
        }

        $hash = password_hash($data, self::ALGORITHM, $options);
        if (!$hash) {
            throw new RuntimeException('Failed to hash password');
        }

        return $hash;
    }

    /**
     * {@inheritDoc}
     *
     * NOTE `$salt` is not used, `password_verify()` retrieves the used salt from the `$hash`
     */
    public function verify($data, $hash, $salt)
    {
        return password_verify($data, $hash);
    }

    /**
     * Determine if the password needs to be rehashed based on the hash options
     *
     * If true, the password should be rehashed after verification.
     *
     * @param string $hash
     * @param string|bool $salt (optional) When omitted `password_needs_rehash()` will generate it's own salt
     * @return bool
     */
    public function needsRehash($hash, $salt = false)
    {
        // Set hash options
        $options = [];
        if (is_int($this->cost)) {
            $options['cost'] = $this->cost;
        }
        if (is_string($salt)) {
            $options['salt'] = $salt;
        }

        return password_needs_rehash($hash, self::ALGORITHM, $options);
    }

    /**
     * Get info for the given hash
     *
     * @param string $hash
     * @return mixed[]
     */
    public function getInfo($hash)
    {
        return password_get_info($hash);
    }

    /**
     * Set cost
     *
     * @throws InvalidArgumentException if the cost is too high or low
     * @param int|null $cost
     */
    public function setCost($cost)
    {
        if (is_null($cost)) {
            $this->cost = $cost;

            return;
        }

        if ($cost < 4 || $cost > 31) {
            throw new InvalidArgumentException(sprintf(
                'Cost value "%d" needs to be greater than 3 and smaller than 32', (int) $cost
            ));
        }

        $this->cost = (int) $cost;
    }
}

