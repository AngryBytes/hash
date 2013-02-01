<?php
/**
 * HasherInterface.php
 *
 * AngryBytes Manager 5
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash;

/**
 * HasherInterface
 *
 * Interface for hashers
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
}
