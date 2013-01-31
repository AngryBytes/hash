<?php
/**
 * MD5.php
 *
 * ABC Manager 5
 *
 * @category        Abc
 * @package         Hash
 * @subpackage      Hasher
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace Abc\Hash\Hasher;

use Abc\Hash\HasherInterface;

/**
 * MD5
 *
 * MD5 hasher.
 *
 * You probably shouldn't use this for passwords
 *
 * @category        Abc
 * @package         Hash
 * @subpackage      Hasher
 */
class MD5 implements HasherInterface
{
    /**
     * Hash a value using md5
     *
     * @param string $data
     * @param string $salt
     * @return string
     */
    public function hash($data, $salt)
    {
        return md5($data . '-' . $salt);
    }
}
