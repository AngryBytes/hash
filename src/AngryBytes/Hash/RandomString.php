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

        // In case we can not read from /dev/urandom, we will need to generate
        // some bytes ourselves. This is less secure.
        if ($fp === false) {
            return self::generateBytesPHP($bytes);
        }

        // Read the required number of bytes
        $bytes =  fread($fp, $bytes);

        // Close the file handle
        fclose($fp);

        // Return them
        return $bytes;
    }

    /**
     * Generate random bytes using PHP
     *
     * This is less secure than using the operating system, but it is more
     * reliable.
     *
     * @param  int    $bytes
     * @return string
     **/
    private static function generateBytesPHP($bytes)
    {
        $output = '';
        for ($i = 0; $i < $len; ++$i) {
            $output .= chr(mt_rand(0, 255));
        }

        return $output;
    }
}
