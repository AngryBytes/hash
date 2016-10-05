<?php
/**
 * MD5.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

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
 * @see AngryBytes\Hasher\Password For a password hasher
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 */
class MD5 implements HasherInterface
{
    /**
     * {@inheritDoc}
     */
    public function hash($data, array $options = [])
    {
        $salt = isset($options['salt']) ? $options['salt'] : '';

        return md5($data . '-' . $salt);
    }

    /**
     * {@inheritDoc}
     *
     * @see Hash::compare()
     */
    public function verify($data, $hash, array $options = [])
    {
        return Hash::compare(
            $this->hash($data, $options),
            $hash
        );
    }
}
