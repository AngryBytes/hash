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
 * MD5
 *
 * Generate and verify MD5 hashes using a salt
 *
 * NOTE:
 *
 * This hasher MUST NOT be used for password storage. It is RECOMMENDED
 * to use the Hasher\Password for this purpose
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
    public function hash($string, $salt)
    {
        return md5($string . '-' . $salt);
    }

    /**
     * {@inheritDoc}
     *
     * @see Hash::compare()
     */
    public function verify($string, $hash, $salt)
    {
        return Hash::compare(
            $this->hash($string, $salt),
            $hash
        );
    }
}
