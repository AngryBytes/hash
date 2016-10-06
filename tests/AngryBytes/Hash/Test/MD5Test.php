<?php
/**
 * MD5Test.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 * @copyright       Copyright (c) 2007-2016 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Hash;
use AngryBytes\Hash\Hasher\MD5 as MD5Hasher;

/**
 * Test the md5 hasher
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 */
class MD5Test extends \PHPUnit_Framework_TestCase
{
    /**
     * Test hashing of strings
     *
     * @return void
     **/
    public function testHashString()
    {
        $hasher = $this->createHasher();

        $this->assertEquals(
            'f149d11c9f6d8e899772b855588722f2',
            $hasher->hash('foo')
        );

        $this->assertEquals(
            '7b84dc4faca7b93ea519eade24a5f634',
            $hasher->hash('bar')
        );
    }

    /**
     * Test hashing of complex data
     *
     * @return void
     **/
    public function testHashObject()
    {
        $hasher = $this->createHasher();

        $obj = new \stdClass;
        $obj->foo = 'bar';

        $this->assertEquals(
            '2f4162ad4b8774272e452efafd25972f',
            $hasher->hash($obj)
        );

        $obj->bar = 'foo';

        $this->assertEquals(
            '0d8c867eedd83575b44c62f8caac142b',
            $hasher->hash($obj)
        );
    }

    /**
     * Test verification of string hashes
     */
    public function testVerifyString()
    {
        $hasher = $this->createHasher();

        $this->assertTrue(
            $hasher->verify('foo', 'f149d11c9f6d8e899772b855588722f2')
        );

        $this->assertFalse(
            $hasher->verify('bar', 'f149d11c9f6d8e899772b855588722f2')
        );
    }

    /**
     * Test verification of object hashes
     */
    public function testVerifyHashObject()
    {
        $hasher = $this->createHasher();

        $obj = new \stdClass;
        $obj->foo = 'bar';

        $this->assertTrue(
            $hasher->verify($obj, '2f4162ad4b8774272e452efafd25972f')
        );

        $obj->bar = 'foo';

        $this->assertFalse(
            $hasher->verify($obj, '2f4162ad4b8774272e452efafd25972f')
        );
    }

    /**
     * Create hasher
     *
     * @return Hash
     **/
    private function createHasher()
    {
        // Hasher
        return new Hash(
            new MD5Hasher,
            '909b96914de6866224f70f52a13e9fa6'
        );
    }
}
