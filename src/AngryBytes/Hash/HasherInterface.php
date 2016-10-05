<?php
/**
 * HasherInterface.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 * @copyright       Copyright (c) 2007-2016 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash;

/**
 * Common Contract For Hashers
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 */
interface HasherInterface
{
    /**
     * Hash a data string
     *
     * Implementation is supposed to salt the hashing method using $salt
     *
     * @param string $data
     * @param string $salt
     * @return string
     **/
    public function hash($data, $salt);

    /**
     * Verify is the data string matches the given hash
     *
     * @param string $data
     * @param string $hash
     * @param string $salt
     * @return bool
     */
    public function verify($data, $hash, $salt);
}
