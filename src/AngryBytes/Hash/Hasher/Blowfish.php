<?php
/**
 * Blowfish.php
 *
 * AngryBytes Manager 5
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash\Hasher;

use AngryBytes\Hash\HasherInterface;

use \RuntimeException;
use \InvalidArgumentException;

/**
 * Blowfish
 *
 * Blowfish hasher
 *
 * Relies on bcrypt/crypt() for the heavy lifting
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Hasher
 *
 * @hootie
 */
class Blowfish implements HasherInterface
{
    /**
     * Work factor for blowfish
     *
     * @var int
     **/
    private $workFactor = 15;

    /**
     * Constructor
     *
     * @throws RuntimeException
     *
     * @return void
     **/
    public function __construct()
    {
        if (!defined("CRYPT_BLOWFISH") || CRYPT_BLOWFISH !== 1) {
            throw new RuntimeException(
                'Blowfish hashing not available on this installation'
            );
        }
    }

    /**
     * Get the blowfish work factor
     *
     * @return int
     */
    public function getWorkFactor()
    {
        return $this->workFactor;
    }

    /**
     * Set the blowfish work factor
     *
     * @param int $workFactor
     * @return Blowfish
     */
    public function setWorkFactor($workFactor)
    {
        if ($workFactor < 4 || $workFactor > 31) {
            throw new InvalidArgumentException(
                'Work factor needs to be greater than 3 and smaller than 32'
            );
        }
        $this->workFactor = $workFactor;

        return $this;
    }

    /**
     * Hash password and salt
     *
     * @param  string $data
     * @param  string $salt
     * @return string
     **/
    public function hash($data, $salt)
    {
        return crypt($data, $this->bcryptSalt($salt));
    }

    /**
     * Generate a bcrypt salt from an AngryBytes Manager default salt
     *
     * @param  string $salt
     * @return string
     **/
    private function bcryptSalt($salt)
    {
        return '$2y$'
            // Pad workfactor with 0's to the left, max 2 chars long
            . str_pad($this->getWorkFactor(), 2, '0', STR_PAD_LEFT)
            // Add salt itself
            . '$' .
            self::getSaltSubstr($salt)
            . '$'
        ;
    }

    /**
     * Get valid salt substr for blowfish
     *
     * Blowfish accepts 22 chars as a salt
     *
     * Will take a hash of $salt to take changes over 22 chars into account
     *
     * @param  string $salt
     * @return string
     **/
    private static function getSaltSubstr($salt)
    {
        return substr(
            md5($salt),
            0, 22
        );
    }
}

