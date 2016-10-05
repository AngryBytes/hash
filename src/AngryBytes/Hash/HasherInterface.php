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
     * @param array $options Additional hasher options
     * @return string
     **/
    public function hash($data, array $options = []);

    /**
     * Verify is the data string matches the given hash
     *
     * @param string $data
     * @param string $hash
     * @param array $options Additional hasher options
     * @return bool
     */
    public function verify($data, $hash, array $options = []);
}
