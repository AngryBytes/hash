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
 * Password hasher
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
     * Get cost
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set cost
     *
     * @throws InvalidArgumentException if the cost is too high or low
     * @param int|null $cost
     * @return $this
     */
    public function setCost($cost)
    {
        if (is_null($cost)) {
            // Use default cost
            return $this;
        }

        if ($cost < 4 || $cost > 31) {
            throw new InvalidArgumentException(
                'Cost needs to be greater than 3 and smaller than 32'
            );
        }
        $this->cost = (int) $cost;

        return $this;
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
            throw RuntimeException('Failed to hash password');
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
     * If true, rehashed the password after verifying it
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
        if ($salt) {
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
}

