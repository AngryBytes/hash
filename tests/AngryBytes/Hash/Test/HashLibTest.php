<?php
/**
 * HashLibTest.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 * @copyright       Copyright (c) 2007-2016 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Hash;

/**
 * Test the Hash Lib
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 */
class HashLibTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test new hasher with valid salt
     */
    public function testValidSalt()
    {
        new Hash(
            new \AngryBytes\Hash\Hasher\MD5
        );
        new Hash(
            new \AngryBytes\Hash\Hasher\MD5,
            str_repeat('a', 20)
        );
    }

    /**
     * Test new hasher with salt set too short
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSaltTooShort()
    {
        new Hash(
            new \AngryBytes\Hash\Hasher\MD5,
            str_repeat('a', 19)
        );
    }

    /**
     * Test new hasher with salt set too long
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSaltTooLong()
    {
        new Hash(
            new \AngryBytes\Hash\Hasher\MD5,
            str_repeat('a', CRYPT_SALT_LENGTH + 1)
        );
    }
}
