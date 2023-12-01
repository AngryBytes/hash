<?php

namespace AngryBytes\Hash\Hasher;

use AngryBytes\Hash\HasherInterface;
use InvalidArgumentException;
use RuntimeException;
use ValueError;

/**
 * Password Hasher Using Native PHP Hash Methods
 *
 * Generate and verify hashes using the `password_*` functions.
 * The hashing algorithm and salting is handled by these functions.
 */
class Password implements HasherInterface
{
    /**
     * Cost factor for the algorithm
     */
    private ?int $cost;

    public function __construct(?int $cost = null)
    {
        $this->setCost($cost);
    }

    /**
     * {@inheritDoc}
     *
     * Supported options in $options array:
     * - 'cost': Override the default cost (not advised)
     *
     * @throws RuntimeException If the hashing fails
     */
    public function hash(string $data, array $options = []): string
    {
        try {
            $hash = password_hash($data, PASSWORD_DEFAULT, $this->parsePasswordOptions($options));
        } catch (ValueError $e) {
            throw new RuntimeException(sprintf(
                'Failed to hash password. An exception was thrown: %s',
                $e->getMessage()
            ));
        }

        return $hash;
    }

    /**
     * {@inheritDoc}
     */
    public function verify(string $string, string $hash, array $options = []): bool
    {
        return password_verify($string, $hash);
    }

    /**
     * Determine if the password needs to be rehashed based on the hash options
     *
     * If true, the password should be rehashed after verification.
     *
     * @param mixed[] $options Password options, @see self::hash()
     */
    public function needsRehash(string $hash, array $options = []): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT, $this->parsePasswordOptions($options));
    }

    /**
     * Get info for the given hash
     *
     * @see password_get_info()
     *
     * @return mixed[]
     */
    public function getInfo(string $hash): array
    {
        return password_get_info($hash);
    }

    /**
     * Set cost
     *
     * @param  ?int $cost
     * @throws InvalidArgumentException if the cost is too high or low
     */
    public function setCost(?int $cost = null): self
    {
        if (is_null($cost)) {
            $this->cost = $cost;

            return $this;
        }

        if ($cost < 4 || $cost > 31) {
            throw new InvalidArgumentException(sprintf(
                'Cost value "%d" needs to be greater than 3 and smaller than 32',
                (int) $cost
            ));
        }

        $this->cost = (int) $cost;

        return $this;
    }

    /**
     * Parse password options for hash methods
     *
     * @param  mixed[] $options
     * @return mixed[]
     */
    private function parsePasswordOptions(array $options): array
    {
        // Parse options
        if (!isset($options['cost']) && is_int($this->cost)) {
            $options['cost'] = $this->cost;
        }

        return $options;
    }
}
