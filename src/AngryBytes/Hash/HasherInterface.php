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
     * @param  string $string
     * @param  mixed[] $options Additional hasher options
     * @return string
     **/
    public function hash($string, array $options = []);

    /**
     * Verify is the data string matches the given hash
     *
     * @param  string $string
     * @param  string $hash
     * @param  mixed[] $options Additional hasher options
     * @return bool
     */
    public function verify($string, $hash, array $options = []);
}
