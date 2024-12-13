<?php

namespace AngryBytes\Hash\Hasher;

use AngryBytes\Hash\Hash;
use AngryBytes\Hash\HasherInterface;

/**
 * MD5 Hasher
 *
 * Generate and verify MD5 hashes.
 *
 * NOTE:
 *
 * This hasher MUST NOT be used for password storage. It is RECOMMENDED
 * to use the Hasher\Password for this purpose
 *
 * @see Password For a password hasher
 */
class MD5 implements HasherInterface
{
    /**
     * {@inheritDoc}
     */
    public function hash(string $string, array $options = []): string
    {
        $salt = $options['salt'] ?? '';
        assert(is_string($salt));

        return md5($string . '-' . $salt);
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
}
