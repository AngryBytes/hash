<?php
/**
 * RandomString.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash;

/**
 * Random string generator
 *
 * Targets UNIX systems with /dev/urandom only
 *
 * @category        AngryBytes
 * @package         Hash
**/
class RandomString
{
    /**
     * Generate $bytes of random bytes
     *
     * @param  int    $bytes
     * @return string
     **/
    public static function generateBytes($bytes)
    {
        // Open /dev/urandom for reading
        $fp = @fopen('/dev/urandom','rb');

        if ($fp === false) {
            throw new RuntimeException('Can not read from /dev/urandom');
        }

        return fread($fp, $bytes);
    }
}
