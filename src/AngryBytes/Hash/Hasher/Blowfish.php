<?php

namespace AngryBytes\Hash\Hasher;

use AngryBytes\Hash\Hash;
use AngryBytes\Hash\HasherInterface;

/**
 * Blowfish Hasher
 *
 * Generate and verify Blowfish bcrypt/crypt() hashes.
 *
 * @see AngryBytes\Hasher\Password For a password hasher
 */
class Blowfish implements HasherInterface
{
    /**
     * Work factor for blowfish
     *
     * Defaults to '15' (32768 iterations)
     */
    private int $workFactor = 15;

    public function __construct(?int $workFactor = null)
    {
        if (is_int($workFactor)) {
            $this->setWorkFactor($workFactor);
        }
    }

    /**
     * Get the blowfish work factor
     */
    public function getWorkFactor(): int
    {
        return $this->workFactor;
    }

    /**
     * Set the blowfish work factor
     */
    public function setWorkFactor(int $workFactor): self
    {
        if ($workFactor < 4 || $workFactor > 31) {
            throw new \InvalidArgumentException(
                'Work factor needs to be greater than 3 and smaller than 32'
            );
        }
        $this->workFactor = (int) $workFactor;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hash(string $string, array $options = []): string
    {
        $salt = '';
        if (isset($options['salt'])) {
            assert(is_string($options['salt']));
            $salt = $this->bcryptSalt($options['salt']);
        }

        return crypt($string, $salt);
    }

    /**
     * {@inheritDoc}
     *
     * @see Hash::compare()
     */
    public function verify(string $string, string $hash, array $options = []): bool
    {
        return Hash::compare(
            $this->hash($string, $options),
            $hash
        );
    }

    /**
     * Generate a bcrypt salt from a string salt
     *
     * @return string Format: "$2y$[workfactor]$[salt]$"
     */
    private function bcryptSalt(string $salt): string
    {
        return '$2y$'
            // Pad workfactor with 0's to the left, max 2 chars long
            . str_pad((string) $this->getWorkFactor(), 2, '0', STR_PAD_LEFT)
            // Add salt itself
            . '$' .
            self::getSaltSubstr($salt)
            . '$'
        ;
    }

    /**
     * Get valid salt string for Blowfish usage
     *
     * Blowfish accepts 22 chars (./0-9A-Za-z) as a salt if anything else is passed,
     * this method will take a hash of $salt to transform it into 22 supported characters
     */
    private static function getSaltSubstr(string $salt): string
    {
        // Return salt when it is a valid Blowfish salt
        if (preg_match('!^[\./0-9A-Za-z]{22}$!', $salt) === 1) {
            return $salt;
        }

        // fallback to md5() to make the salt valid
        return substr(
            md5($salt),
            0,
            22
        );
    }
}
