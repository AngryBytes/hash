<?php
/**
 * HasherInterface.php
 *
 * ABC Manager 5
 *
 * @category        Abc
 * @package         Hash
 * @subpackage      Hasher
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace Abc\Hash;

/**
 * HasherInterface
 *
 * Interface for hashers
 *
 * @category        Abc
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
